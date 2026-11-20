<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['admin_autenticado']) || $_SESSION['admin_autenticado'] !== true) {
    http_response_code(403);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado.']);
    exit;
}

if (!isset($_FILES['banner']) || $_FILES['banner']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Nenhuma imagem válida foi enviada.']);
    exit;
}

$arquivo = $_FILES['banner'];

if ($arquivo['size'] > 5 * 1024 * 1024) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'A imagem deve ter no máximo 5 MB.']);
    exit;
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($arquivo['tmp_name']);
$extensoes = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp'
];

if (!isset($extensoes[$mime])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Formato inválido. Use JPG, PNG ou WEBP.']);
    exit;
}

$pastaUploads = __DIR__ . '/uploads';
if (!is_dir($pastaUploads)) {
    mkdir($pastaUploads, 0755, true);
}

$nomeArquivo = 'banner-' . date('Ymd-His') . '-' . bin2hex(random_bytes(3)) . '.' . $extensoes[$mime];
$destino = $pastaUploads . '/' . $nomeArquivo;

if (!move_uploaded_file($arquivo['tmp_name'], $destino)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao salvar imagem em /uploads.']);
    exit;
}

echo json_encode([
    'sucesso' => true,
    'caminho' => 'uploads/' . $nomeArquivo
]);