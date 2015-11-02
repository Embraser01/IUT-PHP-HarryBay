<?php
/**
 * Created by PhpStorm.
 * User: nicolai
 * Date: 29/10/2015
 * Time: 18:56
 */

include('includes/header.php');

if (isset($_GET['error'])) {
    echo '<p class="error"><i class="material-icons md-48">error</i><br/>';

    switch ($_GET['error']) {
        case 1:
            echo 'Une erreur est survenue dans la base de données.';
            break;
        case 2:
            echo 'La date de suppression de l\'enchère est incorrecte. Elle doit être dans le futur, non de Zeus!';
            break;
        case 3:
            echo 'Veuillez renseigner tous les champs.';
            break;
        case 4:
            echo 'Vous devez <a href="login.php">vous connecter</a> pour modifier un objet.';
            break;
        case 13:
            echo 'Le mot de passe est erroné. L\'objet n\'a pas été supprimé.';
            break;
        default:
            echo 'Un problème est survenu.';
    }

    echo '</p><hr>';
}

//require __DIR__ . '/lib/class.Database.php';

//On prépare la requète et on l'execute
$req = $db->prepare('SELECT Objet.`_id`, Objet.nom AS `desc`, Objet.prix_min, Objet.prix_now, Objet.date_start, Objet.date_stop, Objet.proprio_id,Objet.best_user_id, User._id AS `userid` FROM Objet JOIN User ON Objet.proprio_id = User._id WHERE Objet.`_id` = :id');
$req->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
$req->execute();

if ($req->rowCount()==1)
{

    //fetch la ligne
    $res = $req->fetch(PDO::FETCH_OBJ);


    $online;

    if ($res->date_start <= date("Y-m-d"))
    {
        $online = 1;
    }
    else
    {
        $online = 0;
    }

    $price_changed;

    if ($res->best_user_id != PDO::PARAM_NULL)
    {
        $price_changed = 1;
    }
    else
    {
        $price_changed = 0;
    }

    /**
     * On vérifie si l'id du propriétaire est le même que celui de la personna actuellement connectée.
     * Si c'est pas identique, ou si la personne n'est pas connectée, l'envoyer balader.
     */

    if ((isset($_SESSION['_id']))&&($res->userid == $_SESSION['_id']))
    {
        ?>

        <div class="mdl-card mdl-shadow--16dp centre_card edit_card">

            <div class="mdl-card__title titre_card" style="background: linear-gradient( rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.25) ),url('./images/get_obj_img.php?id=<?php echo $_GET['id'] ?>');">
                <h1 class="mdl-card__title-text">Modifier un objet</h1>
            </div>

            <div class="mdl-card__supporting-text">

                <form enctype="multipart/form-data" method="POST" action="action/edit_object.php?id=<?php echo $_GET['id']."&online=".$online."&price_changed=".$price_changed ?>">

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="text" name="nom" id="nom" value="<?php echo $res->desc ?>" pattern="?+$"/>
                        <label class="mdl-textfield__label" for="nom">Nom</label>
                    </div>

                    <!--<div id="photo-form-file-group">
                        File label
                        <div class="mdl-textfield mdl-js-textfield">
                            <input id="photo-label-input" class="mdl-textfield__input" disabled/>
                            <label class="mdl-textfield__label" for="photo-label-input" id="photo-label">Vous ne pouvez pas changer de photo.</label>
                        </div>

                        Upload button

                        <div class="mdl-button mdl-js-button mdl-button--raised fileUpload">
                            <span>Choisir le fichier</span>
                            <input type="file" name="img" id="img" class="upload" required/>
                        </div>
                    </div>-->

                    <?php

                    if ($res->best_user_id == PDO::PARAM_NULL)
                    {
                        //Affichage du prix minimum si aucune enchère n'a été faite.
                        ?>

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="number" name="prix_min" id="prix_min" value="<?php echo $res->prix_min ?>" required/>
                            <label class="mdl-textfield__label" for="prix_min">Prix minimum</label>
                        </div>

                        <?php

                    }
                    else
                    {
                        //Affichage du prix minimum si une enchère a été faite sur cet objet
                        ?>

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input no-interaction" type="number" name="prix_min" id="prix_min" value="<?php echo $res->prix_min ?>" readonly="true"/>
                            <label class="mdl-textfield__label" for="prix_min">Prix minimum (prix actuel: <?php echo $res->prix_now ?> €)</label>
                            Vous ne pouvez pas modifier le prix minimum, car une enchère a déjà été proposée.
                        </div>

                        <?php
                    }

                    if ($res->date_start <= date("Y-m-d"))
                    {
                        //Affichage de la date de début mais sans possibilité de la modifier
                        ?>

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input no-interaction" type="date" name="date_start" id="date_start" value="<?php echo $res->date_start ?>" readonly="true"/>
                            <label class="mdl-textfield__label" for="date_start">Date de mise en ligne de l'enchère.</label>
                            <span>Vous ne pouvez pas modifier la date de mise en ligne d'une enchère déjà parue.</span>
                        </div>

                        <?php
                    }
                    else
                    {
                        ?>

                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="date" name="date_start" id="date_start" value="<?php echo $res->date_start ?>" required/>
                            <label class="mdl-textfield__label" for="date_start">Date de mise en ligne de l'enchère</label>
                        </div>

                        <?php
                    }

                    ?>

                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="date" name="date_stop" id="date_stop" value="<?php echo $res->date_stop ?>" required/>
                        <label class="mdl-textfield__label" for="date_stop">Date de fin de l'enchère</label>
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
                    Supprimer cet objet
                </div>
            </div>

            <div class="mdl-card__supporting-text">
                <!--
                <i class="material-icons">edit</i><br/>
                work in progress...
                -->

                <form method="POST" action="action/delete_object.php?id=<?php echo $res->_id; ?>">
                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                        <input class="mdl-textfield__input" type="password" name="pwd" id="pwd" pattern=".+$"/>
                        <label class="mdl-textfield__label" for="pwd">Mot de passe:</label>
                    </div>
                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect submit-button" type="submit">
                        Wingardium supprimssah!
                    </button>
                </form>

            </div>

        </div>

        <?php
    }
    else
    {
        header('location: objects.php?error=7');    //pas connecté
        exit;
    }

}

?>