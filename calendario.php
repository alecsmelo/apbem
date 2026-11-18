<?php
  include 'assets/php/topo.php';
?>

  <!-- CONTEÚDO PRINCIPAL DO CALENDÁRIO -->
  <main class="calendario-page">
    <div class="container">
      
      <!-- Barra de Ferramentas e Filtro -->
      <div class="cal-toolbar">
        <div>
          <span class="hero-eyebrow">Dispensação Farmacêutica</span>
          <h1 class="cal-page-title">Calendário de Medicações</h1>
        </div>

        <div class="cal-filtros">
          <label for="filtroMedicamento" class="sr-only">Filtrar medicamento</label>
          <select id="filtroMedicamento" class="cal-select">
            <option value="">Todos os medicamentos</option>
          </select>
          <button type="button" onclick="window.print()" class="pill-btn pill-azul" style="border:none; cursor:pointer;">
            Imprimir / Salvar PDF
          </button>
        </div>
      </div>

      <!-- ESPAÇO PARA BANNER DE CAMPANHA (EXIBIDO APENAS QUANDO HOUVER IMAGEM CONFIGURADA) -->
      <div id="calBannerWrapper" class="cal-banner-wrapper" style="display:none;">
        <img id="calBannerImg" src="" alt="Banner da Campanha Mensal">
      </div>

      <!-- QUADRO DO CALENDÁRIO -->
      <section class="cal-board" id="calBoard" aria-label="Grade mensal de entrega de medicações">
        <header class="cal-board-header">
          <div class="cal-board-label">
            <span>CALENDÁRIO</span>
            <strong>MEDICAÇÕES</strong>
          </div>
          <div class="cal-board-mes">
            <h2 id="calNomeMes">CARREGANDO...</h2>
          </div>
        </header>

        <!-- Cabeçalho dos Dias da Semana -->
        <div class="cal-weekdays">
          <div class="cal-weekday is-weekend">DOMINGO</div>
          <div class="cal-weekday">SEGUNDA</div>
          <div class="cal-weekday">TERÇA</div>
          <div class="cal-weekday">QUARTA</div>
          <div class="cal-weekday">QUINTA</div>
          <div class="cal-weekday">SEXTA</div>
          <div class="cal-weekday is-weekend">SÁBADO</div>
        </div>

        <!-- Grade Dinâmica dos Dias -->
        <div class="cal-grid" id="calGrid"></div>

        <!-- Rodapé Informativo do Quadro -->
        <footer class="cal-board-footer">
          <div class="cal-info-box">
            <strong>HORÁRIO DE DISPENSAÇÃO</strong>
            <span id="calHorario">08h às 11:15h</span>
          </div>
          <div class="cal-info-box">
            <strong>ATENÇÃO</strong>
            <span>NÃO HAVERÁ DISPENSAÇÃO NOS DIAS EM VERMELHO OU FINAIS DE SEMANA</span>
          </div>
          <div class="cal-info-box cal-info-destaque">
            <span id="calObsExtra">BALANÇO</span>
          </div>
        </footer>
      </section>

      <!-- INFORMATIVO DE RESPONSABILIDADE E ROL DE MEDICAÇÕES DO CREM -->
      <section class="crem-info-card" aria-labelledby="cremTitulo">
        <div class="crem-info-header">
          <span class="crem-badge">Informação Importante aos Pacientes</span>
          <h2 id="cremTitulo">Responsabilidade pela Dispensação</h2>
          <p>
            A gestão, agendamento e dispensação das medicações listadas neste calendário são de responsabilidade exclusiva do
            <strong>CREM — Centro de Referência de Esclerose Múltipla da Paraíba</strong>.
          </p>
        </div>

        <div class="crem-rol-grid">
          <!-- Coluna 1: Rol Regular -->
          <div class="crem-rol-box">
            <h3>Rol de Medicações Dispensadas no CREM</h3>
            <p class="crem-rol-desc">Medicações contempladas regularmente no cronograma mensal:</p>
            <ul id="listaRolRegular" class="crem-med-list"></ul>
          </div>

          <!-- Coluna 2: Medicações Judicializadas -->
          <div class="crem-rol-box is-judicial">
            <h3>Dispensação Exclusiva via Judicialização</h3>
            <p class="crem-rol-desc">Medicações que não integram o rol regular e são fornecidas somente mediante determinação judicial:</p>
            <ul id="listaRolJudicial" class="crem-med-list"></ul>
          </div>
        </div>
      </section>

    </div>
  </main>


<?php
  include 'assets/php/modal.php';
  include 'assets/php/footer.php';
?>


</body>
</html>