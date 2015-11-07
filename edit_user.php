<?php
/**
 * Created by PhpStorm.
 * User: Nicolas POURPRIX
 * Date: 03/11/2015
 * Time: 13:23
 */

session_start();

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

include('includes/header.php');

if (isset($_GET['error'])) {
    echo '<p class="error"><i class="material-icons md-48">error</i><br/>';

    switch ($_GET['error']) {
        case 1:
            echo 'Un problème est survenu dans la base de données';
            break;
        case 2:
            echo 'Le mot de passe est erroné';
            break;
        case 3:
            echo 'Le compte que vous essayez de modifier n\'existe pas.';
            break;
        case 4:
            echo 'Les champs ne sont pas tous renseignés';
            break;
        case 5:
            echo 'Vous ne pouvez modifier que votre propre compte.';
            break;
        case 6:
            echo 'Vous ne pouvez pas supprimer votre compte pour le moment, car il y a un objet dont vous êtes le meilleur enchérisseur.';
            break;
        case 12:
            echo 'Le mot de passe actuel est incorrect.';
            break;
        case 14:
            echo 'Les nouveaux mots de passe ne sont pas identiques.';
            break;
        default:
            echo 'Un problème est survenu.';
    }

    echo '</p><hr>';

}


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

    if ((isset($_SESSION['_id'])) && ($res->_id == $_SESSION['_id'])) {
        ?>

        <div class="titre_page">
            Configuration du compte
        </div>

        <div class="list_card_wrapper">

            <div class="mdl-card mdl-shadow--16dp list_card" id="edit_card">

                <div class="mdl-card__title titre_card mdl-button mdl-js-button mdl-js-ripple-effect">
                    <h1 class="mdl-card__title-text">Mon compte</h1>
                </div>

                <div class="mdl-card__supporting-text">

                    <form enctype="multipart/form-data" method="POST"
                          action="action/edit_user.php?id=<?php echo $_GET['id'] ?>">

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

                        <div class="mdl-card__title">
                            Pour valider vos changements, entrez votre mot de passe.
                        </div>

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="password" name="mdp" id="mdp" pattern=".+$"/>
                            <label class="mdl-textfield__label" for="mdp">Mot de passe:</label>
                        </div>

                        <br>

                        <button class="mdl-button mdl-js-button mdl-js-ripple-effect submit-button" type="submit">
                            <i class="material-icons">done</i> Valider
                        </button>

                    </form>
                </div>

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
                        cours et en attente seront supprimées. Si vous avez des enchères en cours, celles-ci seront
                        annulées. Cependant, si vous étiez le propriétaire de la meilleure vente affichée en page
                        d'accueil, celle-ci ne sera pas retirée de la base.
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

        <?php
    } else {
        header('Location: objects.php?error=7');    //pas connecté
        exit;
    }
}

?>