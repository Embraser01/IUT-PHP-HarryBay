<?php
/**
 * Created by PhpStorm.
 * User: Nicolas POURPRIX
 * Date: 30/10/2015
 * Time: 13:37...       YAY!
 */


session_start();

if (isset($_SESSION['_id']) AND $_SESSION['_id'] == 1 OR $_SESSION['_id'] == 4) {

    require __DIR__ . '/../lib/class.Database.php';

    $num_error = 0;

    $req = $db->prepare('SELECT Objet.`_id`, Objet.prix_now, Objet.is_max FROM Objet WHERE Objet.date_stop < NOW() ORDER BY is_max DESC');
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
                unlink( __DIR__ . "/../images/objects/" . basename($value->_id));
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
