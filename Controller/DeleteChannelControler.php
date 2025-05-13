<?php
require_once '../model/ModelChannel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['channel_id']) && is_numeric($_POST['channel_id'])) {
        $channelId = intval($_POST['channel_id']);


        Channel::supprimerChannel($channelId);


        header("Location: ../Vue/Back/Back/channels.html");
        exit();
    } else {

        header("Location: ../Vue/Back/Back/channels.html*");
        exit();
    }
} else {
    header("HTTP/1.1 405 Method Not Allowed");
    echo "Method Not Allowed";
}
