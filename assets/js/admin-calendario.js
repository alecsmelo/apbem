// Estado inicial dos eventos (tenta carregar do rascunho local ou usa o calendario.js existente)
let dadosCalendario = JSON.parse(localStorage.getItem('rascunho_calendario')) || (typeof eventos !== 'undefined' ? eventos : {});

const containerGrade = document.getElementById('grade-calendario'); // Div onde a grade será renderizada

// 1. Renderiza a grade vazia (ex: 31 dias) para preenchimento
function renderizarGradeVazia(totalDias = 31, mesAno = '2026-11') {
  containerGrade.innerHTML = '';

  for (let dia = 1; dia <= totalDias; dia++) {
    const diaFormatado = String(dia).padStart(2, '0');
    const dataChave = `${mesAno}-${diaFormatado}`;
    const valorAtual = dadosCalendario[dataChave] || '';

    const celula = document.createElement('div');
    celula.className = 'dia-celula';
    celula.innerHTML = `
      <label><strong>Dia ${diaFormatado}</strong></label>
      <textarea 
        data-data="${dataChave}" 
        placeholder="Digite os eventos deste dia..."
        rows="3"
      >${valorAtual}</textarea>
    `;

    // Salva automaticamente no localStorage enquanto você digita
    const textarea = celula.querySelector('textarea');
    textarea.addEventListener('input', (e) => {
      const texto = e.target.value.trim();
      if (texto) {
        dadosCalendario[dataChave] = texto;
      } else {
        delete dadosCalendario[dataChave]; // Remove dias vazios para manter o arquivo limpo
      }
      localStorage.setItem('rascunho_calendario', JSON.stringify(dadosCalendario));
    });

    containerGrade.appendChild(celula);
  }
}

// 2. Gera e baixa o arquivo calendario.js pronto para substituir o antigo
function exportarCalendarioJS() {
  const conteudoArquivo = `// Arquivo gerado automaticamente pelo Admin Calendário\nconst eventos = ${JSON.stringify(dadosCalendario, null, 2)};\n`;

  const blob = new Blob([conteudoArquivo], { type: 'application/javascript;charset=utf-8' });
  const url = URL.createObjectURL(blob);

  const link = document.createElement('a');
  link.href = url;
  link.download = 'calendario.js';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
  URL.revokeObjectURL(url);
}

// Inicializa a grade ao abrir a página
renderizarGradeVazia();