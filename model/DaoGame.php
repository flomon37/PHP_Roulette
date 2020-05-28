<?php

    require_once('DtoGame.php');

    class DaoGame {
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

        public function insertGame($game) {
            $requete = 'INSERT INTO Game (id, date, bet, profit) VALUES (:id, :date, :bet, :profit)';
            $reponse = $this->bdd->prepare($requete);
            $reponse->execute(
                array(
                ':id' => $game->id,
                ':date' => $game->date,
                ':bet' => $game->bet,
                ':profit' => $game->profit
                )
            );
        }


        private $bdd;
        private $host = '';
        private $usr = '';
        private $pwd = '';   
    }