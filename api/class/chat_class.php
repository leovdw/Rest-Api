<?php
require_once('../class/db.class.php');

class Chatroom
{
    public $errors = [];

    public function create($chat)
    {
        $dbh = Connection::get();
        if (isset($chat->name)) {
            if (empty($chat->name)) {
                return $errors[] = 'champ login vide';
                // si name > 50 chars
            } else if (mb_strlen($chat->name) > 45) {
                return $errors[] = 'champ Nom trop long (45max)';
            } else {
                $sql = "select count(id) from chatrooms where name = :name";
                $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                $sth->execute(array(
                    ':name' => $chat->name
                ));
                if ($sth->fetchColumn() > 0) {
                    return $errors[] = ['Chatroom name deja pris blaireau'];
                }
            }
        }


        if (isset($chat->name) && isset($chat->creator)) {

            $sql = "insert into chatrooms (name, creator) values (:name, :creator )";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            if ($sth->execute(array(
                ':name' => $chat->name,
                ':creator' => $chat->creator,
            ))) {
                // SUCCESS
                // redirect to list
                // on *redirige* vers la VIEW list

                return 'succes';
            } else {
                // ERROR
                // put errors in $session
                return ['pas reussi a creer la room'];
            }
        }
    }

    public function update_chat($chat)
    {
        $dbh = Connection::get();

        if (isset($chat->name)) {
            if (empty($chat->name)) {
                return $errors[] = ['champ name vide'];
                // si name > 50 chars
            } else if (mb_strlen($chat->name) > 45) {
                return $errors[] = ['champ Nom trop long (45max)'];
            } else {
                $sql = "select count(id) from chatrooms where name = :name";
                $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                $sth->execute(array(
                    ':name' => $chat->name
                ));
                if ($sth->fetchColumn() > 0) {
                    return $errors[] = ['Ce nom de chatroom a déjé été pris pas un utilisateur !'];
                }
            }
        }

        if (isset($chat->id) && isset($chat->name)) {
            $sql = "UPDATE chatrooms SET name = :name WHERE id = :id";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

            if (
            $sth->execute(
                array(
                    'id' => $chat->id,
                    'name' => $chat->name,
                )
            )
            ) {

                $result = 'Succses';
                return $result;
            } else {
                $this->errors[] = 'Failed to modify Chatroom Name';
                $result = array($this->errors, 'fail');
                return $result;
            }
        }
    }

    public function list($creator)
    {
        $dbh = Connection::get();

        if (isset($creator->id)) {
            $stmt = $dbh->query("select name, id from chatrooms where creator = $creator->id");
            return $users = $stmt->fetchAll(PDO::FETCH_CLASS);
        }
    }
    public function delete($room)
    {
        $dbh = Connection::get();
        if (isset($room->id)) {
            $stmt = $dbh->query("delete from chatrooms where id = $room->id limit 1");
            return 'Succes';
        }
    }
}
