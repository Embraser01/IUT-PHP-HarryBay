<?php
/**
 * Created by PhpStorm.
 * User: Marc-Antoine
 * Date: 06/10/2015
 * Time: 14:38
 */

include('includes/header.php');


//Affichage de l'éventuel message d'erreur

if (isset($_GET['error'])) {
    echo '<p class="error"><i class="material-icons md-48">error</i><br/>';

    switch ($_GET['error']) {
        case 1:
            echo 'Cet utilisateur existe déjà.'; break;
        case 2:
            echo 'Mauvais mot de passe'; break;
        case 3:
            echo 'Vous êtes déjà connecté, <a href="action/logout.php">Se déconnecter</a>.'; break;
        case 4:
            echo 'Veuillez renseigner tous les champs.'; break;
        default:
            echo 'Un problème est survenu.';
    }
    echo '</p><hr>';
}
?>

    <div class="mdl-card mdl-shadow--16dp centre_card signin_card">

        <div class="mdl-card__title titre_card">
            <h1 class="mdl-card__title-text">S'inscrire</h1>
        </div>

        <div class="mdl-card__supporting-text">

            <form method="POST" action="action/signin.php">

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" name="nom" id="nom" pattern="[a-zA-Z ]+$"/>
                    <label class="mdl-textfield__label" for="nom">Nom:</label>
                    <span class="mdl-textfield__error">Votre nom doit uniquement contenir des lettres et espaces.</span>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" name="prenom" id="prenom" pattern="[a-zA-Z ]+$"/>
                    <label class="mdl-textfield__label" for="prenom">Prénom:</label>
                    <span class="mdl-textfield__error">Votre prénom doit uniquement contenir des lettres et espaces.</span>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="email" name="mail" id="mail" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]+$"/>
                    <label class="mdl-textfield__label" for="mail">Email:</label>
                    <span class="mdl-textfield__error">Entrez une adresse email valide.</span>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="password" name="mdp" id="mdp"pattern=".+$" />
                    <label class="mdl-textfield__label" for="mdp">Mot de passe:</label>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="password" name="verif" id="verif" pattern=".+$"/>
                    <label class="mdl-textfield__label" for="verif">Verification mot de passe:</label>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" name="num" id="num" pattern="^[0-9]+$"/>
                    <label class="mdl-textfield__label" for="num">Numéro de téléphone:</label>
                    <span class="mdl-textfield__error">Entrez un numéro de téléphone valide.</span>
                </div>

                <button class="mdl-button mdl-js-button mdl-js-ripple-effect submit-button" type="submit">
                    S'inscrire
                </button>

            </form>
        </div>

    </div>

    <p class="centrer_texte">Déjà un compte?<br/>
        <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="login.php">
            Se connecter
        </a>
    </p>


<?php
include('includes/footer.php');
?>