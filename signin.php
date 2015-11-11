<?php

include('includes/header.php');

$isFail = (!empty($_SESSION['errors_tmp']) AND $_SESSION['errors_tmp']['from'] == 'signin') ? $isFail = TRUE : $isFail = FALSE;
?>

    <div class="mdl-card mdl-shadow--16dp centre_card signin_card">

        <div class="mdl-card__title titre_card">
            <h1 class="mdl-card__title-text">S'inscrire</h1>
        </div>

        <form method="POST" action="action/signin.php">

            <div class="mdl-card__supporting-text form_padding">

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" name="nom" id="nom"
                           pattern="[a-zA-Zéèàîâêïäëôûöü' ]+$" <?php echo ($isFail) ? 'value="' . $_SESSION['errors_tmp']['nom'] . '"' : ''; ?>/>
                    <label class="mdl-textfield__label" for="nom">Nom:</label>
                    <span class="mdl-textfield__error">Votre nom doit uniquement contenir des lettres et espaces.</span>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" name="prenom" id="prenom"
                           pattern="[a-zA-Zéèàîâêïäëôûöü' ]+$" <?php echo ($isFail) ? 'value="' . $_SESSION['errors_tmp']['prenom'] . '"' : ''; ?>/>
                    <label class="mdl-textfield__label" for="prenom">Prénom:</label>
                    <span
                        class="mdl-textfield__error">Votre prénom doit uniquement contenir des lettres et espaces.</span>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="email" name="mail" id="mail"
                           pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]+$" <?php echo ($isFail AND isset($_SESSION['errors_tmp']['mail'])) ? 'value="' . $_SESSION['errors_tmp']['mail'] . '"' : ''; ?>/>
                    <label class="mdl-textfield__label" for="mail">Email:</label>
                    <span class="mdl-textfield__error">Entrez une adresse email valide.</span>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="password" name="mdp" id="mdp"
                           pattern="^(?=.*\d)(?=.*[a-zA-Zéèàîâêïäëôûöü' ])[0-9a-zA-Zéèàîâêïäëôûöü' ]{8,}$"/>
                    <label class="mdl-textfield__label" for="mdp">Mot de passe:</label>
                    <span class="mdl-textfield__error">Votre mot de passe doit contenir au moins 8 caractères, dont une lettre et un chiffre.</span>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="password" name="verif" id="verif"
                           pattern="^(?=.*\d)(?=.*[a-zA-Zéèàîâêïäëôûöü' ])[0-9a-zA-Zéèàîâêïäëôûöü' ]{8,}$"/>
                    <label class="mdl-textfield__label" for="verif">Verification mot de passe:</label>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" name="num" id="num"
                           pattern="^[0-9]+$" <?php echo ($isFail) ? 'value="' . $_SESSION['errors_tmp']['num'] . '"' : ''; ?>/>
                    <label class="mdl-textfield__label" for="num">Numéro de téléphone:</label>
                    <span class="mdl-textfield__error">Entrez un numéro de téléphone valide.</span>
                </div>

            </div>

            <div class="mdl-card__actions">
                <button class="mdl-button mdl-js-button mdl-js-ripple-effect submit-button" type="submit">
                    S'inscrire
                </button>
                <div class="mdl-spinner mdl-js-spinner"></div>
            </div>

        </form>

        <script>
            $(document).ready(function () {


                $("#submit_button").on('click', function () {
                    if ($("#nom").val() !== ""
                        && $("#prenom").val() !== ""
                        && $("#mdp").val() !== ""
                        && $("#verif").val() !== ""
                        && $("#num").val() !== ""
                        && $("#email").val() !== ""
                    ) {
                        event.preventDefault();
                        $(".mdl-spinner").addClass("is-active");
                        $("#submit_button").remove();
                        $('form').submit();
                    }

                });
            });
        </script>

    </div>

    <p class="centrer_texte">Vous avez déjà un compte?<br/>
        <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="login.php">
            Se connecter
        </a>
    </p>

<?php

$_SESSION['errors_tmp'] = array();

include('includes/footer.php');
?>