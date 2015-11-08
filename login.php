<?php

include('includes/header.php');
?>

    <div class="mdl-card mdl-shadow--16dp centre_card">
        <div class="mdl-card__supporting-text">

            <form method="POST" action="action/login.php">

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" name="mail" id="mail"
                           pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]+$"/>
                    <label class="mdl-textfield__label" for="mail">Email:</label>
                    <span class="mdl-textfield__error">Entrez une adresse email valide.</span>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="password" name="pwd" id="pwd" pattern=".+$"/>
                    <label class="mdl-textfield__label" for="pwd">Mot de passe:</label>
                </div>

                <button class="mdl-button mdl-js-button mdl-js-ripple-effect submit-button" type="submit">
                    Se connecter
                </button>

            </form>

        </div>

        <?php
        /*
        if (isset($_GET['error'])) {
            echo
            '<div class="mdl-card__actions mdl-card--border error">',
            $error,
            '</div>';
        }
        */
        ?>

    </div>

    <p class="centrer_texte">Pas encore de compte?<br/>
        <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="signin.php">
            S'inscrire
        </a>
    </p>


<?php

include('includes/footer.php');

?>