<?php

require_once '../model/database.php';

header('Content-Type: application/json');

try {
    $db = config::getConnexion();
    $query = $db->query("SELECT * FROM channel");

    $channels = [];
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $channels[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'image_url' => $row['image_url']
        ];
    }

    echo json_encode($channels);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

