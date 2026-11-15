    // Menu Mobile
    const menuToggle = document.getElementById('menuToggle');
    const navActions = document.getElementById('navActions');

    menuToggle.addEventListener('click', () => {
      const isOpen = navActions.classList.toggle('active');
      menuToggle.setAttribute('aria-expanded', isOpen);
    });
const PIX_APBEM = {
    chave: "05592284000176",
    nome: " APBEM ",
    cidade: "JOAO PESSOA",
    txid: "DOACAOAPBEM"
  };

  let qrCodeInstance = null;

  function formatarTLV(id, valor) {
    const tam = String(valor.length).padStart(2, "0");
    return `${id}${tam}${valor}`;
  }

  function calcularCRC16(payload) {
    let crc = 0xFFFF;
    for (let i = 0; i < payload.length; i++) {
      crc ^= payload.charCodeAt(i) << 8;
      for (let j = 0; j < 8; j++) {
        crc = (crc & 0x8000) !== 0 ? ((crc << 1) ^ 0x1021) & 0xFFFF : (crc << 1) & 0xFFFF;
      }
    }
    return crc.toString(16).toUpperCase().padStart(4, "0");
  }

  function gerarPayloadBRCode(valorNumerico) {
    const gui = formatarTLV("00", "br.gov.bcb.pix");
    const chave = formatarTLV("01", PIX_APBEM.chave);
    const merchantAccount = formatarTLV("26", gui + chave);

    const campos = [
      formatarTLV("00", "01"),
      merchantAccount,
      formatarTLV("52", "0000"),
      formatarTLV("53", "986"),
      valorNumerico > 0 ? formatarTLV("54", valorNumerico.toFixed(2)) : "",
      formatarTLV("58", "BR"),
      formatarTLV("59", PIX_APBEM.nome),
      formatarTLV("60", PIX_APBEM.cidade),
      formatarTLV("62", formatarTLV("05", PIX_APBEM.txid)),
      "6304"
    ].join("");

    return campos + calcularCRC16(campos);
  }

  function atualizarPixModal() {
    const inputValor = document.getElementById("valorDoacao");
    const valor = parseFloat(inputValor.value);
    const valorValido = !isNaN(valor) && valor > 0 ? valor : 0;

    const payload = gerarPayloadBRCode(valorValido);
    document.getElementById("payloadPixCopiaECola").value = payload;

    const container = document.getElementById("qrcodePixContainer");
    if (!qrCodeInstance) {
      qrCodeInstance = new QRCode(container, {
        text: payload,
        width: 180,
        height: 180,
        colorDark: "#0F172A",
        colorLight: "#FFFFFF",
        correctLevel: QRCode.CorrectLevel.M
      });
    } else {
      qrCodeInstance.clear();
      qrCodeInstance.makeCode(payload);
    }
  }

  function selecionarValor(valor) {
    const input = document.getElementById("valorDoacao");
    input.value = valor;
    atualizarPixModal();
    if (valor === "") {
      input.focus();
    }
  }

  function abrirModalDoacao() {
    const modal = document.getElementById("modalDoacao");
    modal.style.display = "flex";
    atualizarPixModal();
  }

  function fecharModalDoacao() {
    document.getElementById("modalDoacao").style.display = "none";
    document.getElementById("feedbackCopia").style.display = "none";
  }

  function copiarPixCopiaECola() {
    const codigo = document.getElementById("payloadPixCopiaECola").value;
    navigator.clipboard.writeText(codigo).then(() => {
      const feedback = document.getElementById("feedbackCopia");
      feedback.style.display = "block";
      setTimeout(() => { feedback.style.display = "none"; }, 4000);
    });
  }

  document.getElementById("modalDoacao").addEventListener("click", function(e) {
    if (e.target === this) fecharModalDoacao();
  });