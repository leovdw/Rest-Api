<?php

include_once("../class/chat_class.php");

$Chatroom = new Chatroom();

session_start();

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'create':
        header("Access-Control-Allow-Headers: *");
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=UTF-8');

        $chat_info = json_decode(file_get_contents('php://input'));

        $is_okay = $Chatroom->create($chat_info);
        echo json_encode($is_okay);
        break;

    case 'update_chat':
        header("Access-Control-Allow-Headers: *");
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=UTF-8');

        $chat_info = json_decode(file_get_contents('php://input'));

        $is_okay = $Chatroom->update_chat($chat_info);
        echo json_encode($is_okay);
        break;

    case 'list':
        header("Access-Control-Allow-Headers: *");
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=UTF-8');

        $creator = json_decode(file_get_contents('php://input'));

        $is_okay = $Chatroom->list($creator);
        echo json_encode($is_okay);
        break;

    case 'delete':
        header("Access-Control-Allow-Headers: *");
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=UTF-8');

        $room = json_decode(file_get_contents('php://input'));

        $is_okay = $Chatroom->delete($room);
        echo json_encode($is_okay);
        break;

    default;
        break;
}
?>
