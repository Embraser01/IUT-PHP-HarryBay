<?php
/**
 * Created by PhpStorm.
 * User: Nicolas POURPRIX
 * Date: 03/11/2015
 * Time: 13:23
 */

session_start();

if(!isset($_GET['id'])){
    header('Location: index.php');
    exit;
}

include('includes/header.php');

if (isset($_GET['error'])) {
    echo '<p class="error"><i class="material-icons md-48">error</i><br/>';

    switch ($_GET['error']) {
        case 1:
            echo 'Une erreur est survenue dans la base de données.';
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

if ($req->rowCount()==1)
{   //si l'utilisateur existe

    $res = $req->fetch(PDO::FETCH_OBJ);

    /**
     * On vérifie si l'id du propriétaire est le même que celui de la personna actuellement connectée.
     * Si c'est pas identique, ou si la personne n'est pas connectée, l'envoyer balader.
     */

    if ((isset($_SESSION['_id']))&&($res->_id == $_SESSION['_id']))
    {
        ?>

        <div class="mdl-card mdl-shadow--16dp centre_card edit_card">

            <div class="mdl-card__title titre_card">
                <h1 class="mdl-card__title-text">Mon compte</h1>
            </div>

            <div class="mdl-card__supporting-text">

                <form enctype="multipart/form-data" method="POST" action="action/edit_user.php?id=<?php echo $_GET['id'] ?>">

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" name="nom" id="nom" value="<?php echo $res->nom ?>" pattern="?+$"/>
                        <label class="mdl-textfield__label" for="nom">Nom</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" name="prenom" id="prenom" value="<?php echo $res->prenom ?>" pattern="?+$"/>
                        <label class="mdl-textfield__label" for="prenom">Prénom</label>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" name="mail" id="mail" value="<?php echo $res->mail ?>" pattern="?+$"/>
                        <label class="mdl-textfield__label" for="mail">Adresse email</label>
                        <span class="mdl-textfield__error">Entrez une adresse email valide.</span>
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" name="num" id="num" value="<?php echo $res->num_tel ?>" pattern="^0[0-9]{9}$"/>
                        <label class="mdl-textfield__label" for="num">Numéro de téléphone:</label>
                        <span class="mdl-textfield__error">Entrez un numéro de téléphone valide.</span>
                    </div>

                    <div class="mdl-card__title">
                        Pour valider vos changements, entrez votre mot de passe.
                    </div>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="password" name="pwd" id="pwd" pattern=".+$"/>
                        <label class="mdl-textfield__label" for="pwd">Mot de passe:</label>
                    </div>

                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect submit-button" type="submit">
                        <i class="material-icons">done</i> Valider
                    </button>

                </form>
            </div>

        </div>

        <!--Carte pour supprimer l'objet-->

        <div class="mdl-card mdl-shadow--16dp centre_card delete_card">

            <div class="mdl-card__title titre_card">
                <!--<i class="material-icons" style="margin-right: 15px;">delete</i> Supprimer l'enchère-->
                <div class="mdl-card__title-text centrer_texte">
                    Modifier le mot de passe
                </div>
            </div>

            <div class="mdl-card__supporting-text centrer_texte">

                <i class="material-icons">edit</i><br/>
                work in progress...

            </div>

        </div>

        <?php
    }
    else
    {
        header('Location: objects.php?error=7');    //pas connecté
        exit;
    }
}

?>