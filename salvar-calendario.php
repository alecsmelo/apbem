<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (empty($_SESSION['admin_autenticado']) || $_SESSION['admin_autenticado'] !== true) {
    http_response_code(403);
    echo json_encode(['sucesso' => false, 'erro' => 'Acesso negado.']);
    exit;
}

$dadosBrutos = file_get_contents('php://input');
$requisicao = json_decode($dadosBrutos, true);

if (json_last_error() !== JSON_ERROR_NONE || empty($requisicao)) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'JSON inválido.']);
    exit;
}

$acao = $requisicao['acao'] ?? 'salvar';
$calendario = $requisicao['calendario'] ?? $requisicao;

$ano = intval($calendario['ano'] ?? 0);
$mes = intval($calendario['mes'] ?? 0);

if ($ano < 2000 || $mes < 1 || $mes > 12) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'erro' => 'Mês ou ano inválido.']);
    exit;
}

$diretorio = __DIR__ . '/assets/data';
if (!is_dir($diretorio)) {
    mkdir($diretorio, 0755, true);
}

$chave = sprintf('%04d-%02d', $ano, $mes);
$nomeArquivoIndividual = "calendario-{$chave}.json";
$caminhoArquivoIndividual = $diretorio . '/' . $nomeArquivoIndividual;

// 1. Grava o JSON individual completo deste calendário (com todos os dias, banner e medicações)
$jsonIndividual = json_encode($calendario, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
if (file_put_contents($caminhoArquivoIndividual, $jsonIndividual) === false) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'erro' => "Falha ao gravar {$nomeArquivoIndividual}."]);
    exit;
}

// 2. Atualiza o índice lista-calendarios.json
$arquivoIndice = $diretorio . '/lista-calendarios.json';
$indice = [
    'total' => 0,
    'publicado' => null,
    'calendarios' => []
];

if (is_file($arquivoIndice)) {
    $lido = json_decode(file_get_contents($arquivoIndice), true);
    if (is_array($lido) && isset($lido['calendarios'])) {
        $indice = $lido;
    }
}

// Mantém o status de publicado se já era o ativo, ou atualiza se a ação agora for 'publicar'
$publicadoAnterior = $indice['publicado'] ?? null;
if ($acao === 'publicar' || empty($publicadoAnterior)) {
    $indice['publicado'] = $chave;
}

$indice['calendarios'][$chave] = [
    'chave' => $chave,
    'ano' => $ano,
    'mes' => $mes,
    'arquivo' => "assets/data/{$nomeArquivoIndividual}",
    'bannerCampanha' => $calendario['bannerCampanha'] ?? '',
    'atualizadoEm' => date('Y-m-d H:i:s')
];

ksort($indice['calendarios']);
$indice['total'] = count($indice['calendarios']);

$jsonIndice = json_encode($indice, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
if (file_put_contents($arquivoIndice, $jsonIndice) === false) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'erro' => 'Falha ao atualizar lista-calendarios.json.']);
    exit;
}

// 3. Se a ação for 'publicar' (ou ainda não houver calendário publicado), publica em calendario.json e calendario.js
$arquivoPublicoJson = $diretorio . '/calendario.json';
$arquivoPublicoJs = $diretorio . '/calendario.js';

if ($acao === 'publicar' || !is_file($arquivoPublicoJson)) {
    $salvouJson = file_put_contents($arquivoPublicoJson, $jsonIndividual);
    $salvouJs = file_put_contents($arquivoPublicoJs, "window.DADOS_CALENDARIO = " . $jsonIndividual . ";\n");

    if ($salvouJson === false || $salvouJs === false) {
        http_response_code(500);
        echo json_encode(['sucesso' => false, 'erro' => 'Salvo no índice, mas falhou ao publicar.']);
        exit;
    }
}

echo json_encode([
    'sucesso' => true,
    'acao' => $acao,
    'chave' => $chave,
    'arquivo' => $nomeArquivoIndividual,
    'total' => $indice['total']
]);