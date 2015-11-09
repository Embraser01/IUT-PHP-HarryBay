<?php

session_start();

if (!isset($_GET['id'])) {
    header('Location: index.php?error=-1');
    exit;
}

if (!isset($_SESSION['mail'])) {
    header('Location: login.php?error=76');
    exit;
}

include('includes/header.php');


//On prépare la requète et on l'execute
$req = $db->prepare('SELECT _id, nom, prenom, mail, num_tel, mdp FROM User WHERE User.`_id` = :id');
$req->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
$req->execute();

if ($req->rowCount() == 1) {   //si l'utilisateur existe

    $res = $req->fetch(PDO::FETCH_OBJ);

    /**
     * On vérifie si l'id du propriétaire est le même que celui de la personna actuellement connectée.
     * Si c'est pas identique, ou si la personne n'est pas connectée, l'envoyer balader.
     */

    if ($res->_id == $_SESSION['_id']) {
        ?>

        <div class="titre_page">
            Configuration du compte
        </div>

        <div class="list_card_wrapper">

            <div class="mdl-card mdl-shadow--16dp list_card" id="edit_card">

                <div class="mdl-card__title titre_card mdl-button mdl-js-button mdl-js-ripple-effect">
                    <h1 class="mdl-card__title-text">Mon compte</h1>
                </div>

                <form enctype="multipart/form-data" method="POST"
                      action="action/edit_user.php?id=<?php echo $_GET['id'] ?>">

                    <div class="mdl-card__supporting-text">

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" name="nom" id="nom"
                                   value="<?php echo $res->nom ?>" pattern="?+$"/>
                            <label class="mdl-textfield__label" for="nom">Nom</label>
                        </div>

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" name="prenom" id="prenom"
                                   value="<?php echo $res->prenom ?>" pattern="?+$"/>
                            <label class="mdl-textfield__label" for="prenom">Prénom</label>
                        </div>

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" name="mail" id="mail"
                                   value="<?php echo $res->mail ?>" pattern="?+$"/>
                            <label class="mdl-textfield__label" for="mail">Adresse email</label>
                            <span class="mdl-textfield__error">Entrez une adresse email valide.</span>
                        </div>

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" name="num" id="num"
                                   value="<?php echo $res->num_tel ?>" pattern="^0[0-9]{9}$"/>
                            <label class="mdl-textfield__label" for="num">Numéro de téléphone:</label>
                            <span class="mdl-textfield__error">Entrez un numéro de téléphone valide.</span>
                        </div>

                        Pour valider vos changements, entrez votre mot de passe.

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="password" name="mdp" id="mdp" pattern=".+$"/>
                            <label class="mdl-textfield__label" for="mdp">Mot de passe:</label>
                        </div>

                    </div>

                    <div class="mdl-card__actions">

                        <button class="mdl-button mdl-js-button mdl-js-ripple-effect submit-button" type="submit">
                            <i class="material-icons">done</i> Valider
                        </button>

                    </div>

                </form>

            </div>

            <!--Carte pour supprimer l'objet-->

            <div class="mdl-card mdl-shadow--16dp list_card" id="password_card">

                <div class="mdl-card__title titre_card mdl-button mdl-js-button mdl-js-ripple-effect">
                    <!--<i class="material-icons" style="margin-right: 15px;">delete</i> Supprimer l'enchère-->
                    <div class="mdl-card__title-text centrer_texte">
                        Modifier le mot de passe
                    </div>
                </div>

                <div class="mdl-card__supporting-text">

                    <form enctype="multipart/form-data" method="POST"
                          action="action/edit_password.php?id=<?php echo $_GET['id'] ?>">

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="password" name="oldpwd" id="oldpwd"
                                   pattern=".+$"/>
                            <label class="mdl-textfield__label" for="oldpwd">Mot de passe actuel:</label>
                            <span class="mdl-textfield__error">Entrez un numéro de téléphone valide.</span>
                        </div>

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="password" name="newpwd" id="newpwd"
                                   pattern="^(?=.*\d)(?=.*[a-zA-Z])[0-9a-zA-Z]{8,}$"/>
                            <label class="mdl-textfield__label" for="newpwd">Nouveau mot de passe:</label>
                            <span
                                class="mdl-textfield__error">8 caractères minimum, dont une lettre et un chiffre.</span>
                        </div>

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="password" name="newpwdverif" id="newpwdverif"
                                   pattern="^(?=.*\d)(?=.*[a-zA-Z])[0-9a-zA-Z]{8,}$"/>
                            <label class="mdl-textfield__label" for="newpwdverif">Confirmation du nouveau mot de
                                passe:</label>
                            <span class="mdl-textfield__error">Les mots de passe ne sont pas identiques.</span>
                        </div>

                        <br>

                        <button class="mdl-button mdl-js-button mdl-js-ripple-effect submit-button" type="submit">
                            <i class="material-icons">done</i> Valider
                        </button>

                    </form>

                </div>

            </div>

            <div class="mdl-card mdl-shadow--16dp list_card" id="delete_user_card">

                <div class="mdl-card__title titre_card mdl-button mdl-js-button mdl-js-ripple-effect">
                    <!--<i class="material-icons" style="margin-right: 15px;">delete</i> Supprimer l'enchère-->
                    <div class="mdl-card__title-text centrer_texte">
                        Supprimer le compte
                    </div>
                </div>

                <div class="mdl-card__supporting-text">

                    <div class="red">
                        Si vous supprimez votre compte, toutes vos données seront effacées du serveur. Vos annonces en
                        cours et en attente seront supprimées. Cependant, si vous êtes actuellement le meilleur
                        enchérisseur sur un objet, vous ne pourrez pas supprimer votre compte tant que personne n'a
                        proposé une meilleure enchère. De plus, si vous étiez le propriétaire de la meilleure vente
                        affichée en page d'accueil, celle-ci ne sera pas retirée de la base.
                        <br><br>
                        C'était sympa. Bye bye!
                        <br><br>
                        - L'équipe
                    </div>
                    <br>

                    <form enctype="multipart/form-data" method="POST"
                          action="action/delete_user.php?id=<?php echo $_GET['id'] ?>">

                        <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect"
                               for="accepte">
                            <input type="checkbox" id="accepte" class="mdl-checkbox__input" name="accepte">
                            <span class="mdl-checkbox__label">J'accepte les termes ci-dessus.</span>
                        </label>

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="password" name="pwd" id="pwd"
                                   pattern="^(?=.*\d)(?=.*[a-zA-Z])[0-9a-zA-Z]{8,}$"/>
                            <label class="mdl-textfield__label" for="pwd">Mot de passe:</label>
                        </div>

                        <br>

                        <button class="mdl-button mdl-js-button mdl-js-ripple-effect submit-button" type="submit">
                            <i class="material-icons">clear</i> DROP ACCOUNT IF EXISTS!
                        </button>

                    </form>

                </div>

            </div>

        </div>
        <script src="js/application.js"></script>

        <?php
    } else {
        header('Location: objects.php?error=73');
        exit;
    }
} else {
    header('Location: objects.php?error=73');
    exit;
}
include('includes/footer.php');
?>