<?php
session_start();

if (empty($_SESSION['admin_autenticado']) || $_SESSION['admin_autenticado'] !== true) {
    header('Location: login.php');
    exit;
}
?>
<?php
include 'assets/php/topo.php';
?>

  <!-- CONTEÚDO PRINCIPAL DO GERENCIADOR -->
  <main class="admin-page">
    <div class="container">

      <div class="admin-header-bar">
        <div>
          <span class="hero-eyebrow">Área Administrativa</span>
          <h1 class="admin-page-title">Gerenciador do Calendário de Medicações</h1>
          <p class="admin-subtitle">
            Preencha o cronograma mensal e clique em <strong id="textoInstrucaoBotao">Atualizar Dados</strong> para publicar imediatamente no calendário.
          </p>
        </div>

        <a href="calendario.html" class="pill-btn pill-azul">Visualizar Calendário</a>
      </div>

      <!-- PAINEL DE CONFIGURAÇÕES GERAIS -->
      <section class="admin-card" aria-label="Configurações gerais do mês">
        <div class="admin-row">
          <div class="admin-field">
            <label for="mes">Mês de Referência</label>
            <select id="mes" onchange="trocarMesOuAno()">
              <option value="1">Janeiro</option>
              <option value="2">Fevereiro</option>
              <option value="3">Março</option>
              <option value="4">Abril</option>
              <option value="5">Maio</option>
              <option value="6">Junho</option>
              <option value="7">Julho</option>
              <option value="8">Agosto</option>
              <option value="9" selected>Setembro</option>
              <option value="10">Outubro</option>
              <option value="11">Novembro</option>
              <option value="12">Dezembro</option>
            </select>
          </div>

          <div class="admin-field">
            <label for="ano">Ano</label>
            <input type="number" id="ano" value="2026" min="2024" max="2099" onchange="trocarMesOuAno()">
          </div>

          <div class="admin-field">
            <label for="bannerCampanha">Caminho do Banner (opcional)</label>
            <input type="text" id="bannerCampanha" placeholder="Ex: assets/img/banner-campanha.jpg">
          </div>

          <div class="admin-field">
            <label for="bannerAlt">Descrição do Banner (Acessibilidade)</label>
            <input type="text" id="bannerAlt" placeholder="Ex: Campanha Setembro Amarelo">
          </div>
        </div>

        <div class="admin-row">
          <div class="admin-field">
            <label for="horarioDispensacao">Horário de Dispensação</label>
            <input type="text" id="horarioDispensacao" value="08h às 11:15h">
          </div>

          <div class="admin-field">
            <label for="observacaoExtra">Aviso Destaque no Rodapé</label>
            <input type="text" id="observacaoExtra" placeholder="Ex: BALANÇO Dia 24">
          </div>

          <div class="admin-field">
            <label for="judicializadas">Medicações Judicializadas (separadas por vírgula)</label>
            <input type="text" id="judicializadas" value="OCRELIZUMABE">
          </div>
        </div>

        <div class="admin-actions">
          <button type="button" class="btn-admin-novo" onclick="novoCalendario()">
            + Novo Calendário
          </button>
          <button type="button" id="btnAtualizarDados" class="btn-admin-salvar" onclick="atualizarDadosInternamente()">
            Atualizar Dados
          </button>
          <button type="button" class="btn-admin-sair" onclick="window.location.href='assets/php/logout.php'">
            Sair
          </button>
        </div>
        <p id="statusSalvamento" style="display:none; margin:14px 0 0; font-weight:700; font-size:0.95rem;"></p>
      </section>

      <!-- GRADE DE EDIÇÃO DOS DIAS DO MÊS -->
      <section aria-label="Edição diária de medicações e avisos">
        <div class="dias-grid" id="diasContainer"></div>
      </section>

    </div>
  </main>

  <?php
    include 'assets/php/modal.php';
    include 'assets/php/footer.php';
  ?>
