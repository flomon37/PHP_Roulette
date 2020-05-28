<?php
    class DtoGame {

        public function __construct($id, $date, $bet, $profit) {
            $this->id = $id;
            $this->date = $date;
            $this->bet = $bet;
            $this->profit = $profit;

        }

        public function __get($attr) {
            if ( property_exists('DtoGame', $attr) ) {
                    return $this->$attr;
            }
        }

        public function __set($attr, $val) {
            if ( property_exists('DtoGame', $attr) ) {
                    $this->$attr = $val;
            }
        }

        private $id;
        private $date;
        private $bet;
        private $profit;
    }

?>