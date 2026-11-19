<?php
$host    = 'localhost:3306';
$db      = 'apbemorgbr_apbem';
$user    = 'admin';
$pass    = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log("Erro de conexão MySQL: " . $e->getMessage());
    die("Erro interno ao conectar ao banco de dados." . $e->getMessage());
}