<?php
require_once '../model/message.php';
require_once 'messageControler.php';

header('Content-Type: application/json ,charset=utf-8');

if (!isset($_GET['action'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing action'
    ]);
    exit;
}


$messageController = new MessageController();


if ($_GET['action'] === 'sendMessage') {
    if (!isset($_GET['channel_id']) || !isset($_GET['user_id']) || !isset($_GET['content'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing required parameters (channel_id, user_id, or content)'
        ]);
        exit;
    }

    $channel_id = intval($_GET['channel_id']);
    $user_id = intval($_GET['user_id']);
    $content = $_GET['content'];

    $result = $messageController->createMessage($channel_id, $user_id, $content);

    if ($result['success']) {
        echo json_encode([
            'status' => 'success',
            'message' => $result['message'],
            'message_id' => $result['id']
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => $result['message']
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unknown action'
    ]);
}
?>