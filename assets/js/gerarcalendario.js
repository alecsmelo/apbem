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
}

function trocarMesOuAno() {
  novoCalendario();
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

function preencherFormularioComDados(dados) {
  if (dados.mes) document.getElementById('mes').value = String(dados.mes);
  if (dados.ano) document.getElementById('ano').value = String(dados.ano);

  // Recria a grade limpa para o mês/ano carregado
  novoCalendario();

  // Preenche configurações gerais
  const banner = dados.bannerCampanha || '';
  document.getElementById('bannerCampanha').value = banner;
  atualizarPreviewBanner(banner);

  document.getElementById('bannerAlt').value = dados.bannerAlt || '';
  document.getElementById('horarioDispensacao').value = dados.horarioDispensacao || '08h às 11:15h';
  document.getElementById('observacaoExtra').value = dados.observacaoExtra || '';

  if (Array.isArray(dados.judicializadas)) {
    document.getElementById('judicializadas').value = dados.judicializadas.join(', ');
  }

  // Preenche cada dia salvo no arquivo JSON individual
  const dias = dados.dias || {};
  Object.keys(dias).forEach(diaKey => {
    const diaNum = parseInt(diaKey, 10);
    const infoDia = dias[diaKey];
    const selectTipo = document.getElementById(`tipo-dia-${diaNum}`);
    const textareaTexto = document.getElementById(`texto-dia-${diaNum}`);

    if (selectTipo && infoDia.tipo) {
      selectTipo.value = infoDia.tipo;
      atualizarEstiloCardDia(selectTipo);
    }

    if (textareaTexto && Array.isArray(infoDia.itens)) {
      textareaTexto.value = infoDia.itens.join('\n');
    }
  });
}

// 1. Lê lista-calendarios.json -> 2. Localiza a chave AAAA-MM -> 3. Carrega o JSON individual daquele calendário
async function carregarCalendarioSalvoPorMesAno() {
  const mes = parseInt(document.getElementById('mes').value, 10);
  const ano = parseInt(document.getElementById('ano').value, 10);
  const chave = `${String(ano).padStart(4, '0')}-${String(mes).padStart(2, '0')}`;

  try {
    const respIndice = await fetch(`assets/data/lista-calendarios.json?t=${Date.now()}`, {
      cache: 'no-store'
    });

    if (respIndice.ok) {
      const indice = await respIndice.json();
      const totalSalvos = indice.total || 0;
      const registro = indice.calendarios ? indice.calendarios[chave] : null;

      if (!registro || !registro.arquivo) {
        exibirStatus(
          `Calendário ${String(mes).padStart(2, '0')}/${ano} não encontrado na lista (total de calendários salvos: ${totalSalvos}).`,
          '#B45309'
        );
        return;
      }

      // Carrega o arquivo JSON exclusivo daquele calendário
      const respCalendario = await fetch(`${registro.arquivo}?t=${Date.now()}`, {
        cache: 'no-store'
      });

      if (!respCalendario.ok) {
        exibirStatus(`Erro ao abrir o arquivo ${registro.arquivo}.`, '#DC2626');
        return;
      }

      const dadosCalendario = await respCalendario.json();
      preencherFormularioComDados(dadosCalendario);

      exibirStatus(
        `Calendário ${String(mes).padStart(2, '0')}/${ano} carregado de ${registro.arquivo} (${totalSalvos} salvos no total).`,
        '#16A34A'
      );
      return;
    }
  } catch (e) {
    console.warn('Erro ao consultar lista-calendarios.json:', e);
  }

  // Fallback caso lista-calendarios.json ainda não tenha sido gerado (primeiro uso)
  try {
    const respPub = await fetch(`assets/data/calendario.json?t=${Date.now()}`, {
      cache: 'no-store'
    });
    if (respPub.ok) {
      const pub = await respPub.json();
      if (pub && parseInt(pub.mes, 10) === mes && parseInt(pub.ano, 10) === ano) {
        preencherFormularioComDados(pub);
        exibirStatus(`Calendário ${String(mes).padStart(2, '0')}/${ano} carregado do arquivo ativo.`, '#16A34A');
        return;
      }
    }
  } catch (e) {}

  exibirStatus(`Nenhum calendário salvo encontrado para ${String(mes).padStart(2, '0')}/${ano}.`, '#B45309');
}

async function carregarCalendarioInicial() {
  let dados = null;

  try {
    const resp = await fetch(`assets/data/calendario.json?t=${Date.now()}`, {
      cache: 'no-store'
    });
    if (resp.ok) {
      dados = await resp.json();
    }
  } catch (e) {}

  if (!dados && typeof window.DADOS_CALENDARIO === 'object' && window.DADOS_CALENDARIO !== null) {
    dados = window.DADOS_CALENDARIO;
  }

  if (dados) {
    preencherFormularioComDados(dados);
    exibirStatus(
      `Exibindo calendário publicado atualmente (${String(dados.mes).padStart(2, '0')}/${dados.ano}).`,
      '#0369A1'
    );
  } else {
    novoCalendario();
  }
}

async function enviarBannerUpload(input) {
  if (!input.files || !input.files[0]) return;

  const textoCaminho = document.getElementById('textoBannerCaminho');
  const container = document.getElementById('previewBannerContainer');
  container.style.display = 'block';
  textoCaminho.textContent = 'Enviando imagem...';

  const formData = new FormData();
  formData.append('banner', input.files[0]);

  try {
    const resp = await fetch('upload-banner.php', {
      method: 'POST',
      body: formData
    });
    const resultado = await resp.json();

    if (resultado.sucesso) {
      document.getElementById('bannerCampanha').value = resultado.caminho;
      atualizarPreviewBanner(resultado.caminho);
      exibirStatus('Banner enviado para /uploads! Clique em Salvar ou Publicar para gravar.', '#0369A1');
    } else {
      textoCaminho.textContent = resultado.mensagem || 'Erro no upload.';
      input.value = '';
    }
  } catch (e) {
    textoCaminho.textContent = 'Falha ao enviar imagem para o servidor.';
  }
}

function atualizarPreviewBanner(caminho) {
  const container = document.getElementById('previewBannerContainer');
  const img = document.getElementById('previewBannerImg');
  const texto = document.getElementById('textoBannerCaminho');

  if (caminho) {
    img.src = caminho;
    texto.textContent = caminho;
    container.style.display = 'block';
  } else {
    img.src = '';
    texto.textContent = '';
    container.style.display = 'none';
  }
}

function removerBanner() {
  document.getElementById('bannerCampanha').value = '';
  document.getElementById('bannerUpload').value = '';
  atualizarPreviewBanner('');
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

async function salvarCalendario(acao = 'salvar') {
  const dados = montarObjetoCalendario();
  const payload = JSON.stringify({
    acao: acao,
    calendario: dados
  }, null, 2);

  try {
    const resposta = await fetch('salvar-calendario.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: payload
    });

    if (resposta.ok) {
      const resultado = await resposta.json();
      if (resultado.sucesso) {
        const labelMesAno = `${String(dados.mes).padStart(2, '0')}/${dados.ano}`;
        if (acao === 'publicar') {
          exibirStatus(
            `Calendário ${labelMesAno} salvo (${resultado.arquivo}) e PUBLICADO no site! Total salvos: ${resultado.total}.`,
            '#16A34A'
          );
        } else {
          exibirStatus(
            `Calendário ${labelMesAno} salvo em ${resultado.arquivo} (total salvos: ${resultado.total}).`,
            '#0369A1'
          );
        }
        return;
      }
    }
  } catch (e) {}

  exibirStatus('Não foi possível salvar no servidor. Verifique o salvar-calendario.php.', '#DC2626');
}

function exibirStatus(mensagem, cor) {
  const status = document.getElementById('statusSalvamento');
  if (status) {
    status.textContent = mensagem;
    status.style.color = cor;
    status.style.display = 'block';
  }
}

document.addEventListener('DOMContentLoaded', () => {
  carregarCalendarioInicial();
});