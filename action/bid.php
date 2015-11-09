<?php

/**
 * Range error : 10 - 19
 */

session_start();

$num_error = 0;

if (isset($_SESSION['mail']) AND isset($_GET['id']) AND isset($_POST['prix'])) {

    require __DIR__ . '/../lib/class.Database.php';

    // On vérifie que l'objet existe

    $req = $db->prepare('SELECT _id, prix_now, proprio_id FROM Objet WHERE _id=:id AND date_start <= NOW() AND date_stop >= NOW()');
    $req->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $req->execute();

    if ($req->rowCount() >= 1) { // L'objet existe
        $res = $req->fetch(PDO::FETCH_BOTH);

        if ($res[1] < floatval($_POST['prix'])) {
            if ($_SESSION['bid_count'] < 4) {
                if ($_SESSION['_id'] != $res[2]) {
                    try {
                        $req = $db->prepare('UPDATE Objet SET prix_now=:prix, best_user_id=:uid WHERE `_id`=:id');

                        $req->bindValue(':prix', $_POST['prix'], PDO::PARAM_STR);
                        $req->bindValue(':uid', $_SESSION['_id'], PDO::PARAM_INT);
                        $req->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
                        $req->execute();

                        $_SESSION['bid_count'] += 1;
                    } catch (PDOException $ex) {
                        $num_error = 11;
                    }
                } else {
                    $num_error = 12;
                }
            } else { // Limite de bid atteinte
                $num_error = 13;
            }
        } else { // Prix inférieur
            $num_error = 14;
        }
    } else { // L'objet n'existe pas
        $num_error = 15;
    }
} else { // Pas les bons paramètres
    $num_error = 16;
}
if ($num_error == 0)
    header('Location: ../objects.php?page=1&success=10');
else
    header('Location: ../objects.php?page=1&error=' . $num_error);