<?php

include_once("../class/user_class.php");

$User = new User();

session_start();

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'login':
        header("Access-Control-Allow-Headers: *");
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=UTF-8');

        $user_info = json_decode(file_get_contents('php://input'));
        $is_okay = $User->log_in($user_info);
        $_SESSION['ok'] = 'ok';
        echo json_encode($is_okay);
        break;

    case 'register':
        header("Access-Control-Allow-Headers: *");
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=UTF-8');

        $user_info = json_decode(file_get_contents('php://input'));

        $is_okay = $User->register($user_info);
        echo json_encode($is_okay);
        break;

    case 'update':
        header("Access-Control-Allow-Headers: *");
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=UTF-8');

        $user_info = json_decode(file_get_contents('php://input'));

        $is_okay = $User->update_user($user_info);
        echo json_encode($is_okay);
        break;

    case 'checktoken':
        header("Access-Control-Allow-Headers: *");
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=UTF-8');

        $token = json_decode(file_get_contents('php://input'));

        $is_okay = $User->verif_token($token);
        echo json_encode($is_okay);
        break;

    case 'delete':
        header("Access-Control-Allow-Headers: *");
        header('Access-Control-Allow-Origin: *');
        header('Content-type: application/json; charset=UTF-8');

        $user = json_decode(file_get_contents('php://input'));

        $is_okay = $User->delete($user);
        echo json_encode($is_okay);
        break;

    default;
        break;
}
?>
