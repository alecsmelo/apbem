const NOMES_MESES = [
  "JANEIRO", "FEVEREIRO", "MARÇO", "ABRIL", "MAIO", "JUNHO",
  "JULHO", "AGOSTO", "SETEMBRO", "OUTUBRO", "NOVEMBRO", "DEZEMBRO"
];

const DIAS_SEMANA_CURTO = ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"];

const TERMOS_NAO_MEDICAMENTOS = [
  "ÚLTIMO DIA DE DISPENSAÇÃO",
  "ULTIMO DIA DE DISPENSACAO"
];

async function carregarCalendario() {
  try {
    const resposta = await fetch(`assets/data/calendario.json?v=${Date.now()}`);
    if (resposta.ok) {
      const dadosJson = await resposta.json();
      window.DADOS_CALENDARIO = dadosJson;
      renderizarCalendario(dadosJson);
      return;
    }
  } catch (erro) {
    // Fallback para window.DADOS_CALENDARIO caso seja aberto via file:// sem servidor HTTP
  }

  if (window.DADOS_CALENDARIO) {
    renderizarCalendario(window.DADOS_CALENDARIO);
  } else {
    document.getElementById("calGrid").innerHTML =
      `<p style="grid-column:1/-1; background:#fff; padding:20px; border-radius:12px; text-align:center;">
        Arquivo assets/data/calendario.json não encontrado.
      </p>`;
  }
}

function renderizarCalendario(dados) {
  const {
    ano,
    mes,
    bannerCampanha,
    bannerAlt,
    horarioDispensacao,
    observacaoExtra,
    judicializadas,
    dias
  } = dados;

  // Banner de Campanha acima do calendário
  const bannerWrapper = document.getElementById("calBannerWrapper");
  const bannerImg = document.getElementById("calBannerImg");
  if (bannerCampanha && bannerCampanha.trim() !== "") {
    bannerImg.src = bannerCampanha.trim();
    bannerImg.alt = bannerAlt || "Banner da campanha mensal";
    bannerWrapper.style.display = "block";
    bannerImg.onerror = () => { bannerWrapper.style.display = "none"; };
  } else {
    bannerWrapper.style.display = "none";
  }

  // Cabeçalho e Rodapé do Quadro
  document.getElementById("calNomeMes").textContent = `${NOMES_MESES[mes - 1]} ${ano}`;
  document.getElementById("calHorario").textContent = horarioDispensacao || "08h às 11:15h";
  document.getElementById("calObsExtra").textContent = observacaoExtra || "";

  const primeiroDiaSemana = new Date(ano, mes - 1, 1).getDay();
  const totalDiasMes = new Date(ano, mes, 0).getDate();

  const grid = document.getElementById("calGrid");
  grid.innerHTML = "";

  const medicamentosUnicos = new Set();

  for (let i = 0; i < primeiroDiaSemana; i++) {
    const celulaVazia = document.createElement("div");
    celulaVazia.className = "cal-day is-empty";
    grid.appendChild(celulaVazia);
  }

  for (let dia = 1; dia <= totalDiasMes; dia++) {
    const diaSemana = new Date(ano, mes - 1, dia).getDay();
    const isWeekend = diaSemana === 0 || diaSemana === 6;
    const registro = dias[String(dia)];

    const celula = document.createElement("article");
    celula.className = "cal-day";
    if (isWeekend) celula.classList.add("is-weekend");
    if (registro && registro.tipo === "aviso") celula.classList.add("is-aviso");

    const numFormatado = String(dia).padStart(2, "0");
    let html = `<div class="cal-day-num">${numFormatado} <small style="font-size:0.7rem; font-weight:600; opacity:0.65;">(${DIAS_SEMANA_CURTO[diaSemana]})</small></div>`;

    if (registro && Array.isArray(registro.itens) && registro.itens.length > 0) {
      celula.dataset.itens = registro.itens.map(i => i.toUpperCase()).join("|");
      html += `<ul class="cal-day-items">`;
      registro.itens.forEach(item => {
        const nomeUpper = item.trim().toUpperCase();
        html += `<li>${item}</li>`;
        if (registro.tipo === "medicacao" && !TERMOS_NAO_MEDICAMENTOS.includes(nomeUpper)) {
          medicamentosUnicos.add(nomeUpper);
        }
      });
      html += `</ul>`;
    }

    celula.innerHTML = html;
    grid.appendChild(celula);
  }

  const listaOrdenada = Array.from(medicamentosUnicos).sort();
  popularFiltroMedicamentos(listaOrdenada);
  renderizarRolCrem(listaOrdenada, judicializadas || ["OCRELIZUMABE"]);
}

function popularFiltroMedicamentos(lista) {
  const select = document.getElementById("filtroMedicamento");
  select.innerHTML = `<option value="">Todos os medicamentos</option>`;
  lista.forEach(med => {
    const opt = document.createElement("option");
    opt.value = med;
    opt.textContent = med;
    select.appendChild(opt);
  });

  select.addEventListener("change", (e) => {
    const termo = e.target.value;
    document.querySelectorAll(".cal-day:not(.is-empty)").forEach(card => {
      card.classList.remove("is-dimmed", "is-highlighted");
      if (!termo) return;

      const itens = (card.dataset.itens || "").split("|");
      if (itens.includes(termo)) {
        card.classList.add("is-highlighted");
      } else {
        card.classList.add("is-dimmed");
      }
    });
  });
}

function renderizarRolCrem(listaRegular, listaJudicial) {
  const ulRegular = document.getElementById("listaRolRegular");
  const ulJudicial = document.getElementById("listaRolJudicial");

  ulRegular.innerHTML = listaRegular
    .map(med => `<li>${med}</li>`)
    .join("");

  ulJudicial.innerHTML = listaJudicial
    .map(med => `<li>${med.toUpperCase()}</li>`)
    .join("");
}

document.addEventListener("DOMContentLoaded", carregarCalendario);