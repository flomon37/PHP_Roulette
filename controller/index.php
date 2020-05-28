<?php
    require_once('../model/DaoPlayer.php');
    require_once('../model/DaoGame.php');
    require_once('./fonctions.php');
    session_start();

/*--------- Variables d'affichage -------------------*/
        // View Connexion.php
        $errorUser = "";
        $errorPass = "";
        
        //View Inscription.php
        $errorInscriptionUser = "";
        $errorInscriptionPass = "";
        $errorInscriptionMoney = "";

        // View Roulette.php
        $errorMiseArgent = "";
        $errorMiseNb = "";
        $errorMiseParite = "";
        $errorTypePari = "";

        $infoPartie = "";
        $infoResultat = "";

/*---------------------------------------------------*/


    // Appui sur le lien de deconnexion dans roulette.php
    if (isset($_GET['deco']) and $_GET['deco'] == "true") {
        session_unset();
    }

    // Alors je dois afficher roulette.php
    if ( isset($_SESSION['Player']) ) {
        $_SESSION['pageActuelle'] = "roulette";
        //Recharger le joueur depuis la BDD
        $maj = new DaoPlayer();
        $_SESSION['Player'] = $maj->getUserById($_SESSION['Player']->id);
        // On veut lancer la roulette
        if (isset($_POST['btnJouer'])) {
            // On ne peut jouer que si on a de l'argent
            if ($_SESSION['Player']->money <= 0) {
                $infoPartie = MessagePlusArgent();
            // On a de l'argent pour jouer
            } else {
                if ( isset($_POST['mise'], $_POST['nbMise']) ) {
                    // On ne peut jouer que si la mise est inférieure à l'argent dispo
                    if ($_POST['mise'] != '') {
                        $CheckMise = checkMise($_POST['mise'], $_SESSION['Player']->money);
                        $errorMiseArgent = $CheckMise['errorMise'];
                    } else {
                        $errorMiseArgent = MessageMiseManquante();
                    }
                    //on veut miser sur un nombre
                    if ($_POST['nbMise'] != '') {
                        $checkNbMise = checkNbMise($_POST['nbMise']);
                        $errorMiseNb = $checkNbMise['errorNbMise'];
                        // Tout est ok on peut lancer la roulette
                        if ($CheckMise['OK'] && $checkNbMise['OK']) {

                            $_SESSION['nbGagnant'] = rand(1,36);
                            $reponse = roulette((int) $_SESSION['nbGagnant'], (int) $_SESSION['Player']->money, (int) $_POST['mise'], 0 ,(int) $_POST['nbMise']);

                            $bddPlayer = new DaoPlayer();
                            $bddGame = new DaoGame();
                            $_SESSION['lastGame'] = new DtoGame($_SESSION['Player']->id, date('Y-m-d H:i:s'), (int) $_POST['mise'], $reponse['profit']);
                            $bddGame->insertGame($_SESSION['lastGame']);
                            $_SESSION['Player']->money = $reponse['argentTot'];
                            $bddPlayer->updateUser($_SESSION['Player']);

                            $infoPartie = MessageInfoPartie($_SESSION['nbGagnant'], $_POST['nbMise'], 0);
                            $infoResultat = MessageGagnant($reponse['gagnant'], $_POST['nbMise'], $_POST['mise']);

                        }
                    //sinon on veut miser sur la parité    
                    } else {
                        if (isset($_POST['parite']) and $_POST['parite'] != '') {
                            $checkParite = checkParite($_POST['parite']);
                            $errorMiseParite = $checkParite['errorParite'];

                             // Tout est ok on peut lancer la roulette
                            if ($CheckMise['OK'] && $checkParite['OK']) {
                                $_SESSION['nbGagnant'] = rand(1,36);
                                $reponse = roulette((int) $_SESSION['nbGagnant'], (int) $_SESSION['Player']->money, (int) $_POST['mise'], $_POST['parite'] ,-1);

                                $bddPlayer = new DaoPlayer();
                                $bddGame = new DaoGame();
                                $_SESSION['lastGame'] = new DtoGame((int) $_SESSION['Player']->id, date('Y-m-d H:i:s'), (int) $_POST['mise'], $reponse['profit']);
                                $bddGame->insertGame($_SESSION['lastGame']);
                                $_SESSION['Player']->money = $reponse['argentTot'];
                                $bddPlayer->updateUser($_SESSION['Player']);

                                $infoPartie = MessageInfoPartie($_SESSION['nbGagnant'], -1, $_POST['parite']);
                                $infoResultat = MessageGagnant($reponse['gagnant'], $_POST['nbMise'], $_POST['mise']);

                            }
                        } else {
                            $errorTypePari = MessageTypePariManquant();
                        }
                    }
                 }
            }
           
            // Si l'utilisateur recharge la page sans recharger le formulaire (quelques infos sur la dernière partie)
        } else if ( isset($_SESSION['lastGame'], $_SESSION['nbGagnant']) ) {
            $infoPartie = "<p> <span style=\"font-weight:bold\">Dernière partie joué :</span> <br><br> Numéro gagnant : ".$_SESSION['nbGagnant']."</p>";
            if ($_SESSION['lastGame']->profit > 0) {
                $infoResultat = "<p style=\"color:green;\">Gain : ".$_SESSION['lastGame']->profit."€</p>";
            } else {
                $infoResultat = "<p style=\"color:red;\">Perte : ".abs($_SESSION['lastGame']->profit)."€</p>";
            }
        }
    }
    // sinon je dois afficher soit connexion.php soit inscription.php
    if ( !isset($_SESSION['Player']) ) {
        //Tentative de connexion sur connexion.php
        if (isset($_POST['btnConnexion'])) {
            if ( isset($_POST['username']) and $_POST['username'] == '') {
                $errorUser ="<div style='color:red'>Veuillez rentrer un nom d'utilisateur</div>";
            }

            if (isset($_POST['passwd']) and $_POST['passwd'] == '') {
                $errorPass = "<div style='color:red'>Veuillez rentrer un mot de passe</div>";
            }

            if ( isset($_POST['username']) and $_POST['username'] != '' and isset($_POST['passwd']) and $_POST['passwd'] != '') {
                $bdd = new DaoPlayer();
                $testUser = $bdd->checkUser($_POST['username'], $_POST['passwd']);

                if ($testUser['notFound']) {
                    $errorUser = "<div style='color:red'>Aucun utilisateur de ce nom</div>";
                } else if (!$testUser['pass']) {
                    $errorPass = "<div style='color:red'>Le mot de passe est incorrect</div>";
                } else {
                    $_SESSION['Player'] = $bdd->getUser($_POST['username'], $_POST['passwd']);
                    $_SESSION['pageActuelle'] = "roulette";
                }
            }
        }

        // On a appuyé sur la page inscription
        if ( isset($_GET['inscription']) and $_GET['inscription'] == "true" ) {
            $_SESSION['pageActuelle'] = "inscription";
        }
        //On souhaite revenir à la page de connexion
        if (isset($_GET['connexion']) and $_GET['connexion'] == "true") {
            unset($_SESSION['pageActuelle']);
        }
        // On a appuyé sur le bouton s'inscrire sur connexion.php
        if ( isset($_POST['btnInscription']) ) {
            //On a bien remplis tous les champs du formulaire
            if (isset($_POST['username']) and $_POST['username'] != '' and
                isset($_POST['passwd']) and $_POST['passwd'] != '' and
                isset($_POST['money']) and $_POST['money'] != '') {
                    $bdd = new DaoPlayer();
                    $testUser = $bdd->checkUser($_POST['username'], $_POST['passwd']);
                    //L'utilisateur saisis n'existe pas dans la BDD donc on peut l'ajouter
                    if ($testUser['notFound']) {
                        // L'argent rentré est bien valide (>0)
                        if ((int) $_POST['money'] > 0) {
                            $Player = new DtoPlayer(-1, $_POST['username'], $_POST['passwd'], $_POST['money'] );
                            $bdd->insertUser($Player);
                            $_SESSION['Player'] = $bdd->getUser($_POST['username'], $_POST['passwd']);
                            $_SESSION['pageActuelle'] = "roulette";
                        } else {
                            //argent invalide
                            $errorInscriptionMoney = "<br><div style='color:red'>Veuillez rentrer un montant strictement positif</div>";
                        }
                    } else {
                        //utilisateur existe deja dans la base de données
                        $errorInscriptionUser = "<br><div style='color:red'>Un utilisateur avec ce nom existe déjà</div>";
                    }
            }
            if ( isset($_POST['username']) and $_POST['username'] == ''){
                //champ nom d'utilisateur non remplis
                $errorInscriptionUser = "<br><div style='color:red'>Veuillez rentrer un nom d'utilisateur</div>";
            }
            if ( isset($_POST['passwd']) and $_POST['passwd'] == '' ) {
                //champ mot de passe non remplis
                $errorInscriptionPass = "<br><div style='color:red'>Veuillez rentrer un mot de passe</div>";
            }
            if ( isset($_POST['money']) and $_POST['money'] == '' ) {
                //champ money non remplis
                $errorInscriptionMoney = "<br><div style='color:red'>Veuillez rentrer une somme d'argent</div>";
            }

        }
    }



        if (isset($_SESSION['pageActuelle'])) {

            if( $_SESSION['pageActuelle'] == "roulette" ) {
                
                $titre = "Roulette";
                include('../view/header.php');
                include('../view/roulette.php');
                include('../view/footer.php');

            } else if ( $_SESSION['pageActuelle'] == "inscription" )  {
                $titre="Inscription";
                include('../view/header.php');
                include('../view/inscription.php');
                include('../view/footer.php');

            }

        } else {
            $titre = "Connexion";
            include('../view/header.php');
            include('../view/connexion.php');
            include('../view/footer.php');
    }



?>