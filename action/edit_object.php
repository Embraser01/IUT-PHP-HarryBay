<?php
/**
 * Created by PhpStorm.
 * User: nicolai
 * Date: 29/10/2015
 * Time: 22:49
 */

session_start();

$num_error = 0;

if (isset($_SESSION['mail'])) {
    if (isset($_POST['nom'])
        AND isset($_POST['prix_min'])
        AND isset($_POST['date_start'])
        AND isset($_POST['date_stop'])
        AND $_POST['nom'] != ""
        AND $_POST['prix_min'] != ""
        AND $_POST['date_start'] != ""
        AND $_POST['date_stop'] != ""
    ) { // Si tous les champs sont renseignés + utilisateur connecté

        if ($_POST['date_start'] >= date("Y-m-d") OR $_GET['online'] == 1)
        { //si la date de mise en ligne n'est pas dans le passé

            if ($_POST['date_stop'] > date("Y-m-d") AND strtotime($_POST['date_start']) < strtotime($_POST['date_stop']))
            { //si la date de fin est bien dans le futur et postérieure à la date de mise en ligne

                require __DIR__ . '/../lib/class.Database.php';

                // On essaye de modifier dans la DB

                try {

                    $req;

                    if ($_GET['price_changed'] == 0)
                    {   //si le prix n'a changé (pas d'enchère), la requète modifiera le prix actuel pour le mettre au même niveau que le prix minimum
                        $req = $db->prepare('UPDATE Objet SET nom = :nom, date_start= :date_start, date_stop = :date_stop, prix_min = :prix_min, prix_now = :prix_min/*, best_user_id = :best_user_id*/ WHERE Objet.`_id` = :id');
                        $req->bindValue(':prix_min', $_POST['prix_min'], PDO::PARAM_STR);
                    }
                    else
                    {
                        $req = $db->prepare('UPDATE Objet SET nom = :nom, date_start= :date_start, date_stop = :date_stop/*, best_user_id = :best_user_id*/ WHERE Objet.`_id` = :id');
                    }

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
        }
    } else {
        $num_error = 3; //Les champs ne sont pas tous renseignés
    }
} else {
    $num_error = 4; //L'utilisateur n'est pas ou plus connecté
}

if ($num_error == 0) {
    header('Location: ../objects.php?page=1&success=2');
} elseif ($num_error == 4) {
    header('Location: ../login.php?error=4');
} else {
    header('Location: ../edit_object.php?id='.$_GET['id'].'&page=1&error=' . $num_error);
}
