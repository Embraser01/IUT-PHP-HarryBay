<?php

/**
 * Range error : 20 - 29
 */


function sendMail($value, $is_max = FALSE)
{
    $passage_ligne = "\n";

    //===== Définition du sujet.
    $sujet = "Harry Bay | $value->nom";
    //=========

    //===== Définition du footer.
    $footer = "--<br>Harry Bay,<br>Projet de PHP fait dans le cadre du DUT Informatique S3<br>A l'IUT Lyon 1 - Site du Bourg-en-Bresse<br>Par:<br> - Nicolas POURPRIX<br> - Marc-Antoine FERNANDES</body></html>";
    //=========
    if($is_max) $footer = "<br>PS: Cet objet détient désormais le record de l'objet vendu le plus cher!<br>Félicitations!<br>" . $footer;


    //=====Création de la boundary
    $boundary = "-----=" . md5(rand());
    //==========

    //===== Création du header des e-mail.
    $header = "From: \"Harry Bay\" <marc-antoine.fernandes@etu.univ-lyon1.fr>" . $passage_ligne;
    $header .= "Reply-to: \"Harry Bay\" <marc-antoine.fernandes@etu.univ-lyon1.fr>" . $passage_ligne;
    $header .= "MIME-Version: 1.0" . $passage_ligne;
    $header .= "Content-Type: multipart/alternative;" . $passage_ligne . " boundary=\"$boundary\"" . $passage_ligne;
    //==========

/*======== MAIL FOR THE OWNER ========*/

    //=====Déclaration des messages au format HTML.
    $message_html = "<html><head></head><body>Bonsoir $value->proprioPrenom $value->proprioNom,<br>L'enchère de votre objet \"$value->nom\" est arrivé à échéance, veuillez contacter $value->bestPrenom " . substr($value->bestNom, 0, 1) . ". <br> - Téléphone: $value->bestNum <br> - Mail: $value->bestMail";
    //==========

    //=====Création du message.
    $message = $passage_ligne . "--" . $boundary . $passage_ligne;
    //=====Ajout du message au format HTML
    $message .= "Content-Type: text/html; charset=\"UTF-8\"" . $passage_ligne;
    $message .= "Content-Transfer-Encoding: 8bit" . $passage_ligne;
    $message .= $passage_ligne . $message_html . $footer . $passage_ligne;
    //==========
    $message .= $passage_ligne . "--" . $boundary . "--" . $passage_ligne;
    $message .= $passage_ligne . "--" . $boundary . "--" . $passage_ligne;
    //==========

    //=====Envoi de l'e-mail.
    mail($value->proprioMail, $sujet, $message, $header);
    //==========


/*======== MAIL FOR THE BUYER ========*/

    //=====Déclaration des messages au format HTML.
    $message_html = "<html><head></head><body>Bonsoir $value->bestPrenom $value->bestNom,<br>L'enchère de l'objet \"$value->nom\" est arrivé à échéance, et vous êtes le meilleur enchérisseur, veuillez contacter $value->proprioPrenom " . substr($value->proprioNom, 0, 1) . ". <br> - Téléphone: $value->proprioNum <br> - Mail: $value->proprioMail";
    //==========

    //=====Création du message.
    $message = $passage_ligne . "--" . $boundary . $passage_ligne;
    //=====Ajout du message au format HTML
    $message .= "Content-Type: text/html; charset=\"ISO-8859-1\"" . $passage_ligne;
    $message .= "Content-Transfer-Encoding: 8bit" . $passage_ligne;
    $message .= $passage_ligne . $message_html . $footer . $passage_ligne;
    //==========
    $message .= $passage_ligne . "--" . $boundary . "--" . $passage_ligne;
    $message .= $passage_ligne . "--" . $boundary . "--" . $passage_ligne;
    //==========

    //=====Envoi de l'e-mail.
    mail($value->bestMail, $sujet, $message, $header);
    //==========
}

session_start();

$num_error = 0;

if (isset($_SESSION['_id']) AND $_SESSION['_id'] == 1 OR $_SESSION['_id'] == 4) {

    require __DIR__ . '/../lib/class.Database.php';

    $req = $db->prepare('SELECT Objet.nom AS obj_nom, Objet.prix_now, uProprio.mail AS proprioMail, uProprio.num_tel AS proprioNum, uBest.mail AS bestMail, uBest.num_tel AS bestNum, uProprio.nom AS proprioNom, uProprio.prenom AS proprioPrenom, uBest.nom AS bestNom, uBest.prenom AS bestPrenom FROM Objet JOIN User uProprio ON Objet.proprio_id=uProprio.`_id` LEFT JOIN User uBest ON Objet.best_user_id=uBest.`_id` WHERE Objet.date_stop < NOW() ORDER BY is_max DESC');
    $req->execute();

    if ($req->rowCount() >= 2) {


        $res = $req->fetchAll();

        $max = 0;
        $id_max = -1;
        $list_to_delete = array();


        /** On parcours la première fois la liste pour determiner le max */

        foreach ($res as $key => $value) {
            if ($value->is_max == true) { // Uniquement le premier
                $max = $value->prix_now;
                $id_max = $value->_id;
            } elseif ($value->prix_now > $max) { // Nouveau record
                $max = $value->prix_now;
                $id_max = $value->_id;
            }
        }


        /** On parcours une deuxième fois pour lister les éléments à delete et on supprime les images */

        foreach ($res as $key => $value) {
            if ($value->_id != $id_max) {
                array_push($list_to_delete, $value->_id);
                unlink(__DIR__ . "/../images/objects/" . basename($value->_id) . '.jpg');
                sendMail($value, FALSE);
            } else {
                sendMail($value, TRUE);
            }
        }


        /** Suppression des objets dans la BDD */

        try {
            $query = "DELETE FROM Objet WHERE `_id` IN (";
            foreach ($list_to_delete as $value) $query .= $value . ',';
            $query = substr($query, 0, -1) . ')';


            $req = $db->prepare($query);
            $req->execute();

            $req = $db->prepare('UPDATE Objet SET is_max=TRUE WHERE `_id` = :id');
            $req->bindValue(':id', $id_max, PDO::PARAM_INT);
            $req->execute();

        } catch (PDOException $ex) {
            $num_error = 21; //Problème dans la base de données
        }
    } else {
        $num_error = 22;
    }
} else {
    $num_error = 23;
}

if ($num_error == 0) {
    header('Location: ../admin.php?success=20');
} elseif ($num_error == 21) {
    echo $ex->getMessage();
    echo '<br>' . $query;
} elseif( $num_error == 23) {
    header('Location: ../index.php');
} else {
    header('Location: ../admin.php?error=' . $num_error);
}
