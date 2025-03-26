<?php
header('Content-Type: application/json');

// Verifica se é uma requisição POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

// Recebe os dados do formulário
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

// Valida os dados
if (!$name || !$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Dados inválidos']);
    exit;
}

// Prepara os dados para salvar
$data = [
    'name' => $name,
    'email' => $email,
    'date' => date('Y-m-d H:i:s')
];

// Caminho do arquivo JSON
$file = __DIR__ . '/data/optin_subscribers.json';

// Lê os dados existentes
$subscribers = [];
if (file_exists($file)) {
    $subscribers = json_decode(file_get_contents($file), true) ?? [];
}

// Adiciona o novo inscrito
$subscribers[] = $data;

// Salva os dados
if (file_put_contents($file, json_encode($subscribers, JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true, 'message' => 'Inscrição realizada com sucesso!']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao salvar os dados']);
} 