<?php
session_start();

$num_error = 0;

if (isset($_POST['pwd']) AND isset($_POST['mail'])) { // Pas déjà co et mot de passe envoyé

    if (!isset($_SESSION['mail'])) {

        require __DIR__ . '/../lib/class.Database.php';

        // On fait la requete dans la DB User

        $req = $db->prepare('SELECT _id FROM User WHERE mail=:mail AND mdp=:mdp');
        $req->bindValue(':mail', $_POST['mail'], PDO::PARAM_STR);
        $req->bindValue(':mdp', sha1(sha1($_POST['pwd']) . "42jeej42"), PDO::PARAM_STR);
        $req->execute();

        if ($req->rowCount() >= 1) { // Correspondance trouvé dans la DB

            $_SESSION['_id'] = $req->fetchAll()[0]->_id;
            $_SESSION['mail'] = $_POST['mail'];
            $_SESSION['nom'] = $_POST['nom'];
            $_SESSION['prenom'] = $_POST['prenom'];

            $_SESSION['bid_count'] = 0;

        } else { // aucune correspondance trouvé
            $num_error = 1;
        }
    } else {
        $num_error = 2;
    }
} else {
    $num_error = 3;
}

if ($num_error == 0) {
    header('Location: ../index.php');
} else {
    header('Location: ../login.php?error=' . $num_error);
}