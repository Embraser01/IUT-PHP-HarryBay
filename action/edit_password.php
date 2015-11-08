<?php

/**
 * Range error : 60 - 69
 */

session_start();

$num_error = 0;

if (isset($_SESSION['_id'])) { //Si l'utilisateur est connecté
    if ($_SESSION['_id'] == $_GET['id']) {  //si le compte concerné correspond bien au compte actuellement connecté
        if (isset($_POST['oldpwd'])
            AND isset($_POST['newpwd'])
            AND isset($_POST['newpwdverif'])
            AND $_POST['oldpwd'] != ""
            AND $_POST['newpwd'] != ""
            AND $_POST['newpwdverif'] != ""
        ) { // Si tous les champs sont renseignés

            if ($_POST['newpwd'] == $_POST['newpwdverif']) {

                require __DIR__ . '/../lib/class.Database.php';

                $getmdp = $db->prepare('SELECT mdp FROM User WHERE User.`_id` = :id');
                $getmdp->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
                $getmdp->execute();

                if ($getmdp->rowCount() >= 1) {
                    $resmdp = $getmdp->fetch(PDO::FETCH_OBJ);
                    $mdp = sha1(sha1($_POST['oldpwd']) . "42jeej42");

                    if ($mdp == $resmdp->mdp) {

                        try {
                            $req = $db->prepare('UPDATE User SET mdp = :mdp WHERE User.`_id` = :id');
                            $req->bindValue(':mdp', sha1(sha1($_POST['newpwd']) . "42jeej42"), PDO::PARAM_STR);
                            $req->bindValue(':id', $_GET['id'], PDO::PARAM_STR);
                            $req->execute();
                        } catch (PDOException $ex) {
                            $num_error = 61;
                        }
                    } else {
                        $num_error = 62; //mauvais ancien mot de passe
                    }
                } else {
                    $num_error = 63; //aucun compte avec cet id
                }
            } else {
                $num_error = 64; //les nouveaux mots de passe ne concordent pas
            }
        } else {
            $num_error = 65; //Les champs ne sont pas tous renseignés
        }
    } else {
        $num_error = 66; //Le compte à modifier n'est pas le compte actuellement utilisé
    }
} else {
    $num_error = 67; //L'utilisateur n'est pas ou plus connecté
}

if ($num_error == 0) {
    header('Location: ../user_objects.php?page=1&success=60');
} elseif ($num_error == 67 || $num_error == 66) {
    header('Location: ../login.php?error=' . $num_error);
} else {
    header('Location: ../edit_user.php?id=' . $_GET['id'] . '&error=' . $num_error);
}