
  <!-- RODAPÉ -->
  <footer class="footer" id="contato">
    <div class="container">
      <div class="footer-top">
        <div class="footer-brand">
          <div class="logo-text-group">
            <img src="assets/img/logo_apbem.svg" alt="Logo da Apbem" class="logo-img">
          </div>

          <div class="footer-cnpj">
            Associação Paraibana de Esclerose Múltipla<br>
            CNPJ: 05.592.284/0001-76<br>
            Rua Estudante Marizete da Silva Nascimento, 151<br>
            Mangabeira VII, João Pessoa, Paraíba
          </div>
        </div>

        <div class="footer-col">
          <h4>Instituição</h4>
          <ul>
            <li><a href="#sobre-nos">Sobre Nós</a></li>
            <li><a href="#nosso-time">Nosso Time</a></li>
            <li><a href="#nossa-missao">Nossa Missão</a></li>
            <li><a href="#contato">Contato</a></li>
          </ul>
        </div>

        <div class="footer-col">
          <h4>Eventos</h4>
          <ul>
            <li><a href="#caravana">Caravana E.M.</a></li>
            <li><a href="#campanhas">Campanhas</a></li>
            <li><a href="#voluntariado">Voluntariado</a></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        <p>2026 &copy; Apbem | Desenvolvido com Amor</p>
        <div class="footer-legal">
          <a href="#termos">Termos de Uso</a>
          <a href="#privacidade">Política de Privacidade</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- SCRIPTS OBRIGATÓRIOS -->  
<!-- SCRIPTS OBRIGATÓRIOS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script src="assets/js/funcoes.js" defer></script>
  <script src="assets/data/calendario.js?v=<?php echo time(); ?>"></script>

  <?php if (basename($_SERVER['PHP_SELF']) === 'admin-calendario.php'): ?>
    <script src="assets/js/gerarcalendario.js?v=2" defer></script>
  <?php else: ?>
    <script src="assets/js/calendario.js" defer></script>
  <?php endif; ?>
  
</body>
</html>