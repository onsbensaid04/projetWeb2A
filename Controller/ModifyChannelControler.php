<?php
ob_start();
require_once '../model/ModelChannel.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fallback-safe variable handling
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $created_by = isset($_POST['created_by']) ? $_POST['created_by'] : '';
    $image_url = '';  // Default empty image_url

    // Validate required fields
    if (empty($name) || empty($description)) {
        echo "Name and description are required!";
        exit;
    }

    // Handle image upload (if any)
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create upload directory if it doesn't exist
        }

        $fileName = basename($_FILES['image_url']['name']);
        $targetPath = $uploadDir . $fileName;

        // Validate image file type (only allow images)
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['image_url']['type'], $allowedTypes)) {
            echo "Invalid image file type!";
            exit;
        }

        // Move the uploaded image to the target directory
        if (move_uploaded_file($_FILES['image_url']['tmp_name'], $targetPath)) {
            $image_url = $targetPath;
        } else {
            echo "Error uploading the image.";
            exit;
        }
    }

    // Update the channel
    $channel = new Channel();
    $channel->setId($id);
    $channel->setName($name);
    $channel->setDescription($description);
    $channel->setImageUrl($image_url); // Set the image URL (empty if no image uploaded)
    $channel->setCreatedBy($created_by);

    // Call the update function
    if ($channel->updateChannel()) {
        // If successful, redirect with a success message
        header("Location: ../Vue/Back/Back/ChannelBackOffice.html?status=success");
        exit;
    } else {
        // If update fails, redirect with an error message
        header("Location: ../Vue/Back/Back/ChannelBackOffice.html?status=error");
        exit;
    }
}

ob_end_flush();
?>
