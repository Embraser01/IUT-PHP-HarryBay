<?php

/**
 * Range error : 00 - 09
 */

session_start();

$num_error = 0;

function upload($index, $maxsize = FALSE, $extensions = FALSE) {
    //Test1: fichier correctement uploadé
    if (!isset($_FILES[$index]) OR $_FILES[$index]['error'] > 0) return FALSE;
    //Test2: taille limite
    if ($maxsize !== FALSE AND $_FILES[$index]['size'] > $maxsize) return FALSE;
    //Test3: extension
    $ext = substr(strrchr($_FILES[$index]['name'], '.'), 1);
    if ($extensions !== FALSE AND !in_array($ext, $extensions)) return FALSE;
    //Déplacement
    return TRUE;
}

if (isset($_SESSION['mail'])) { //si connecté
    if (isset($_POST['nom'])
        AND isset($_FILES['img'])
        AND isset($_POST['prix_min'])
        AND isset($_POST['date_start'])
        AND isset($_POST['date_stop'])
        AND $_POST['nom'] != ""
        AND $_POST['prix_min'] != ""
        AND $_POST['date_start'] != ""
        AND $_POST['date_stop'] != ""
    ) { //si tous les champs sont renseignés

        if ($_POST['date_start'] >= date("Y-m-d")
            AND $_POST['date_stop'] > date("Y-m-d")
            AND strtotime($_POST['date_start']) < strtotime($_POST['date_stop'])
        ) {


            if (upload('img', FALSE, array('png', 'PNG', 'JPEG', 'jpg', 'JPG', 'jpeg', 'bmp', 'BMP', 'GIF', 'gif'))) {

                require __DIR__ . '/../lib/class.Database.php';

                // On essaye d'inserer dans la DB

                try {
                    $req = $db->prepare('INSERT INTO Objet(nom, prix_min, date_start, date_stop, prix_now, is_max, proprio_id) VALUE (:nom, :prix_min, :date_start, :date_stop, :prix_now, :is_max, :proprio_id)');

                    $req->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
                    $req->bindValue(':prix_min', $_POST['prix_min'], PDO::PARAM_STR);
                    $req->bindValue(':date_start', $_POST['date_start'], PDO::PARAM_STR);
                    $req->bindValue(':date_stop', $_POST['date_stop'], PDO::PARAM_STR);
                    $req->bindValue(':prix_now', $_POST['prix_min'], PDO::PARAM_STR);
                    $req->bindValue(':is_max', FALSE, PDO::PARAM_BOOL);
                    $req->bindValue(':proprio_id', $_SESSION['_id'], PDO::PARAM_INT);
                    $req->execute();

                    move_uploaded_file($_FILES['img']['tmp_name'], __DIR__ . "/../images/objects/" . $db->lastInsertId());
                } catch (PDOException $ex) {
                    $num_error = 01;
                }
            } else {
                $num_error = 02;
                $_SESSION['errors_tmp'] = array('from' => 'add_object',
                    'nom' => $_POST['nom'],
                    'prix_min' => $_POST['prix_min'],
                    'date_start' => $_POST['date_start'],
                    'date_stop' => $_POST['date_stop']);
            }
        } else {
            $num_error = 03;
            $_SESSION['errors_tmp'] = array('from' => 'add_object',
                'nom' => $_POST['nom'],
                'prix_min' => $_POST['prix_min'],
                'date_start' => $_POST['date_start'],
                'date_stop' => $_POST['date_stop']);
        }
    } else {
        $num_error = 03;
    }
} else {
    $num_error = 04;
}

if ($num_error == 0) {
    header('Location: ../objects.php?page=1&success=00');
} else {
    header('Location: ../add_object.php?page=1&error=' . $num_error);
}
