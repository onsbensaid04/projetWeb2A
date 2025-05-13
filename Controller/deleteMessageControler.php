<?php

require_once '../model/Message.php';
require_once 'messageControler.php';

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing message ID.'
    ]);
    exit;
}

$id = intval($_GET['id']);

$controller = new MessageController();
$result = $controller->deleteMessage($id);

echo json_encode($result);

