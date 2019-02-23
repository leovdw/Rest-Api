<?php
require_once('../class/db.class.php');

class User
{
    public $errors = [];

    public function log_in($user)
    {
        $dbh = Connection::get();

        if (isset($user->login)) {
            if (empty($user->login)) {
                $errors[] = 'champ login vide';
            } else if (mb_strlen($user->login) > 45) {
                $errors[] = 'champ login trop long (45max)';
            } else {
                if (isset($user->password)) {
                    if (empty($user->password)) {
                        $errors[] = 'champ password vide';
                    } else {
                        $sql = "select password from users where login = :login limit 1";
                        $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                        $sth->execute(array(
                            ':login' => $user->login
                        ));
                        $storedPassword = $sth->fetchColumn();

                        if (!password_verify($user->password, $storedPassword)) {
                            $this->errors[] = 'Mot de passe faux';
                            $result = array( $this->errors , 'fail');
                            return $result;
                        } else {
                            // Getting ID of user
                            $sql = "select id from users where login = :login limit 1";
                            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                            $sth->execute(array(
                                ':login' => $user->login
                            ));
                            $id = $sth->fetchColumn();

                            //Generating new token for the user and inserting it in DB
                            $bytes = openssl_random_pseudo_bytes(8, $cstrong);
                            $new_token = bin2hex($bytes);

                            $sql = "update users set token = :token where id = :id";
                            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                            if ($sth->execute(array(
                                ':token' => $new_token,
                                ':id' => $id,
                            ))) {

                            } else {
                                $this->errors[] = 'Token non généré';

                                $result = array( $this->errors , 'fail');
                                return $result;
                            }

                            $result = array($id , 'succes', $new_token);
                            return $result;
                        }
                    }
                }
            }
        }
    }

    public function register($user_info)
    {
        $dbh = Connection::get();

        if (isset($user_info->login)) {
            if (empty($user_info->login)) {
                $errors[] = 'champ login vide';
                // si name > 50 chars
            } else if (mb_strlen($user_info->login) > 45) {
                $errors[] = 'champ login trop long (45max)';
            } else {
                $sql = "select count(id) from users where login = :login";
                $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                $sth->execute(array(
                    ':login' => $user_info->login
                ));
                if ($sth->fetchColumn() > 0) {
                    $errors[] = 'login deja pris blaireau';
                }
            }
        }
        if (isset($user_info->password)) {
            if (empty($user_info->password)) {
                $errors[] = 'champ password vide';
                // si name > 50 chars
            } else if (mb_strlen($user_info->password) < 5) {
                $errors[] = 'champ password trop court (5 min)';
            } else if (mb_strlen($user_info->password) > 20) {
                $errors[] = 'champ password trop long (20 max)';
            }
        }

        if (isset($user_info->name)) {
            if (empty($user_info->name)) {
                $errors[] = 'champ firstname vide';
                // si name > 50 chars
            } else if (mb_strlen($user_info->name) < 1) {
                $errors[] = 'champ firstname trop court (1 min)';
            } else if (mb_strlen($user_info->name) > 45) {
                $errors[] = 'champ firstname trop long (20 max)';
            }
        }

        if (isset($user_info->login) && count($errors) == 0) {
            // ben on insere dans la table message
            // la synaxe ":user_id" ca veut dire qu'on prepare la requete et que juste quand on la lance, on va remplacer ":user_id" par la bonne valeur.

            $hashedPassword = password_hash($user_info->password, PASSWORD_DEFAULT);
            /* syntaxe avec preparedStatements */
            $sql = "insert into users (login, password, firstname) values (:login, :password , :firstname)";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            if ($sth->execute(array(
                ':login' => $user_info->login,
                ':password' => $hashedPassword,
                ':firstname' => $user_info->name,
            ))) {
                // SUCCESS
                // redirect to list
                // on *redirige* vers la VIEW list

                return 'succes';
            } else {
                // ERROR
                // put errors in $session
                return $errors['pas reussi a creer le user'];
            }
        }
    }

    public function update_user($user_info)
    {
        $dbh = Connection::get();
        //return $user_info;
        if (isset($user_info->login)) {
            if (empty($user_info->login)) {
                return $this->errors[] = 'champ login vide';
                // si name > 50 chars
            } else if (mb_strlen($user_info->login) > 45) {
                return $this->errors[] = 'champ login trop long (45max)';
            } else {
                $sql = "select count(id) from users where login = :login";
                $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
                $sth->execute(array(
                    ':login' => $user_info->login
                ));
                if ($sth->fetchColumn() > 0) {
                    return $this->errors[] = 'login deja pris blaireau';
                }
            }
        }
        if (isset($user_info->password)) {
            if (empty($user_info->password)) {
                return $this->errors[] = 'champ password vide';
                // si name > 50 chars
            } else if (mb_strlen($user_info->password) < 5) {
                return $this->errors[] = 'champ password trop court (5 min)';
            } else if (mb_strlen($user_info->password) > 20) {
                return $this->errors[] = 'champ password trop long (20 max)';
            }
        }

        if (isset($user_info->name)) {
            if (empty($user_info->name)) {
                return $this->errors[] = 'champ firstname vide';
                // si name > 50 chars
            } else if (mb_strlen($user_info->name) < 1) {
                return $this->errors[] = 'champ firstname trop court (1 min)';
            } else if (mb_strlen($user_info->name) > 45) {
                return $this->errors[] = 'champ firstname trop long (20 max)';
            }
        }

        if (isset($user_info->login) && count($this->errors) == 0) {
            $hashedPassword = password_hash($user_info->password, PASSWORD_DEFAULT);
            /* syntaxe avec preparedStatements */
            $sql = "UPDATE users SET login = :login, password = :password, firstname = :firstname WHERE id = :id and token = :token";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            if ($sth->execute(array(
                'login' => $user_info->login,
                'password' => $hashedPassword,
                'firstname' => $user_info->name,
                'id' => $user_info->id,
                'token' => $user_info->token,
            ))) {

                $result = array( $user_info , 'Succses');
                return $result;
            } else {
                $this->errors[] = 'Failed to modify user';

                $result = array( $this->errors , 'fail');
                return $result;
            }
        }
    }

    public function verif_token($token)
    {
        if (isset($token)){
            $dbh = Connection::get();

            // Getting Token of user
            $sql = "select id, firstname, login from users where token = :token limit 1";
            $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $sth->execute(array(
                ':token' => $token->token
            ));
            $user_data = $sth->fetch();

            return $user_data;
        }
    }

    public function list()
    {
        $dbh = Connection::get();

        $stmt = $dbh->query("select * from message where ");
        // recupere les users et fout le resultat dans une variable sous forme de tableau de tableaux
        $users = $stmt->fetchAll(PDO::FETCH_CLASS);
        $_SESSION['users'] = $users;
    }

    public function delete($user)
    {
        $dbh = Connection::get();
        if (isset($user->id)) {
            $stmt = $dbh->query("delete from users where id = $user->id limit 1");
            return 'Succes';
        }
    }
}
