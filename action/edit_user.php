<?php
/**
 * Created by PhpStorm.
 * User: Nicolas POURPRIX
 * Date: 04/11/2015
 * Time: 20:12
 */

session_start();

$num_error = 0;

//PREMIERE CONDITION A SEPARER!
if (isset($_SESSION['_id'])){ //Si l'utilisateur est connecté
    if ($_SESSION['_id'] == $_GET['id']) {  //si le compte concerné correspond bien au compte actuellement connecté
        if (isset($_POST['nom'])
            AND isset($_POST['prenom'])
            AND isset($_POST['num'])
            AND isset($_POST['mail'])
            AND isset($_POST['mdp'])
            AND $_POST['nom'] != ""
            AND $_POST['prenom'] != ""
            AND $_POST['num'] != ""
            AND $_POST['mail'] != ""
            AND $_POST['mdp'] != ""
        ) { // Si tous les champs sont renseignés

            $getmdp;
            $req;

            require __DIR__ . '/../lib/class.Database.php';

            try {
                $getmdp = $db->prepare('SELECT mdp FROM User WHERE User.`_id` = :id');
                $getmdp->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
                //$getmdp->bindValue(':nom', sha1(sha1($_POST['mdp']) . '42jeej42'), PDO::PARAM_STR);
                $mdp = sha1(sha1($_POST['mdp']) . '42jeej42');
            } catch (PDOException $exmdp) {
                $num_error = 1;
                echo $exmdp;
            }

            try {
                $req = $db->prepare('UPDATE User SET nom = :nom, prenom = :prenom, num_tel = :num, mail = :mail WHERE User.`_id` = :id');
                $req->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
                $req->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
                $req->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
                $req->bindValue(':num', $_POST['num'], PDO::PARAM_STR);
                $req->bindValue(':mail', $_POST['mail'], PDO::PARAM_STR);
                $req->execute();
            } catch (PDOException $ex) {
                // Reste sur la page et affiche l'erreur;
                $num_error = 1;
                echo $ex;
            }

            /*if ($_POST['date_start'] >= date("Y-m-d") OR $_GET['online'] == 1)
            { //si la date de mise en ligne n'est pas dans le passé

                if ($_POST['date_stop'] > date("Y-m-d") AND strtotime($_POST['date_start']) < strtotime($_POST['date_stop']))
                { //si la date de fin est bien dans le futur et postérieure à la date de mise en ligne

                    require __DIR__ . '/../lib/class.Database.php';

                    // On essaye de modifier dans la DB

                    try {

                        $req->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
                        $req->bindValue(':date_start', $_POST['date_start'], PDO::PARAM_STR);
                        $req->bindValue(':date_stop', $_POST['date_stop'], PDO::PARAM_STR);
                        $req->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
                        //$req->bindValue(':best_user_id', $_SESSION['_id'], PDO::PARAM_NULL);
                        $req->execute();

                    } catch (PDOException $ex) {
                        // Reste sur la page et affiche l'erreur;
                        $num_error = 1;
                        echo $ex;
                    }
                }
                else
                {
                    $num_error = 2; //La date de fin n'est pas correcte
                }
            } else {
                $num_error = 3; //La date de mise en ligne n'est pas correcte
            }*/
        } else {
            $num_error = 3; //Les champs ne sont pas tous renseignés
        }
    }else{
        $num_error = 0; //Le compte à modifier n'est pas le compte actuellement utilisé
    }
} else {
    $num_error = 4; //L'utilisateur n'est pas ou plus connecté
}

if ($num_error == 0) {
    header('Location: ../user_objects.php?page=1&success=2');
} elseif ($num_error == 4) {
    header('Location: ../login.php?error=4');
} else {
    header('Location: ../edit_object.php?id=' . $_GET['id'] . '&page=1&error=' . $num_error);
}