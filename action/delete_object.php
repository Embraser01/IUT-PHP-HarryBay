<?php

/**
 * Range error : 30 - 39
 */

session_start();

$num_error = 0;

if (isset($_SESSION['mail'])) {
    if (isset($_GET['id']) AND isset($_POST['pwd'])) {
        
        require __DIR__ . '/../lib/class.Database.php';


        $reqmdp = $db->prepare('SELECT User.mdp, Objet.is_max, Objet.proprio_id FROM Objet JOIN User ON Objet.proprio_id = User.`_id` WHERE Objet.`_id` = :id');
        $reqmdp->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        $reqmdp->execute();

        $mdp = sha1(sha1($_POST['pwd']) . "42jeej42");

        if ($reqmdp->rowCount() == 1) {   //Si la requète renvoie une seule ligne (celle de l'objet, il n'y a pas de raison pour que ça renvoie plus d'une ligne)

            $resmdp = $reqmdp->fetch(PDO::FETCH_OBJ);


            if (($mdp == $resmdp->mdp) AND ($_POST['pwd'] != "") AND ($_SESSION['_id'] == $resmdp->proprio_id)) { //si les mots de passe concordent

                if ($resmdp->is_max == false) { //si l'objet n'est pas le plus vendu

                    // On essaye de modifier dans la DB
                    try {

                        $req = $db->prepare('DELETE FROM Objet WHERE `_id` = :id');
                        $req->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
                        $req->execute();

                        unlink(__DIR__ . "/../images/objects/" . basename($_GET['id'] . '.jpg'));

                    } catch (PDOException $ex) {
                        $num_error = 31; //Problème dans la base de données
                    }
                } else {
                    $num_error = 32; //L'objet concerné est utilisé sur la page d'accueil
                }
            } else {
                $num_error = 33; //Le mot de passe est erroné
            }
        } else {
            $num_error = 34; // Aucun objet de se nom là
        }
    } else {
        $num_error = 35; // Infos
    }
} else {
    $num_error = 36; //L'utilisateur n'est pas ou plus connecté
}

if ($num_error == 0) {
    header('Location: ../objects.php?page=1&success=30');
} elseif ($num_error == 33 || $num_error == 35) {
    header('Location: ../edit_object.php?id=' . $_GET['id'] . '&error=' . $num_error);
} else {
    header('Location: ../objects.php?id=' . $_GET['id'] . '&page=1&error=' . $num_error);
}
