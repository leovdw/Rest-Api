<?php
require_once('../class/db.class.php');

class Message
{
    public $errors = [];

    public function newmessage($message)
    {
        $dbh = Connection::get();
        if (isset($message->chatroom) && isset($message->content) && isset($message->user_id)) {

            $sql = "insert into messages (user_id, username, content, chatroom) values (:user_id, :username,  :content, :chatroom)";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            if ($sth->execute(array(
                ':user_id' => $message->user_id,
                ':username' => $message->user_name,
                ':content' => $message->content,
                ':chatroom' => $message->chatroom,
            ))) {
                return 'succes';
            } else {
                // ERROR
                // put errors in $session
                return ['Impossible denvoyer le message'];
            }
        }
    }

    public function message_list($room)
    {
        $dbh = Connection::get();
        if (isset($room->chatroom)) {
            $sql = "select content, username from messages where chatroom = :chatroom";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(
                ':chatroom' => $room->chatroom
            ));
            return $messages = $sth->fetchAll(PDO::FETCH_CLASS);

        }
    }
    public function user_list($user)
    {
        $dbh = Connection::get();
        if (isset($user->user_id)) {
            $sql = "select content, chatroom from messages where user_id = :id";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(
                ':id' => $user->user_id
            ));
            return $messages = $sth->fetchAll(PDO::FETCH_CLASS);
        }
    }
}
