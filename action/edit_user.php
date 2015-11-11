<?php

/**
 * Range error : 70 - 79
 */

session_start();

$num_error = 0;

if (isset($_SESSION['_id'])) { //Si l'utilisateur est connecté
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


            require __DIR__ . '/../lib/class.Database.php';

                $getmdp = $db->prepare('SELECT mdp FROM User WHERE User.`_id` = :id');
                $getmdp->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
                $getmdp->execute();


            if ($getmdp->rowCount() >= 1) {
                $resmdp = $getmdp->fetch(PDO::FETCH_OBJ);
                $mdp = sha1(sha1($_POST['mdp']) . "42jeej42");

                if ($mdp == $resmdp->mdp) {

                    try {
                        $req = $db->prepare('UPDATE User SET nom = :nom, prenom = :prenom, num_tel = :num, mail = :mail WHERE User.`_id` = :id');
                        $req->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
                        $req->bindValue(':nom', strtoupper($_POST['nom']), PDO::PARAM_STR);
                        $req->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
                        $req->bindValue(':num', $_POST['num'], PDO::PARAM_STR);
                        $req->bindValue(':mail', strtolower($_POST['mail']), PDO::PARAM_STR);
                        $req->execute();

                        $_SESSION['mail'] = $_POST['mail'];
                        $_SESSION['nom'] = $_POST['nom'];
                        $_SESSION['prenom'] = $_POST['prenom'];

                    } catch (PDOException $ex) {
                        $num_error = 71;
                    }
                } else {
                    $num_error = 72; //mauvais mot de passe
                }
            } else {
                $num_error = 73; //aucun compte avec cet id
            }
        } else {
            $num_error = 74; //Les champs ne sont pas tous renseignés
        }
    } else {
        $num_error = 75; //Le compte à modifier n'est pas le compte actuellement utilisé
    }
} else {
    $num_error = 76; //L'utilisateur n'est pas ou plus connecté
}

if ($num_error == 0) {
    header('Location: ../user_objects.php?page=1&success=70');
} elseif ($num_error == 76 || $num_error == 75) {
    header('Location: ../login.php?error=' . $num_error);
} else {
    header('Location: ../edit_user.php?id=' . $_GET['id'] . '&error=' . $num_error);
}