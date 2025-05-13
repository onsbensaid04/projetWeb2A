<?php
require_once '../model/message.php';
require_once 'messageControler.php';

header('Content-Type: application/json');

if (!isset($_GET['id']) || !isset($_GET['content'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing parameters.'
    ]);
    exit;
}

$id = intval($_GET['id']);
$newContent = trim($_GET['content']);

$controller = new MessageController();
$result = $controller->updateMessage($id, $newContent);

echo json_encode($result);
?>
<?php
