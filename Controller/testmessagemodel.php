<?php
require_once 'messageControler.php';

$controller = new MessageController();


$responseCreate = $controller->createMessage(1, 2, "m3ana Basma");
echo json_encode($responseCreate);


$responseGet = $controller->getMessagesByChannel(1);
echo json_encode($responseGet);


$responseDelete = $controller->deleteMessage(1); // assuming ID 3
echo json_encode($responseDelete);
?>