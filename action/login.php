<?php

/**
 * Range error : 80 - 89
 */

session_start();

$num_error = 0;

if (isset($_POST['pwd']) AND isset($_POST['mail'])) { // Informations envoyées

    if (!isset($_SESSION['mail'])) {

        require __DIR__ . '/../lib/class.Database.php';

        // On fait la requete dans la DB User

        $req = $db->prepare('SELECT _id, prenom, nom, mail FROM User WHERE mail=:mail AND mdp=:mdp');
        $req->bindValue(':mail', $_POST['mail'], PDO::PARAM_STR);
        $req->bindValue(':mdp', sha1(sha1($_POST['pwd']) . "42jeej42"), PDO::PARAM_STR);
        $req->execute();

        if ($req->rowCount() >= 1) { // Correspondance trouvé dans la DB
            $res = $req->fetchAll();

            $_SESSION['_id'] = $res[0]->_id;
            $_SESSION['mail'] = $res[0]->mail;
            $_SESSION['nom'] = $res[0]->nom;
            $_SESSION['prenom'] = $res[0]->prenom;
            $_SESSION['errors_tmp'] = array();

            $_SESSION['bid_count'] = 0;

        } else { // aucune correspondance trouvé
            $num_error = 81;
        }
    } else {
        $num_error = 82;
    }
} else {
    $num_error = 83;
}

if ($num_error == 0) {
    header('Location: ../index.php');
} else {
    header('Location: ../login.php?error=' . $num_error);
}