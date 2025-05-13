<?php
require_once 'database.php';

class Message
{
    private $conn;

    public $id;
    public $channel_id;
    public $user_id;
    public $content;
    public $sent_at;

    public function __construct($id = null, $channel_id = null, $user_id = null, $content = "", $sent_at = null)
    {
        $this->id = $id;
        $this->channel_id = $channel_id;
        $this->user_id = $user_id;
        $this->content = $content;
        $this->sent_at = $sent_at;
    }


    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getChannelId() { return $this->channel_id; }
    public function setChannelId($channel_id) { $this->channel_id = $channel_id; }

    public function getUserId() { return $this->user_id; }
    public function setUserId($user_id) { $this->user_id = $user_id; }

    public function getContent() { return $this->content; }
    public function setContent($content) { $this->content = $content; }

    public function getSentAt() { return $this->sent_at; }
    public function setSentAt($sent_at) { $this->sent_at = $sent_at; }


    public function ajoutMessage()
    {
        try {
            $this->validateMessageData();

            $db = Config::getConnexion();

            $sql = "INSERT INTO messages (channel_id, user_id, content, timestamp) 
                    VALUES (:channel_id, :user_id, :content, NOW())";

            $stmt = $db->prepare($sql);

            $stmt->bindParam(':channel_id', $this->channel_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindParam(':content', $this->content, PDO::PARAM_STR);

            $stmt->execute();
            return $db->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }

    public static function afficherMessagesParChannel($channel_id)
    {
        try {
            $db = Config::getConnexion();
            $stmt = $db->prepare("SELECT message_id, channel_id, user_id, content, timestamp FROM messages WHERE channel_id = :channel_id ORDER BY timestamp ASC");
            $stmt->bindParam(':channel_id', $channel_id, PDO::PARAM_INT);
            $stmt->execute();

            $messages = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $messages[] = [
                    'id' => $row['message_id'],
                    'channel_id' => $row['channel_id'],
                    'user_id' => $row['user_id'],
                    'content' => $row['content'],
                    'sent_at' => $row['timestamp']
                ];
            }
            return $messages;
        } catch (PDOException $e) {
            throw new Exception("Error fetching messages: " . $e->getMessage());
        }
    }


    public static function supprimerMessage($id)
    {
        try {
            $db = Config::getConnexion();
            $id = (int)$id;

            $db->beginTransaction();

            $sql = "DELETE FROM messages WHERE message_id = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $rowsAffected = $stmt->rowCount();

            $db->commit();

            return $rowsAffected > 0;
        } catch (PDOException $e) {
            if($db->inTransaction()) {
                $db->rollBack();
            }
            throw new Exception("Error deleting message: " . $e->getMessage());
        }
    }


    public static function getMessageById($id)
    {
        try {
            $db = Config::getConnexion();
            $stmt = $db->prepare("SELECT message_id, channel_id, user_id, content, timestamp FROM messages WHERE message_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return new Message(
                    $row['message_id'],
                    $row['channel_id'],
                    $row['user_id'],
                    $row['content'],
                    $row['timestamp']
                );
            }
            return null;
        } catch (PDOException $e) {
            throw new Exception("Error fetching message: " . $e->getMessage());
        }
    }

    public function validateMessageData()
    {
        if (empty($this->content)) {
            throw new Exception('Message cannot be empty.');
        }

        if (strlen($this->content) > 500) {
            throw new Exception('Message is too long (500 characters max).');
        }

        if (empty($this->channel_id) || !is_numeric($this->channel_id)) {
            throw new Exception('Invalid channel ID.');
        }

        if (empty($this->user_id) || !is_numeric($this->user_id)) {
            throw new Exception('Invalid user ID.');
        }
    }

    public static function modifierMessage($id, $newContent)
    {
        try {

            if (empty($newContent)) {
                throw new Exception('Message content cannot be empty.');
            }

            if (strlen($newContent) > 500) {
                throw new Exception('Message is too long (500 characters max).');
            }


            $db = Config::getConnexion();


            $sql = "UPDATE messages SET content = :content WHERE message_id = :id";


            $stmt = $db->prepare($sql);


            $stmt->bindParam(':content', $newContent, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);


            $stmt->execute();


            return $stmt->rowCount() > 0;

        } catch (PDOException $e) {
            throw new Exception("Error updating message: " . $e->getMessage());
        }
    }

}