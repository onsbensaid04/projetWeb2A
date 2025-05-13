<?php
require_once '../model/ModelChannel.php';

header('Content-Type: application/json');

try {
    $db = config::getConnexion();

    if (isset($_GET['id'])) {
        // Fetch a specific channel by its ID
        $channelId = (int)$_GET['id'];
        $query = $db->prepare("SELECT * FROM channel WHERE id = :id");
        $query->bindParam(':id', $channelId, PDO::PARAM_INT);
        $query->execute();

        if ($query->rowCount() > 0) {
            $channel = $query->fetch(PDO::FETCH_ASSOC);

            // ✅ Only return if the name is NOT empty
            if (!empty(trim($channel['name']))) {
                echo json_encode([
                    'id' => $channel['id'],
                    'name' => $channel['name'],
                    'description' => $channel['description'],
                    'image_url' => $channel['image_url']
                ]);
            } else {
                echo json_encode(['error' => 'Channel name is empty']);
            }
        } else {
            echo json_encode(['error' => 'Channel not found']);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['channel_id'])) {
        // Delete a channel
        $channelId = (int)$_POST['channel_id'];

        $query = $db->prepare("DELETE FROM channel WHERE id = :id");
        $query->bindParam(':id', $channelId, PDO::PARAM_INT);
        $query->execute();

        if ($query->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Channel deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error deleting channel or channel does not exist']);
        }
    } else {
        // Fetch all channels
        $query = $db->query("SELECT * FROM channel");

        $channels = [];
        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                // ✅ Only add channels that have a non-empty name
                if (!empty(trim($row['name']))) {
                    $channels[] = [
                        'id' => $row['id'],
                        'name' => $row['name'],
                        'description' => $row['description'],
                        'image_url' => $row['image_url']
                    ];
                }
            }
            echo json_encode($channels);
        } else {
            echo json_encode([]);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
