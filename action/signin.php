<?php

/**
 * Range error : 90 - 99
 */

session_start();

$num_error = 0;

if (isset($_POST['mail'])
    AND isset($_POST['nom'])
    AND isset($_POST['prenom'])
    AND isset($_POST['num'])
    AND isset($_POST['mdp'])
    AND isset($_POST['verif'])
    AND $_POST['mail'] != ""
    AND $_POST['nom'] != ""
    AND $_POST['prenom'] != ""
    AND $_POST['num'] != ""
    AND $_POST['mdp'] != ""
    AND $_POST['verif'] != "") { // Toutes les infos

    if (!isset($_SESSION['mail'])) {

        if($_POST['verif'] === $_POST['mdp']) {

            require __DIR__ . '/../lib/class.Database.php';

            // On essaye d'inserer dans la DB

            try {
                $req = $db->prepare('INSERT INTO User( mail, nom, prenom, num_tel, mdp) VALUE (:mail, :nom, :prenom, :num_tel, :mdp)');
                $req->bindValue(':mail', $_POST['mail'], PDO::PARAM_STR);
                $req->bindValue(':nom', strtoupper($_POST['nom']), PDO::PARAM_STR);
                $req->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
                $req->bindValue(':num_tel', $_POST['num'], PDO::PARAM_STR);
                $req->bindValue(':mdp', sha1(sha1($_POST['mdp']) . "42jeej42"), PDO::PARAM_STR);
                $req->execute();

                $_SESSION['_id'] = $db->lastInsertId();
                $_SESSION['mail'] = $_POST['mail'];
                $_SESSION['nom'] = $_POST['nom'];
                $_SESSION['prenom'] = $_POST['prenom'];
                $_SESSION['errors_tmp'] = array();

                $_SESSION['bid_count'] = 0;

            } catch (PDOException $ex) {
                $num_error = 91;
                $_SESSION['errors_tmp'] = array('from' => 'signin',
                    'nom' => $_POST['nom'],
                    'prenom' => $_POST['prenom'],
                    'num' => $_POST['num']);
            }
        } else {
            $num_error = 92;
            $_SESSION['errors_tmp'] = array('from' => 'signin',
                'mail' => $_POST['mail'],
                'nom' => $_POST['nom'],
                'prenom' => $_POST['prenom'],
                'num' => $_POST['num']);
        }
    } else {
        $num_error = 93;
    }
} else {
    $num_error = 94;
}

if ($num_error == 0) {
    header('Location: ../index.php');
} else {
    header('Location: ../signin.php?error=' . $num_error);
}