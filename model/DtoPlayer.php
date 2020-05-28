<?php
    class DtoPlayer {

        public function __construct($id, $name, $password, $money) {
            $this->id = $id;
            $this->name = $name;
            $this->password = $password;
            $this->money = $money;

        }

        public function __get($attr) {

            if ( property_exists('DtoPlayer', $attr) ) {
                    return $this->$attr;
            }
        }

        public function __set($attr, $val) {

            if ( property_exists('DtoPlayer', $attr) ) {
                if ( $attr != "id" ) {
                    $this->$attr = $val;
                }
            }
            
        }

        private $id;
        private $name;
        private $password;
        private $money;
    }

?>