<?php

require_once '../model/Channel.php';

class ChannelController
{
    public function afficherChannelsJSON()
    {
        try {
            $channels = Channel::afficherChannels();
            header('Content-Type: application/json');
            echo json_encode($channels);
        } catch (PDOException $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
