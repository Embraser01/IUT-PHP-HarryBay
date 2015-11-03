<?php
/**
 * Created by PhpStorm.
 * User: Nicolas POURPRIX
 * Date: 30/10/2015
 * Time: 13:37...       YAY!
 */
function sendMail($mail, $value, $is_proprio = TRUE, $is_max = FALSE)
{

    /*======== MAIL FOR THE OWNER ========*/

    if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $proprio_mail)) { // On filtre les serveurs qui rencontrent des bogues.
        $passage_ligne = "\r\n";
    } else {
        $passage_ligne = "\n";
    }

    //=====Déclaration des messages au format texte et au format HTML.
    $message_txt = "Vous";
    $message_html = "<html><head></head><body><b>Salut à tous</b>, voici un e-mail envoyé par un <i>script PHP</i>.</body></html>";
    //==========

//=====Création de la boundary
    $boundary = "-----=" . md5(rand());
//==========

//=====Définition du sujet.
    $sujet = "Harry Bay";
//=========

//=====Création du header de l'e-mail.
    $header = "From: \"Harry Bay\" <marc-antoine.fernandes@etu.univ-lyon1.fr>" . $passage_ligne;
    $header .= "Reply-to: \"Marc-Antoine FERNANDES\" <marc-antoine.fernandes@etu.univ-lyon1.fr>" . $passage_ligne;
    $header .= "MIME-Version: 1.0" . $passage_ligne;
    $header .= "Content-Type: multipart/alternative;" . $passage_ligne . " boundary=\"$boundary\"" . $passage_ligne;
//==========

//=====Création du message.
    $message = $passage_ligne . "--" . $boundary . $passage_ligne;
//=====Ajout du message au format HTML
    $message .= "Content-Type: text/html; charset=\"ISO-8859-1\"" . $passage_ligne;
    $message .= "Content-Transfer-Encoding: 8bit" . $passage_ligne;
    $message .= $passage_ligne . $message_html . $passage_ligne;
//==========
    $message .= $passage_ligne . "--" . $boundary . "--" . $passage_ligne;
    $message .= $passage_ligne . "--" . $boundary . "--" . $passage_ligne;
//==========

//=====Envoi de l'e-mail.
    mail($mail, $sujet, $message, $header);
//==========
}

session_start();

if (isset($_SESSION['_id']) AND $_SESSION['_id'] == 1 OR $_SESSION['_id'] == 4) {

    require __DIR__ . '/../lib/class.Database.php';

    $num_error = 0;

    $req = $db->prepare('SELECT Objet.nom, Objet.prix_now, uProprio.mail AS proprioMail, uProprio.num_tel AS proprioNum, uBest.mail AS bestMail, uBest.num_tel AS bestNum FROM Objet JOIN User uProprio ON Objet.proprio_id=uProprio.`_id` LEFT JOIN User uBest ON Objet.best_user_id=uBest.`_id` WHERE Objet.date_stop < NOW() ORDER BY is_max DESC');
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
                unlink(__DIR__ . "/../images/objects/" . basename($value->_id));
                // TODO : Envoyer mail à l'acheteur et le vendeur
            }
        }


        /** Suppression des objets dans la BDD */

        try {
            $query = "DELETE FROM Objet WHERE `_id` IN (";
            foreach ($list_to_delete as $value) $query = $query . $value . ',';
            $query = substr($query, 0, -1) . ')';


            $req = $db->prepare($query);
            $req->execute();

            $req = $db->prepare('UPDATE Objet SET is_max=TRUE WHERE `_id` = :id');
            $req->bindValue(':id', $id_max, PDO::PARAM_INT);
            $req->execute();

        } catch (PDOException $ex) {
            $num_error = 1; //Problème dans la base de données
        }
    }


    if ($num_error == 0) {
        header('Location: ../admin.php?success=1');
    } elseif ($num_error == 1) {
        echo $ex->getMessage();
        echo '<br>' . $query;
    } else {
        header('Location: ../index.php');
    }
}
