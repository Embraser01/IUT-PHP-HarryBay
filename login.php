<?php

include('includes/header.php');

//Affichage de l'éventuel message d'erreur

if (isset($_GET['error'])) {

    echo '<p class="error"><i class="material-icons md-48">error</i><br/>';

    switch ($_GET['error']) {
        case 1:
            echo 'Mauvais Email/Mot de passe.';
            break;
        case 2:
            echo 'Vous êtes déjà connecté, <a href="action/logout.php">se déconnecter</a>.';
            break;
        case 3:
            echo 'Il manque des informations !';
            break;
        case 4:
            echo 'Vous êtes déconnecté. Veuillez vous reconnecter pour modifier vos objets.';
            break;
        case 14:
            echo 'Vous êtes déconnecté. Veuillez vous reconnecter pour supprimer vos objets.';
            break;
        default:
            echo 'Un problème est survenu.';
    }

    echo '</p><hr>';
}
?>

    <div class="mdl-card mdl-shadow--16dp centre_card">
        <div class="mdl-card__supporting-text">

            <form method="POST" action="action/login.php">

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" name="mail" id="mail" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]+$"/>
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