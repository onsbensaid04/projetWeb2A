<?php

$data = json_decode(file_get_contents('php://input'), true);


if (!$data || !isset($data['type'], $data['user_id'], $data['channel_id'], $data['content'], $data['timestamp'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit;
}


$logFilePath = __DIR__ . '/logs/messages.txt';

$logMessage = "[" . $data['timestamp'] . "] " .
    "User: " . $data['user_id'] . " | " .
    "Channel: " . $data['channel_id'] . " | " .
    "Content: " . str_replace(["\n", "\r"], ['\\n', ''], $data['content']) . "\n";


file_put_contents($logFilePath, $logMessage, FILE_APPEND);

echo json_encode(['status' => 'logged']);
?>
