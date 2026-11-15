<?php
header('Content-Type: application/json; charset=utf-8');

$dadosBrutos = file_get_contents('php://input');
$dadosDecodificados = json_decode($dadosBrutos, true);

if (json_last_error() !== JSON_ERROR_NONE || empty($dadosDecodificados)) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'JSON inválido.']);
    exit;
}

$diretorio = __DIR__ . '/assets/data';
if (!is_dir($diretorio)) {
    mkdir($diretorio, 0755, true);
}

$jsonFormatado = json_encode($dadosDecodificados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

$salvouJson = file_put_contents($diretorio . '/calendario.json', $jsonFormatado);
$salvouJs = file_put_contents($diretorio . '/calendario.js', "window.DADOS_CALENDARIO = " . $jsonFormatado . ";\n");

if ($salvouJson !== false && $salvouJs !== false) {
    echo json_encode(['sucesso' => true]);
} else {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'erro' => 'Falha ao gravar em assets/data/.']);
}