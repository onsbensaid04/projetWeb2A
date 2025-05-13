<?php
require_once '../model/ModelChannel.php';

require_once __DIR__ . '/../model/ModelChannel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $created_by = isset($_POST['created_by']) ? $_POST['created_by'] : '';
    $image_url = '';

    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($_FILES['image_url']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image_url']['tmp_name'], $targetPath)) {
            $image_url = $targetPath;
        }
    }


    $channel = new Channel();
    $channel->setName($name);
    $channel->setDescription($description);
    $channel->setImageUrl($image_url);
    $channel->setCreatedBy($created_by);


    if ($channel->ajoutChannel()) {
        header("Location: ../view/Front/Front/channels.html?success=1");
        exit;
    } else {
        header("Location: ../view/Back/Back/creation-channels.html?error=1");
        exit;
    }
}
?>
