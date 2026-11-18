<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Apbem — Associação Paraibana de Esclerose Múltipla</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300..700&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/estilo.css">
  <link rel="stylesheet" href="assets/css/calendario.css">
  <link rel="stylesheet" href="assets/css/estiloadmincalendario.css">

</head>
<body>

  <!-- BLOCO SUPERIOR COM DEGRADÊ -->
  <div class="top-wrapper">
    <!-- Cabeçalho -->
    <header class="header">
      <div class="container header-inner">
        <a href="index.php" class="logo" aria-label="Página inicial Apbem">
          <div class="logo-group">
            <img src="assets/img/logo_apbem.svg" alt="Logo da Apbem" class="logo-img">
          </div>
        </a>

        <button class="menu-toggle" id="menuToggle" aria-label="Abrir menu" aria-expanded="false">&#9776;</button>

        <div class="nav-actions" id="navActions">
          <nav>
            <ul class="nav-list">
              <li><a href="index.php">Início</a></li>
              <li><a href="index.php?quem-somos">Quem Somos</a></li>
              <li><a href="index.php?eventos">Eventos</a></li>
              <li><a href="calendario.php">Calendário</a></li>
              <li><a href="index.php?contato">Contato</a></li>
              <li><a href="login.php">Entrar</a></li>
            </ul>
          </nav>
          <a class="btn-doar" onclick="abrirModalDoacao(event)">DOAR</a>
        </div>
      </div>
    </header>

    <!-- Jumbotron / Hero -->
    <section class="hero" id="inicio">
      <div class="container hero-grid">
        <!-- Coluna Esquerda -->
        <div class="hero-left">
          <span class="hero-eyebrow">Associação Paraibana de Esclerose Múltipla</span>
          <h1 class="hero-title">Juntos somos<br>mais Fortes</h1>
          <p class="hero-text">
            Superando os desafios da Esclerose Múltipla com união e transparência. Trabalhamos todos os dias para romper barreiras no diagnóstico, no acesso a tratamentos e na inclusão social em toda a Paraíba.
          </p>

          <div class="hero-buttons">
            <a href="#doar" class="pill-btn pill-laranja" onclick="abrirModalDoacao(event)">Saiba como Ajudar</a>
            <a href="#quem-somos" class="pill-btn pill-azul">Conheça a Apbem</a>
          </div>

          <div class="social-proof">
            <div class="avatar-stack">
              <img src="assets/img/avatar1.jpg" alt="Membro Apbem 1">
              <img src="assets/img/avatar2.jpg" alt="Membro Apbem 2">
              <img src="assets/img/avatar3.jpg" alt="Membro Apbem 3">
            </div>
            <div class="social-proof-text">
              <strong>100+</strong>
              <span>Pessoas envolvidas</span>
            </div>
          </div>
        </div>

        <!-- Coluna Direita -->
        <div class="hero-right">
          <img src="assets/img/foto_hero.png" alt="Integrantes da Apbem vestindo camisa Abrace a causa da Esclerose Múltipla" class="hero-photo">

          <article class="blue-event-card" id="eventos">
            <img src="assets/img/caravana-em.png" alt="Participação na Caravana E.M.">
            <div>
              <h3>Impacto na vida dos Paraibanos com Esclerose Múltipla</h3>
              <p>Participação na Caravana E.M., realizada pelo CREM</p>
            </div>
          </article>
        </div>
      </div>
    </section>
  </div>