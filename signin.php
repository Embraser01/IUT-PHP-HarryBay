<?php
/**
 * Created by PhpStorm.
 * User: Marc-Antoine
 * Date: 06/10/2015
 * Time: 14:38
 */

include('includes/header.php');
?>

    <div class="mdl-card mdl-shadow--16dp centre_card signin_card">

        <div class="mdl-card__title titre_card">
            <h1 class="mdl-card__title-text">S'inscrire</h1>
        </div>

        <div class="mdl-card__supporting-text">

            <form method="POST" action="action/signin.php">

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" name="nom" id="nom" pattern="[a-zA-Zéèàîâêïäëôûöü' ]+$"/>
                    <label class="mdl-textfield__label" for="nom">Nom:</label>
                    <span class="mdl-textfield__error">Votre nom doit uniquement contenir des lettres et espaces.</span>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" name="prenom" id="prenom" pattern="[a-zA-Zéèàîâêïäëôûöü' ]+$"/>
                    <label class="mdl-textfield__label" for="prenom">Prénom:</label>
                    <span class="mdl-textfield__error">Votre prénom doit uniquement contenir des lettres et espaces.</span>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="email" name="mail" id="mail" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]+$"/>
                    <label class="mdl-textfield__label" for="mail">Email:</label>
                    <span class="mdl-textfield__error">Entrez une adresse email valide.</span>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="password" name="mdp" id="mdp" pattern="^(?=.*\d)(?=.*[a-zA-Z])[0-9a-zA-Z]{8,}$" />
                    <label class="mdl-textfield__label" for="mdp">Mot de passe:</label>
                    <span class="mdl-textfield__error">Votre mot de passe doit contenir au moins 8 caractères, dont une lettre et un chiffre.</span>
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

                <div class="mdl-spinner mdl-js-spinner"></div>

            </form>
        </div>

    </div>

    <p class="centrer_texte">Vous avez déjà un compte?<br/>
        <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="login.php">
            Se connecter
        </a>
    </p>


<?php
include('includes/footer.php');
?>