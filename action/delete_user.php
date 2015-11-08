<?php

/**
 * Range error : 40 - 49
 */

session_start();

$num_error = 0;

require __DIR__ . '/../lib/class.Database.php';


if (isset($_POST['accepte']) AND $_POST['accepte'] == true AND isset($_POST['pwd'])) { //Si les champs sont renseignés

    $reqmdp = $db->prepare('SELECT _id, mdp FROM User WHERE _id = :id');
    $reqmdp->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $reqmdp->execute();

    $mdp = sha1(sha1($_POST['pwd']) . "42jeej42");

    if ($reqmdp->rowCount() == 1) {   //Si la requète renvoie une seule ligne (celle de l'objet, il n'y a pas de raison pour que ça renvoie plus d'une ligne)

        $resmdp = $reqmdp->fetch(PDO::FETCH_OBJ);

        if (isset($_SESSION['_id']) AND $_SESSION['_id'] == $resmdp->_id) {   //si connecté et toujours sur le bon compte
            if (($mdp == $resmdp->mdp) AND ($_POST['pwd'] != "")) { //si les mots de passe concordent

                $reqencheres = $db->prepare('SELECT _id FROM Objet WHERE best_user_id = :id');
                $reqencheres->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
                $reqencheres->execute();

                if ($reqencheres->rowCount() == 0) { //si aucun objet n'est actuellement détenu par l'utilisateur connecté

                    // On essaye de modifier dans la DB
                    try {

                        $req = $db->prepare('SELECT _id FROM Objet WHERE proprio_id = :id');
                        $req->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
                        $req->execute();

                        $res = $req->fetchAll();

                        $delete = $db->prepare('DELETE FROM Objet WHERE proprio_id = :id AND is_max = 0');
                        $delete->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
                        $delete->execute();

                        foreach ($res as $key => $value) {

                            unlink(__DIR__ . "/../images/objects/" . basename($value->_id));

                        }

                        $deleteuser = $db->prepare('DELETE FROM User WHERE _id = :id');
                        $deleteuser->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
                        $deleteuser->execute();

                        // On détruit les variables de notre session
                        session_unset ();

                        // On détruit notre session
                        session_destroy ();

                    } catch (PDOException $ex) {
                        $num_error = 41; //Problème dans la base de données
                    }
                } else {
                    $num_error = 42; //L'utilisateur a une enchère en cours
                }
            } else {
                $num_error = 43; //Le mot de passe est erroné
            }
        } else {
            $num_error = 44; //L'utilisateur n'est pas ou plus connecté
        }
    } else {
        $num_error = 45; //Problème dans la base de données
    }
} else {
    $num_error = 46; // Champs non renseigné
}

if ($num_error == 0) {
    header('Location: ../index.php?success=40');
} elseif ($num_error == 44) {
    header('Location: ../login.php?error=' . $num_error);
} else {
    header('Location: ../edit_user.php?id=' . $_GET['id'] . '&error=' . $num_error);
}
