<?php

include_once("../class/message_class.php");

$Message = new Message();

session_start();

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'create':
        header("Access-Control-Allow-Headers: *");
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=UTF-8');

        $message_info = json_decode(file_get_contents('php://input'));
        $is_okay = $Message->newmessage($message_info);
        echo json_encode($is_okay);
        break;

    case 'list_room':
        header("Access-Control-Allow-Headers: *");
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=UTF-8');

        $chat_info = json_decode(file_get_contents('php://input'));

        $is_okay = $Message->message_list($chat_info);
        echo json_encode($is_okay);
        break;

    case 'user_list':
        header("Access-Control-Allow-Headers: *");
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=UTF-8');

        $user_info = json_decode(file_get_contents('php://input'));

        $is_okay = $Message->user_list($user_info);
        echo json_encode($is_okay);
        break;


    default;
        break;
}
?>
