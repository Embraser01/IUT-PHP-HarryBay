<?php
session_start();

if (!isset($_SESSION['mail'])) {
    header('Location: login.php');
    exit;
}

include('includes/header.php');

//Affichage de l'éventuel message d'erreur

if (isset($_GET['error'])) {
    echo '<p class="error"><i class="material-icons md-48">error</i><br/>';

    switch ($_GET['error']) {
        case 2:
            echo 'L\'image que vous avez envoyé n\'est pas valide (extension, poids ou erreur d\'envoi).';
            break;
        case 3:
            echo 'Les informations que vous avez envoyé ne sont pas correctes.';
            break;
        case 4:
            echo 'Vous devez <a href="login.php">vous connecter</a> pour proposer une enchère.';
            break;
        default:
            echo 'Un problème est survenu.';
    }

    echo '</p><hr>';
}
?>

    <div class="mdl-card mdl-shadow--16dp centre_card">

        <div class="mdl-card__title titre_card">
            <h1 class="mdl-card__title-text">Ajouter un objet</h1>
        </div>

        <div class="mdl-card__supporting-text">

            <form enctype="multipart/form-data" method="POST" action="action/add_object.php">

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" name="nom" id="nom" pattern=".+$"/>
                    <label class="mdl-textfield__label" for="nom">Nom</label>
                </div>

                <div id="photo-form-file-group">
                    <!-- File label -->
                    <div class="mdl-textfield mdl-js-textfield">
                        <input id="photo-label-input" class="mdl-textfield__input" disabled/>
                        <label class="mdl-textfield__label" for="photo-label-input" id="photo-label">Choisissez
                            une
                            photo</label>
                    </div>

                    <!-- Upload button -->

                    <div class="mdl-button mdl-js-button mdl-button--raised fileUpload">
                        <span>Choisir le fichier</span>
                        <input type="file" name="img" id="img" class="upload" required/>
                    </div>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" name="prix_min" id="prix_min" pattern="^\d+((,|\.)\d{1,2})?$"/>
                    <label class="mdl-textfield__label" for="prix_min">Prix minimum</label>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="date" name="date_start" id="date_start" pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}" value="<?php echo date("Y-m-d") ?>"/>
                    <label class="mdl-textfield__label" for="date_start">Date de mise en ligne de l'enchère (jj/mm/yyyy)</label>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="date" name="date_stop" id="date_stop" pattern="[0-9]{2}/[0-9]{2}/[0-9]{4}" value="<?php echo date("d/m/Y", strtotime("+1 week")) ?>"/>
                    <label class="mdl-textfield__label" for="date_stop">Date de fin de l'enchère (jj/mm/yyyy)</label>
                </div>

                <button class="mdl-button mdl-js-button mdl-js-ripple-effect submit-button" type="submit">
                    Valider
                </button>

            </form>
        </div>

    </div>

    <script>
        /*======== FILE LABEL UPDATE ========*/

        document.getElementById('img').onchange = function () {
            if (this.value != null) {
                document.getElementById('photo-label').innerHTML = this.value.split(/[\\/]/).pop();
            }
        };
    </script>

<?php

include('includes/footer.php');

?>