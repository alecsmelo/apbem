
<!-- Modal de Doação Pix -->
<div id="modalDoacao" role="dialog" aria-modal="true" aria-labelledby="modalDoacaoTitulo"
  style="display:none; position:fixed; inset:0; background:rgba(15,23,42,0.7); z-index:999; align-items:center; justify-content:center; padding:20px; overflow-y:auto;">
  <div style="background:#fff; max-width:440px; width:100%; border-radius:24px; padding:28px 32px; position:relative; text-align:center; margin:auto;">
    <button type="button" onclick="fecharModalDoacao()" aria-label="Fechar janela de doação"
      style="position:absolute; top:16px; right:20px; border:none; background:none; font-size:1.5rem; cursor:pointer; color:#64748B;">&times;</button>
    
    <span style="color:#EB8C28; font-weight:700; font-size:0.85rem; text-transform:uppercase;">Apoie a APBEM</span>
    <h2 id="modalDoacaoTitulo" style="font-size:1.5rem; margin:6px 0 10px;">Faça sua Doação via Pix</h2>
    <p style="font-size:0.9rem; color:#475569; margin-bottom:16px;">
      100% do valor é destinado aos projetos de acolhimento e defesa dos nossos direitos na Paraíba.
    </p>

    <!-- Seleção Rápida de Valores (10, 25, 50 e Livre) -->
    <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:8px; margin-bottom:12px;">
      <button type="button" onclick="selecionarValor(10)" class="pill-btn pill-laranja" style="border:none; cursor:pointer; padding:10px 6px;">R$ 10</button>
      <button type="button" onclick="selecionarValor(25)" class="pill-btn pill-laranja" style="border:none; cursor:pointer; padding:10px 6px;">R$ 25</button>
      <button type="button" onclick="selecionarValor(50)" class="pill-btn pill-laranja" style="border:none; cursor:pointer; padding:10px 6px;">R$ 50</button>
      <button type="button" onclick="selecionarValor('')" class="pill-btn pill-laranja" style="border:none; cursor:pointer; padding:10px 6px;">Livre</button>
    </div>

    <input type="number" id="valorDoacao" placeholder="Outro valor (R$) ou deixe em branco" min="1" step="0.01"
      oninput="atualizarPixModal()"
      style="width:100%; box-sizing:border-box; padding:12px 16px; border:2px solid #E2E8F0; border-radius:12px; font-size:1rem; margin-bottom:16px;">

    <!-- Área do QR Code + Chave CNPJ -->
    <div style="background:#F8FAFC; padding:16px; border-radius:16px; border:1px dashed #CBD5E1; margin-bottom:16px;">
      <div id="qrcodePixContainer" style="display:flex; justify-content:center; align-items:center; background:#fff; padding:12px; border-radius:12px; width:fit-content; margin:0 auto 12px; border:1px solid #E2E8F0;"></div>
      
      <p style="font-size:0.8rem; color:#64748B; margin:0 0 4px;">Ou use a Chave Pix (CNPJ):</p>
      <code id="chavePixTexto" style="font-size:1rem; font-weight:700; color:#2578D9;">05.592.284/0001-76</code>
      
      <input type="hidden" id="payloadPixCopiaECola">
    </div>

    <button type="button" onclick="copiarPixCopiaECola()" class="pill-btn pill-azul" style="width:100%; border:none; cursor:pointer; padding:14px;">
      Copiar Código Pix (Copia e Cola)
    </button>
    <p id="feedbackCopia" style="font-size:0.85rem; color:#16A34A; font-weight:600; margin-top:10px; display:none;">
      Código Pix copiado!
    </p>
  </div>
</div>
