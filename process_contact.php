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
$message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

// Valida os dados
if (!$name || !$email || !$message || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Dados inválidos']);
    exit;
}

// Prepara os dados para salvar
$data = [
    'name' => $name,
    'email' => $email,
    'message' => $message,
    'date' => date('Y-m-d H:i:s')
];

// Caminho do arquivo JSON
$file = __DIR__ . '/data/contact_messages.json';

// Lê os dados existentes
$messages = [];
if (file_exists($file)) {
    $messages = json_decode(file_get_contents($file), true) ?? [];
}

// Adiciona a nova mensagem
$messages[] = $data;

// Salva os dados
if (file_put_contents($file, json_encode($messages, JSON_PRETTY_PRINT))) {
    echo json_encode(['success' => true, 'message' => 'Mensagem enviada com sucesso!']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao salvar os dados']);
} 