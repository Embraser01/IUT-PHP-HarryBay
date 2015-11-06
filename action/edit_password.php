<?php
/**
 * Created by PhpStorm.
 * User: Nicolas POURPRIX
 * Date: 05/11/2015
 * Time: 00:20
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

                $getmdp;
                $req;

                require __DIR__ . '/../lib/class.Database.php';

                try {
                    $getmdp = $db->prepare('SELECT mdp FROM User WHERE User.`_id` = :id');
                    $getmdp->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
                    $getmdp->execute();
                } catch (PDOException $exmdp) {
                    $num_error = 1;
                    echo $exmdp;
                }

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
                            // Reste sur la page et affiche l'erreur;
                            $num_error = 1;
                            echo $ex;
                        }

                    } else {
                        $num_error = 12; //mauvais ancien mot de passe
                    }

                } else {
                    $num_error = 3; //aucun compte avec cet id
                }
            }
            else
            {
                $num_error = 14; //les nouveaux mots de passe ne concordent pas
            }
        } else {
            $num_error = 4; //Les champs ne sont pas tous renseignés
        }
    } else {
        $num_error = 5; //Le compte à modifier n'est pas le compte actuellement utilisé
    }
} else {
    $num_error = 6; //L'utilisateur n'est pas ou plus connecté
}

if ($num_error == 0) {
    header('Location: ../user_objects.php?page=1&success=2');
} elseif ($num_error == 6) {
    header('Location: ../login.php?error=15');
} elseif ($num_error == 1) {

} else {
    header('Location: ../edit_user.php?id=' . $_GET['id'] . '&error=' . $num_error);
}