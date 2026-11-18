<?php
session_start();
require_once 'assets/php/conexao.php';

// Redireciona ANTES de incluir o topo.php (evita erro de "headers already sent")
if (!empty($_SESSION['admin_autenticado']) && $_SESSION['admin_autenticado'] === true) {
    header('Location: admin-calendario.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha   = $_POST['senha'] ?? '';

    if ($usuario !== '' && $senha !== '') {
        $stmt = $pdo->prepare('SELECT id, usuario, senha FROM admin_usuarios WHERE usuario = :usuario LIMIT 1');
        $stmt->execute([':usuario' => $usuario]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($senha, $admin['senha'])) {
            session_regenerate_id(true);
            $_SESSION['admin_autenticado'] = true;
            $_SESSION['admin_id']          = $admin['id'];
            $_SESSION['admin_user']        = $admin['usuario'];

            header('Location: admin-calendario.php');
            exit;
        }
    }

    sleep(1);
    $erro = 'Usuário ou senha inválidos.';

}

// Carrega o cabeçalho padrão do site

?>
<html lang="pt-BR">
<head>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300..700&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<style>
    body {
        font-family: "Roboto", sans-serif;
        font-optical-sizing: auto;
        font-weight: 400;
        font-style: normal;
        font-variation-settings:
            "wdth" 100;
    }

    /* Estilos isolados apenas para a área de login, sem afetar body, topo ou footer */
    .login-section {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 4rem 1rem;
        min-height: 60vh;
    }

    .login-card {
        background: #ffffff;
        padding: 2.25rem 2rem;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        width: 100%;
        max-width: 380px;
        border-top: 4px solid #e67e22;
    }

    .login-card h2 {
        margin: 0 0 1.5rem;
        font-size: 1.4rem;
        color: #1e293b;
        text-align: center;
        font-weight: 700;
    }

    .login-card .form-group {
        margin-bottom: 1.1rem;
        text-align: left;
    }

    .login-card label {
        display: block;
        margin-bottom: 0.4rem;
        font-size: 0.9rem;
        font-weight: 600;
        color: #475569;
    }

    .login-card input {
        width: 100%;
        padding: 0.7rem 0.85rem;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 1rem;
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .login-card input:focus {
        outline: none;
        border-color: #e67e22;
        box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.18);
    }

    .login-card .btn-entrar {
        width: 100%;
        padding: 0.8rem;
        background-color: #e67e22;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        margin-top: 0.5rem;
        transition: background-color 0.2s;
    }

    .login-card .btn-entrar:hover {
        background-color: #d35400;
    }

    .login-card .alerta-erro {
        background-color: #fee2e2;
        color: #b91c1c;
        padding: 0.75rem;
        border-radius: 8px;
        font-size: 0.875rem;
        margin-bottom: 1.2rem;
        text-align: center;
        border: 1px solid #fecaca;
    }
</style>

<main class="login-section">
    <div class="login-card">
        <h2>Administração</h2>

        <?php if ($erro): ?>
            <div class="alerta-erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <div class="form-group">
                <label for="usuario">Usuário</label>
                <input type="text" id="usuario" name="usuario" value="root" required autofocus>
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
            </div>

            <button type="submit" class="btn-entrar">Entrar</button>
        </form>
    </div>
</main>

