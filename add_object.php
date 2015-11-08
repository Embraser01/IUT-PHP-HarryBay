<?php
session_start();

if (!isset($_SESSION['mail'])) {
    header('Location: login.php');
    exit;
}

include('includes/header.php');
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
                    <input class="mdl-textfield__input" type="text" name="prix_min" id="prix_min"
                           pattern="^\d{1,6}((,|\.)\d{1,2})?$"/>
                    <label class="mdl-textfield__label" for="prix_min">Prix minimum</label>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="date" name="date_start" id="date_start"
                           pattern="(([0-2][0-9]|3[0-1])/(0[0-9]|1[0-2])/20[1-3][0-9])|(20[1-3][0-9]-(0[0-9]|1[0-2])-([0-2][0-9]|3[0-1]))"
                           value="<?php echo date("Y-m-d") ?>"/>
                    <label class="mdl-textfield__label" for="date_start">Date de mise en ligne de l'enchère
                        (jj/mm/yyyy)</label>
                </div>

                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="date" name="date_stop" id="date_stop"
                           pattern="(([0-2][0-9]|3[0-1])/(0[0-9]|1[0-2])/20[1-3][0-9])|(20[1-3][0-9]-(0[0-9]|1[0-2])-([0-2][0-9]|3[0-1]))"
                           value="<?php echo date("Y-m-d", strtotime("+1 week")) ?>"/>
                    <label class="mdl-textfield__label" for="date_stop">Date de fin de l'enchère (jj/mm/yyyy)</label>
                </div>

                <button
                    class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--raised mdl-button--accent submit-button"
                    id="submit_button" type="submit">
                    Valider
                </button>

                <div class="mdl-spinner mdl-js-spinner"></div>

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