<?php
require_once '../model/message.php';

class MessageController
{

    public function createMessage($channel_id, $user_id, $content)
    {
        try {
            $message = new Message(null, $channel_id, $user_id, $content);
            $id = $message->ajoutMessage();
            return [
                'success' => true,
                'message' => 'Message created successfully.',
                'id' => $id
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error creating message: ' . $e->getMessage()
            ];
        }
    }

    public function getMessagesByChannel($channel_id)
    {
        try {
            $messages = Message::afficherMessagesParChannel($channel_id);
            return [
                'success' => true,
                'messages' => $messages
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error fetching messages: ' . $e->getMessage()
            ];
        }
    }


    public function deleteMessage($id)
    {
        try {
            $result = Message::supprimerMessage($id);
            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Message deleted successfully.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Message not found or already deleted.'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error deleting message: ' . $e->getMessage()
            ];
        }
    }

    public function getMessageById($id)
    {
        try {
            $message = Message::getMessageById($id);
            if ($message) {
                return [
                    'success' => true,
                    'message_data' => [
                        'id' => $message->getId(),
                        'channel_id' => $message->getChannelId(),
                        'user_id' => $message->getUserId(),
                        'content' => $message->getContent(),
                        'sent_at' => $message->getSentAt()
                    ]
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Message not found.'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error fetching message: ' . $e->getMessage()
            ];
        }
    }


    public function updateMessage($id, $newContent)
    {
        try {
            $result = Message::modifierMessage($id, $newContent);
            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Message updated successfully.'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Message not found or update failed.'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error updating message: ' . $e->getMessage()
            ];
        }
    }
}
?>



