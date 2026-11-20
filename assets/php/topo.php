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
  <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
  
  
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
    </div>