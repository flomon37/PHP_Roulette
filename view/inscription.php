        <header>
            <h1>Veuillez vous inscrire<br>pour pouvoir jouer à la roulette</h1>
		</header>

        <div class="login-page">
            <div class="form">
                <form class="register-form" action="index.php" method="POST">
                    <input class="textinput" type="text" name="username" placeholder="Identifiant"><?=$errorInscriptionUser?>
                    
                    <input class="textinput" type="password" name="passwd" placeholder="Mot de passe"><?=$errorInscriptionPass?>
                    
                    <input class="textinput" type="number" name="money" placeholder="Mise Argent" min="0"><?=$errorInscriptionMoney?>

                    <button type="submit" name="btnInscription">S'inscrire</button>
                    <p class="message">Déjà un compte ? <a href="index.php?connexion=true">Connectez-vous</a></p>
                </form>
            </div>
        </div>