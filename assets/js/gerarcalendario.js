  let fileHandleCalendario = null;

    function novoCalendario() {
      const mesSelect = document.getElementById('mes');
      const mes = parseInt(mesSelect.value, 10);
      const ano = parseInt(document.getElementById('ano').value, 10);
      const container = document.getElementById('diasContainer');
      const status = document.getElementById('statusSalvamento');

      container.innerHTML = '';
      if (status) status.style.display = 'none';

      const nomesDiasSemana = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
      const totalDias = new Date(ano, mes, 0).getDate();

      for (let dia = 1; dia <= totalDias; dia++) {
        const dataDia = new Date(ano, mes - 1, dia);
        const diaSemanaIndex = dataDia.getDay();
        const isFds = (diaSemanaIndex === 0 || diaSemanaIndex === 6);
        const diaFormatado = String(dia).padStart(2, '0');

        const card = document.createElement('div');
        card.className = 'dia-card' + (isFds ? ' is-fds' : '');
        card.setAttribute('data-dia', dia);

        card.innerHTML = `
          <div class="dia-card-header">
            <span class="dia-card-titulo">Dia ${diaFormatado}</span>
            <span class="dia-card-badge">${nomesDiasSemana[diaSemanaIndex]}</span>
          </div>

          <label for="tipo-dia-${dia}">Tipo do Dia</label>
          <select id="tipo-dia-${dia}" class="campo-tipo-dia" onchange="atualizarEstiloCardDia(this)">
            <option value="medicacao" ${!isFds ? 'selected' : ''}>Dispensação Normal</option>
            <option value="aviso">Aviso / Sem Atendimento</option>
            <option value="fds" ${isFds ? 'selected' : ''}>Fim de Semana</option>
          </select>

          <label for="texto-dia-${dia}">Medicações / Observação (1 por linha)</label>
          <textarea 
            id="texto-dia-${dia}" 
            class="campo-texto-dia" 
            rows="3" 
            placeholder="${isFds ? 'SEM ATENDIMENTO' : 'EX:\nNATALIZUMABE\nFINGOLIMODE'}"
          ></textarea>
        `;

        container.appendChild(card);
      }

      const btnPrincipal = document.getElementById('btnAtualizarDados');
      btnPrincipal.textContent = 'Salvar';
      btnPrincipal.setAttribute('onclick', 'salvarDiretoNoCalendarioJSON()');

      const instrucao = document.getElementById('textoInstrucaoBotao');
      if (instrucao) instrucao.textContent = 'Salvar';
    }

    function atualizarEstiloCardDia(selectEl) {
      const card = selectEl.closest('.dia-card');
      card.classList.remove('is-fds', 'is-aviso');

      if (selectEl.value === 'fds') {
        card.classList.add('is-fds');
      } else if (selectEl.value === 'aviso') {
        card.classList.add('is-aviso');
      }
    }

    function montarObjetoCalendario() {
      const mes = parseInt(document.getElementById('mes').value, 10);
      const ano = parseInt(document.getElementById('ano').value, 10);
      const bannerCampanha = document.getElementById('bannerCampanha').value.trim();
      const bannerAlt = document.getElementById('bannerAlt').value.trim();
      const horarioDispensacao = document.getElementById('horarioDispensacao').value.trim();
      const observacaoExtra = document.getElementById('observacaoExtra').value.trim();
      const judicializadasRaw = document.getElementById('judicializadas').value.trim();

      const judicializadas = judicializadasRaw
        ? judicializadasRaw.split(',').map(item => item.trim().toUpperCase()).filter(Boolean)
        : [];

      const dias = {};
      document.querySelectorAll('#diasContainer .dia-card').forEach(card => {
        const dia = card.getAttribute('data-dia');
        const tipo = card.querySelector('.campo-tipo-dia').value;
        const texto = card.querySelector('.campo-texto-dia').value.trim().toUpperCase();
        const itens = texto ? texto.split('\n').map(l => l.trim()).filter(Boolean) : [];

        if (itens.length > 0 || tipo === 'aviso') {
          dias[dia] = {
            tipo: tipo === 'fds' ? 'aviso' : tipo,
            itens: itens
          };
        }
      });

      return {
        ano: ano,
        mes: mes,
        bannerCampanha: bannerCampanha,
        bannerAlt: bannerAlt,
        horarioDispensacao: horarioDispensacao,
        observacaoExtra: observacaoExtra,
        judicializadas: judicializadas,
        dias: dias
      };
    }

    async function salvarDiretoNoCalendarioJSON() {
      const dados = montarObjetoCalendario();
      const jsonString = JSON.stringify(dados, null, 2);

      // 1. Tenta salvar no servidor via PHP (atualiza assets/data/calendario.json e assets/data/calendario.js)
      try {
        const resposta = await fetch('salvar-calendario.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: jsonString
        });

        if (resposta.ok) {
          const resultado = await resposta.json();
          if (resultado.sucesso) {
            exibirStatus('Arquivo assets/data/calendario.json atualizado diretamente com sucesso!', '#16A34A');
            return;
          }
        }
      } catch (e) {
        // Sem PHP ativo; segue para sobrescrita local directa
      }

      // 2. Fallback local: sobrescreve directamente assets/data/calendario.json no disco
      if ('showOpenFilePicker' in window) {
        try {
          if (!fileHandleCalendario) {
            const [handle] = await window.showOpenFilePicker({
              types: [{
                description: 'Arquivo JSON (assets/data/calendario.json)',
                accept: { 'application/json': ['.json'] }
              }],
              multiple: false
            });
            fileHandleCalendario = handle;
          }

          const writable = await fileHandleCalendario.createWritable();
          await writable.write(jsonString);
          await writable.close();

          exibirStatus(`Arquivo "${fileHandleCalendario.name}" atualizado diretamente com sucesso!`, '#16A34A');
          return;
        } catch (err) {
          if (err.name === 'AbortError') {
            exibirStatus('Operação cancelada. Selecione assets/data/calendario.json para salvar.', '#B45309');
            return;
          }
        }
      }

      exibirStatus('Não foi possível acessar o arquivo diretamente. Verifique o servidor PHP ou use Chrome/Edge.', '#DC2626');
    }

    function exibirStatus(mensagem, cor) {
      const status = document.getElementById('statusSalvamento');
      if (status) {
        status.textContent = mensagem;
        status.style.color = cor;
        status.style.display = 'block';
      }
    }