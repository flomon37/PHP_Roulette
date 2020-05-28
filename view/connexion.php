	<body>
		<header>
            <h1>Connectez-vous pour<br>jouer à la roulette</h1>
        </header>
        
        <div class="login-page">
            <div class="form">
                <form class="login-form" action="index.php" method="POST">
                    <input class="textinput" type="text" name="username" placeholder="Nom d'utilisateur"> <?=$errorUser?>
                    <input class="textinput" type="password" name="passwd" placeholder="Mot de passe"> <?=$errorPass?>
                    <button type="submit" name="btnConnexion">Connexion</button>
                    <p class="message">Pas de comte ? <a href="index.php?inscription=true">Inscrivez-vous</a></p>
                </form>
            </div>
        </div>
