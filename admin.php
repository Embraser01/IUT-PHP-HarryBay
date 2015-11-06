<?php
/**
 * Created by PhpStorm.
 * User: Marc-Antoine
 * Date: 06/10/2015
 * Time: 14:03
 */

session_start();

if (isset($_SESSION['_id']) AND $_SESSION['_id'] == 1 OR $_SESSION['_id'] == 4 OR $_SESSION['_id'] == 7) {

    include('includes/header.php');
    ?>

    <div class="mdl-card mdl-shadow--16dp centre_card">
        <div class="mdl-card__title">
            <h1 class="mdl-card__title-text">Système d'épuration des objets</h1>
        </div>
        <div class="mdl-card__supporting-text">
            Ce système permet de supprimer les objets obselètes de la base de données, et éventuellement de mettre à
            jour l'objet le plus cher.

        </div>
        <div class="mdl-card__actions mdl-card--border">
            <a href="action/clear.php" class="mdl-button mdl-js-button mdl-js-ripple-effect"><i class="material-icons">clear_all</i>
                Epurer la Base de Données</a>
        </div>
    </div>

    <?php
    $uorder_string = "";
    if (!isset($_GET['uorder'])) {
        $_GET['uorder'] = 0;
    }
    switch ($_GET['uorder']) {
        case 0:
            $uorder_string = "ORDER BY Objet._id";
            break;
        case 1:
            $uorder_string = "ORDER BY Objet.nom";
            break;
        case 2:
            $uorder_string = "ORDER BY Objet.proprio_id";
            break;
        case 3:
            $uorder_string = "ORDER BY Objet.prix_min";
            break;
        case 4:
            $uorder_string = "ORDER BY Objet.prix_now";
            break;
        case 5:
            $uorder_string = "ORDER BY Objet.best_user_id";
            break;
        case 6:
            $uorder_string = "ORDER BY Objet.date_start";
            break;
        case 7:
            $uorder_string = "ORDER BY Objet.date_stop";
            break;
        default:
            $uorder_string = "ORDER BY Objet._id";
            break;
    }

    if (isset($_GET['desc'])) {
        $uorder_string = $uorder_string . ' DESC, Objet.`_id` DESC';
    } else {
        $uorder_string = $uorder_string . ', Objet.`_id`';
    }


    $query = 'SELECT Objet.`_id`, Objet.nom AS `desc`,Objet.prix_min, Objet.prix_now, Objet.date_start, Objet.date_stop, Objet.proprio_id, Objet.best_user_id, User.prenom, User.nom FROM Objet JOIN User ON Objet.proprio_id = User._id ';

    $query = $query . $uorder_string;

    $req = $db->prepare($query);
    $req->execute();

    if ($req->rowCount() != 0) {
        ?>

<!--        <table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp centre_card">-->
        <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp centre_card">
            <thead>
            <tr>
                <th class="mdl-data-table__cell--non-numeric">
                    <a class="link-no-style"
                       href="<?php if (isset($_GET['desc']) AND $_GET['uorder'] == 1) echo 'admin.php?uorder=1'; else echo 'admin.php?uorder=1&desc' ?>">Nom
                    </a>
                    <?php if ($_GET['uorder'] == 1) {
                        if(isset($_GET['desc'])){
                            echo '<i class="material-icons">arrow_drop_down</i>';
                        } else {
                            echo '<i class="material-icons">arrow_drop_up</i>';
                        }
                    } ?>
                </th>
                <th class="mdl-data-table__cell--non-numeric">
                    <a class="link-no-style"
                       href="<?php if (isset($_GET['desc']) AND $_GET['uorder'] == 2) echo 'admin.php?uorder=2'; else echo 'admin.php?uorder=2&desc' ?>">Vendeur
                    </a>
                    <?php if ($_GET['uorder'] == 2) {
                        if(isset($_GET['desc'])){
                            echo '<i class="material-icons">arrow_drop_down</i>';
                        } else {
                            echo '<i class="material-icons">arrow_drop_up</i>';
                        }
                    } ?>
                </th>
                <th>
                    <a class="link-no-style"
                       href="<?php if (isset($_GET['desc']) AND $_GET['uorder'] == 3) echo 'admin.php?uorder=3'; else echo 'admin.php?uorder=3&desc' ?>">Prix
                        de base
                    </a>
                    <?php if ($_GET['uorder'] == 3) {
                        if(isset($_GET['desc'])){
                            echo '<i class="material-icons">arrow_drop_down</i>';
                        } else {
                            echo '<i class="material-icons">arrow_drop_up</i>';
                        }
                    } ?>
                </th>
                <th>
                    <a class="link-no-style"
                       href="<?php if (isset($_GET['desc']) AND $_GET['uorder'] == 4) echo 'admin.php?uorder=4'; else echo 'admin.php?uorder=4&desc' ?>">Meilleure
                        enchère
                    </a>
                    <?php if ($_GET['uorder'] == 4) {
                        if(isset($_GET['desc'])){
                            echo '<i class="material-icons">arrow_drop_down</i>';
                        } else {
                            echo '<i class="material-icons">arrow_drop_up</i>';
                        }
                    } ?>
                </th>
                <th class="mdl-data-table__cell--non-numeric">
                    <a class="link-no-style"
                       href="<?php if (isset($_GET['desc']) AND $_GET['uorder'] == 5) echo 'admin.php?uorder=5'; else echo 'admin.php?uorder=5&desc' ?>">Meilleur
                        acheteur
                    </a>
                    <?php if ($_GET['uorder'] == 5) {
                        if(isset($_GET['desc'])){
                            echo '<i class="material-icons">arrow_drop_down</i>';
                        } else {
                            echo '<i class="material-icons">arrow_drop_up</i>';
                        }
                    } ?>
                </th>
                <th class="mdl-data-table__cell--non-numeric">
                    <a class="link-no-style"
                       href="<?php if (isset($_GET['desc']) AND $_GET['uorder'] == 6) echo 'admin.php?uorder=6'; else echo 'admin.php?uorder=6&desc' ?>">Date
                        de début
                    </a>
                    <?php if ($_GET['uorder'] == 6) {
                        if(isset($_GET['desc'])){
                            echo '<i class="material-icons">arrow_drop_down</i>';
                        } else {
                            echo '<i class="material-icons">arrow_drop_up</i>';
                        }
                    } ?>
                </th>
                <th class="mdl-data-table__cell--non-numeric">
                    <a class="link-no-style"
                       href="<?php if (isset($_GET['desc']) AND $_GET['uorder'] == 7) echo 'admin.php?uorder=7'; else echo 'admin.php?uorder=7&desc' ?>">Date
                        de fin
                    </a>
                    <?php if ($_GET['uorder'] == 7) {
                        if(isset($_GET['desc'])){
                            echo '<i class="material-icons">arrow_drop_down</i>';
                        } else {
                            echo '<i class="material-icons">arrow_drop_up</i>';
                        }
                    } ?>
                </th>
            </tr>
            </thead>
            <tbody>

            <?php
            $res = $req->fetchAll();

            foreach ($res as $row) { ?>

                <tr data-objet-id="<?php echo $row->_id; ?>">
                    <td class="mdl-data-table__cell--non-numeric"><?php echo $row->desc; ?></td>
                    <td class="mdl-data-table__cell--non-numeric"><a
                            class="link-no-style"
                            href="#user_id_<?php echo $row->proprio_id; ?>"><?php echo $row->proprio_id; ?> - ICI</a>
                    </td>
                    <td><?php echo $row->prix_min; ?> €</td>
                    <td><?php echo $row->prix_now; ?> €</td>
                    <?php
                    if (is_null($row->best_user_id)) {
                        ?>
                        <td class="mdl-data-table__cell--non-numeric">NULL</td>
                    <?php } else {
                        ?>
                        <td class="mdl-data-table__cell--non-numeric"><a
                                class="link-no-style"
                                href="#user_id_<?php echo $row->best_user_id; ?>"><?php echo $row->best_user_id; ?> - ICI</a>
                        </td>
                    <?php }
                    ?>
                    <td><?php echo $row->date_start; ?></td>
                    <td><?php echo $row->date_stop; ?></td>
                </tr>

            <?php }
            ?>
            </tbody>
        </table>

        <?php
    }

    /*===== USER TABLE =====*/

    $order_string = "";
    if (!isset($_GET['order'])) {
        $_GET['order'] = 0;
    }
    switch ($_GET['order']) {
        case 0:
            $order_string = "ORDER BY User._id";
            break;
        case 1:
            $order_string = "ORDER BY User.nom";
            break;
        case 2:
            $order_string = "ORDER BY User.prenom";
            break;
        case 3:
            $order_string = "ORDER BY User.mail";
            break;
        default:
            $order_string = "ORDER BY User._id";
            break;
    }

    if (isset($_GET['desc'])) {
        $order_string = $order_string . ' DESC, User.`_id` DESC';
    } else {
        $order_string = $order_string . ', User.`_id`';
    }


    $query = 'SELECT User.`_id`, User.mail, User.nom, User.prenom, User.num_tel FROM User ';

    $query = $query . $order_string;

    $req = $db->prepare($query);
    $req->execute();

    if ($req->rowCount() != 0) {
        ?>

        <!--        <table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable mdl-shadow--2dp centre_card">-->
        <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp centre_card">
            <thead>
            <tr>
                <th class="mdl-data-table__cell--non-numeric">
                    <a class="link-no-style"
                       href="<?php if (isset($_GET['desc']) AND $_GET['order'] == 1) echo 'admin.php?order=1'; else echo 'admin.php?order=1&desc' ?>">Nom
                    </a>
                    <?php if ($_GET['order'] == 1) {
                        if(isset($_GET['desc'])){
                            echo '<i class="material-icons">arrow_drop_down</i>';
                        } else {
                            echo '<i class="material-icons">arrow_drop_up</i>';
                        }
                    } ?>
                </th>
                <th class="mdl-data-table__cell--non-numeric">
                    <a class="link-no-style"
                       href="<?php if (isset($_GET['desc']) AND $_GET['order'] == 2) echo 'admin.php?order=2'; else echo 'admin.php?order=2&desc' ?>">Prénom
                    </a>
                    <?php if ($_GET['order'] == 2) {
                        if(isset($_GET['desc'])){
                            echo '<i class="material-icons">arrow_drop_down</i>';
                        } else {
                            echo '<i class="material-icons">arrow_drop_up</i>';
                        }
                    } ?>
                </th>
                <th class="mdl-data-table__cell--non-numeric">
                    <a class="link-no-style"
                       href="<?php if (isset($_GET['desc']) AND $_GET['order'] == 3) echo 'admin.php?order=3'; else echo 'admin.php?order=3&desc' ?>">Mail
                    </a>
                    <?php if ($_GET['order'] == 3) {
                        if(isset($_GET['desc'])){
                            echo '<i class="material-icons">arrow_drop_down</i>';
                        } else {
                            echo '<i class="material-icons">arrow_drop_up</i>';
                        }
                    } ?>
                </th>
                <th>Numéro de tel</th>
            </tr>
            </thead>
            <tbody>

            <?php
            $res = $req->fetchAll();

            foreach ($res as $row) { ?>

                <tr data-user-id="<?php echo $row->_id; ?>" id="user_id_<?php echo $row->_id; ?>">
                    <td class="mdl-data-table__cell--non-numeric"><?php echo $row->nom; ?></td>
                    <td class="mdl-data-table__cell--non-numeric"><?php echo $row->prenom; ?></td>
                    <td class="mdl-data-table__cell--non-numeric"><?php echo $row->mail; ?></td>
                    <td><?php echo $row->num_tel; ?></td>
                </tr>
            <?php }
            ?>
            </tbody>
        </table>

        <?php
    }
    include('includes/footer.php');
} else {
    header('Location: https://www.youtube.com/watch?v=dQw4w9WgXcQ');
    //Félicitations, vous venez de rick-roller quelqu'un.
}
?>