        <header>
            <h1>Faîtes vos jeux</h1>
        </header>

        <div class="form">
            <h3> Bonjour <?= htmlspecialchars($_SESSION['Player']->name)?>
                <br>Il vous reste : <?= htmlspecialchars($_SESSION['Player']->money)?>€
            </h3>

            <?= $infoPartie ?>
            <br>
            <?= $infoResultat ?>
        </div>

        <div class="roulette-page">
            <div class="form">
                <form action="index.php" method="POST">

                    <input class="textinput" type="number" name="mise" placeholder="Votre mise" min="1" max="<?= htmlspecialchars($_SESSION['Player']->money)?>">
                    <br>
                    <?= $errorMiseArgent ?>
    
                    <input class="textinput" type="number" name="nbMise" placeholder="Miser sur votre nombre" min="1" max="36">
                    <?= $errorMiseNb ?>
            
                    <p>ou</p>
                    <br>
                    <p>Miser sur la parité</p>
                    <div class="inputGroup">
                        <input id="pair" type="radio" name="parite" value="pair">
                        <label for="pair">Pair</label>
                    </div>
                    <div class="inputGroup">
                        <input id="impair" type="radio" name="parite" value="impair">
                        <label for="impair">Impair</label>
                    </div>
                    <?= $errorMiseParite ?>
                    <?= $errorTypePari ?>
                    <br>
                    <button type="submit" name="btnJouer">Jouer</button>
                    <p class="message">Marre de jouer ? <a href="index.php?deco=true">Se déconnecter</a></p>
                </form>
            </div>
        </div>