<?php
    require_once('DtoPlayer.php');

    class DaoPlayer {

        public function __construct() {
            try {
                $this->bdd = new PDO('mysql:'.
                                    'host='.$this->host.';'.
                                    'dbname='.$this->usr.';'.
                                    'charset=utf8',
                                    $this->usr,
                                    $this->pwd);
            } catch(Exception $e) {
                die("ERROR : ".$e->getMessage());
            }
        }

        public function checkUser($name, $pass) {
            $requete = 'SELECT name, password FROM Player';
            $reponse = $this->bdd->query($requete);

            $return = [];
            $return['user'] = False;
            $return['pass'] = False;
            $return['notFound'] = True;

            while($data = $reponse->fetch()) {

                if ($name == $data['name'] ) {
                    $return['notFound'] = False;
                    $return['user'] = True; 
                    $return['pass'] = $pass == $data['password'];
                }
            }
            return $return;
        }

        public function insertUser($Player) {
            $requete = 'INSERT INTO Player (name, password, money) VALUES (:name, :password, :money)';
            $reponse = $this->bdd->prepare($requete);
            $reponse->execute(
                array(
                    ':name' => $Player->name,
                    ':password' => $Player->password,
                    ':money' => $Player->money
                )
            );
        }

        public function getUser($name, $pass) {

            $testUser = $this->checkUser($name, $pass);
            if ($testUser['user'] && $testUser['pass']) {

                $requete = 'SELECT id, name, password, money FROM Player WHERE name=:name AND password=:pass';
                $reponse = $this->bdd->prepare($requete);
                $reponse->execute(
                    array(
                        ':name' => $name,
                        ':pass' => $pass
                    )
                );
                $data = $reponse->fetch();
                $Player = new DtoPlayer($data['id'], $data['name'], $data['password'], $data['money']);
                return $Player;
            }
            return NULL; 
        }

        public function getUserById($id) {

                $requete = 'SELECT * FROM Player WHERE id=:id';
                $reponse = $this->bdd->prepare($requete);
                $reponse->execute(
                    array(
                        ':id' => $id,
                    )
                );
                $data = $reponse->fetch();
                $Player = new DtoPlayer($data['id'], $data['name'], $data['password'], $data['money']);
                return $Player;
        }

        public function updateUser($Player) {
            $requete = 'UPDATE Player set money = :money WHERE id = :id;';
            $reponse = $this->bdd->prepare($requete);
            $reponse->execute(
                array(
                    ':money' => $Player->money,
                    ':id' => $Player->id
                )
            );
        }
        

        private $bdd;
        private $host = '';
        private $usr = '';
        private $pwd = '';     
    }


?>