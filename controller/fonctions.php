<?php
    function roulette($nbGagnant, $argentTot, $mise, $parite = 0, $nbMise = -1) {
        $reponse = [];
        $reponse['nbMise'] = $nbMise;
        $reponse['nbGagnant'] = $nbGagnant;
        $reponse['argentTot'] = $argentTot;
        $reponse['mise'] = $mise;
        $reponse['parite'] = $parite;
        $reponse['gagnant'] = false;
        $reponse['profit'] = -$mise;
        
        if ($argentTot >= $mise) {
                $reponse['argentTot'] -= $mise;
                $pariteGagnant = $nbGagnant % 2;

                if ($nbMise > 0) {

                        if ($nbMise == $nbGagnant) {
                            $reponse['profit'] += 35*$mise;
                            $reponse['argentTot'] += 35*$mise;
                            $reponse['gagnant'] = true;
                        }

                } else {

                    if ($parite == "pair") {
                        $pariteNb = 0;
                    } else if ($parite == "impair") {
                        $pariteNb = 1;
                    }

                    if ($pariteGagnant == $pariteNb) {
                        $reponse['profit'] += 2*$mise;
                        $reponse['argentTot'] += 2*$mise;
                        $reponse['gagnant'] = true;
                    }
                }
            }

       return $reponse;
    }

    function checkMise($mise, $argentDispo) {
        $reponse = [];
        $reponse['OK'] = false;
        $reponse['errorMise'] = "";
        if ($mise > $argentDispo) {
            $reponse['errorMise'] = "<div style=\"color:red;\">La mise est supérieure à l'argent disponible</div>";
        } else {
            if ($mise <= 0) {
                $reponse['errorMise'] = "<div style=\"color:red;\">La mise doit être strictement positive</div>";
            } else {
                $reponse['OK'] = True;
            }
        }
        return $reponse;
    }

    function checkNbMise($nbMise) {
        $reponse = [];
        $reponse['OK'] = false;
        $reponse['errorNbMise'] = "";
        if ($nbMise >= 1 and $nbMise <= 36) {
            $reponse['OK'] = True;
        } else {
            $reponse['errorNbMise'] = "<div style=\"color:red;\">Le nombre doit être compris entre 1 et 36</div>";;
        }
        return $reponse;
    }

    function checkParite($parite) {
        $reponse = [];
        $reponse['OK'] = false;
        $reponse['errorParite'] = "";

        if ($parite == "pair" or $parite == "impair") {
            $reponse['OK'] = True;
        } else {
            $reponse['errorParite'] = "<div style=\"color:red;\">La parité doit être paire ou impaire</div>";
        }
        return $reponse;
    }


    function pariteNombre($nbGagnant) {
        if ($nbGagnant % 2 == 0) {
            return "pair";
        } else {
            return "impair";
        }
    }

    function MessageInfoPartie($nbGagnant, $nbMise = -1, $parite = 0) {
       
        if ($nbMise > 0) {
            return "<p>Vous avez parié sur le numéro $nbMise <br> Le numéro gagnant est $nbGagnant</p>";
        } else {
            return "<p>Vous avez parié sur la parité ".$parite."<br> Le numéro gagnant est $nbGagnant qui est ".pariteNombre($nbGagnant)."</p>";
        }
    }

    function MessageGagnant($gagnant, $miseNb, $mise) {
        if ($miseNb > 0) {
            $argentGagnant = 35*$mise;
        } else {
            $argentGagnant = 2*$mise;
        }
        if ($gagnant) {
           return "<p style=\"color:green;\">Vous avez gagné ".$argentGagnant." €</p>";
        } else if ($gagnant == 0) {
            return "<p style=\"color:red;\">Vous avez perdu $mise €";
        }
    }

    function MessagePlusArgent() {
        return "<p><span style=\"background-color:red;\">Vous n'avez plus d'argent pour jouer </span></p>";   
    }

    function MessageMiseManquante(){
        return "<p><span style=\"color:red;\">Merci de rentrer une mise</span></p>";
    }

    function MessageTypePariManquant(){
        return "<p><span style=\"color:red;\">Merci de miser sur un nombre ou une Parité</span></p>";
    }

?>