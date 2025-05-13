<?php
require_once 'ModelChannel.php';
require_once 'message.php';

class Config
{
    private static $pdo = null;

    public static function getConnexion()
    {
        if (self::$pdo === null) {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "DBforum";

            try {
                self::$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC




                ]);
            } catch (PDOException $e) {
                die('Connection failed: ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    if (isset($_POST['delete_id'])) {
        $idToDelete = $_POST['delete_id'];
        Channel::supprimerChannel($idToDelete);
        echo json_encode(['status' => 'deleted']);
    } elseif (isset($_POST['id'])) {
        $channel = new Channel();
        $channel->setId($_POST['id']);
        $channel->setName($_POST['name']);
        $channel->setDescription($_POST['description']);
        $image_url = $_POST['image_url'] ?? '';
        $created_by = $_POST['created_by'] ?? '';

        $channel->updateChannel();
        echo json_encode(['status' => 'updated']);
    } else {
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $image_url = isset($_POST['image_url']) ? trim($_POST['image_url']) : '';
        $created_by = isset($_POST['created_by']) ? (int)$_POST['created_by'] : 1;

        if (!empty($name) && !empty($description) && !empty($image_url)) {
            $channel = new Channel();
            $channel->name = $name;
            $channel->description = $description;
            $channel->image_url = $image_url;
            $channel->created_by = $created_by;

            $channel->ajoutChannel();
            echo json_encode(['status' => 'created']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Missing fields']);
        }
    }
    exit;
}
