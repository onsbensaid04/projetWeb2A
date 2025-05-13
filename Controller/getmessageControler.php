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

// Handle actions from GET parameters
if ($_GET['action'] === 'fetchMessages') {
    if (!isset($_GET['channel_id'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing channel_id'
        ]);
        exit;
    }

    $channel_id = intval($_GET['channel_id']); // Cast to int for safety

    $result = $messageController->getMessagesByChannel($channel_id);

    if ($result['success']) {
        echo json_encode([
            'status' => 'success',
            'messages' => $result['messages']
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