<?php
session_start();
if (!isset($_SESSION['_id']) OR ($_SESSION['_id'] != 1 AND $_SESSION['_id'] != 4 AND $_SESSION['_id'] != 7)) {
    header('Location: https://www.youtube.com/watch?v=dQw4w9WgXcQ');
    exit;
    //Félicitations, vous venez de rick-roller quelqu'un.
}

include('includes/header.php');
?>


    <div class="titre_page">
        Interface administrateur
    </div>

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
                Épurer la Base de Données</a>
        </div>
    </div>

<?php
$uorder_string = "ORDER BY Objet.";

$_GET['uorder'] = (isset($_GET['uorder'])) ? $_GET['uorder'] : 0;

switch ($_GET['uorder']) {
    case 1:
        $uorder_string .= "nom";
        break;
    case 2:
        $uorder_string .= "proprio_id";
        break;
    case 3:
        $uorder_string .= "prix_min";
        break;
    case 4:
        $uorder_string .= "prix_now";
        break;
    case 5:
        $uorder_string .= "best_user_id";
        break;
    case 6:
        $uorder_string .= "date_start";
        break;
    case 7:
        $uorder_string .= "date_stop";
        break;
    default:
        $uorder_string .= "_id";
        break;
}

$uorder_string .= (isset($_GET['desc'])) ? ' DESC, Objet.`_id` DESC' : ', Objet.`_id`';


$query = 'SELECT Objet.`_id`, Objet.nom AS `desc`,Objet.prix_min, Objet.prix_now, Objet.date_start, Objet.date_stop, Objet.proprio_id, Objet.best_user_id, User.prenom, User.nom FROM Objet JOIN User ON Objet.proprio_id = User._id ';

$query .= $uorder_string . ' LIMIT 200';


$req = $db->prepare($query);
$req->execute();

if ($req->rowCount() != 0) {
    $desc_string = (isset($_GET['desc'])) ? '<i class="material-icons">arrow_drop_down</i>' : '<i class="material-icons">arrow_drop_up</i>';
    ?>

    <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp centre_card">
        <thead>
        <tr>
            <th class="mdl-data-table__cell--non-numeric">
                <a class="link-no-style"
                   href="<?php echo (isset($_GET['desc']) AND $_GET['uorder'] == 1) ? 'admin.php?uorder=1' : 'admin.php?uorder=1&desc'; ?>">Nom
                </a>
                <?php if ($_GET['uorder'] == 1) echo $desc_string; ?>
            </th>
            <th class="mdl-data-table__cell--non-numeric">
                <a class="link-no-style"
                   href="<?php echo (isset($_GET['desc']) AND $_GET['uorder'] == 2) ? 'admin.php?uorder=2' : 'admin.php?uorder=2&desc'; ?>">Vendeur
                </a>
                <?php if ($_GET['uorder'] == 2) echo $desc_string; ?>
            </th>
            <th>
                <a class="link-no-style"
                   href="<?php echo (isset($_GET['desc']) AND $_GET['uorder'] == 3) ? 'admin.php?uorder=3' : 'admin.php?uorder=3&desc'; ?>">Prix
                    de base
                </a>
                <?php if ($_GET['uorder'] == 3) echo $desc_string; ?>
            </th>
            <th>
                <a class="link-no-style"
                   href="<?php echo (isset($_GET['desc']) AND $_GET['uorder'] == 4) ? 'admin.php?uorder=4' : 'admin.php?uorder=4&desc'; ?>">Meilleure
                    enchère
                </a>
                <?php if ($_GET['uorder'] == 4) echo $desc_string; ?>
            </th>
            <th class="mdl-data-table__cell--non-numeric">
                <a class="link-no-style"
                   href="<?php echo (isset($_GET['desc']) AND $_GET['uorder'] == 5) ? 'admin.php?uorder=5' : 'admin.php?uorder=5&desc'; ?>">Meilleur
                    acheteur
                </a>
                <?php if ($_GET['uorder'] == 5) echo $desc_string; ?>
            </th>
            <th class="mdl-data-table__cell--non-numeric">
                <a class="link-no-style"
                   href="<?php echo (isset($_GET['desc']) AND $_GET['uorder'] == 6) ? 'admin.php?uorder=6' : 'admin.php?uorder=6&desc'; ?>">Date
                    de début
                </a>
                <?php if ($_GET['uorder'] == 6) echo $desc_string; ?>
            </th>
            <th class="mdl-data-table__cell--non-numeric">
                <a class="link-no-style"
                   href="<?php echo (isset($_GET['desc']) AND $_GET['uorder'] == 7) ? 'admin.php?uorder=7' : 'admin.php?uorder=7&desc'; ?>">Date
                    de fin
                </a>
                <?php if ($_GET['uorder'] == 7) echo $desc_string; ?>
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
                            href="#user_id_<?php echo $row->best_user_id; ?>"><?php echo $row->best_user_id; ?> -
                            ICI</a>
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

$order_string = "ORDER BY User.";

$_GET['order'] = (isset($_GET['order'])) ? $_GET['order'] : 0;

switch ($_GET['order']) {
    case 0:
        $order_string .= "_id";
        break;
    case 1:
        $order_string .= "nom";
        break;
    case 2:
        $order_string .= "prenom";
        break;
    case 3:
        $order_string .= "mail";
        break;
    default:
        $order_string .= "_id";
        break;
}

$order_string .= (isset($_GET['desc'])) ? ' DESC, User.`_id` DESC' : ', User.`_id`';


$query = 'SELECT User.`_id`, User.mail, User.nom, User.prenom, User.num_tel FROM User ';

$query .= $order_string . ' LIMIT 200';


$req = $db->prepare($query);
$req->execute();

if ($req->rowCount() != 0) {
    ?>

    <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp centre_card">
        <thead>
        <tr>
            <th class="mdl-data-table__cell--non-numeric">
                <a class="link-no-style"
                   href="<?php echo (isset($_GET['desc']) AND $_GET['order'] == 1) ? 'admin.php?order=1' : 'admin.php?order=1&desc'; ?>">Nom
                </a>
                <?php if ($_GET['order'] == 1) echo $desc_string; ?>
            </th>
            <th class="mdl-data-table__cell--non-numeric">
                <a class="link-no-style"
                   href="<?php echo (isset($_GET['desc']) AND $_GET['order'] == 2) ? 'admin.php?order=2' : 'admin.php?order=2&desc'; ?>">Prénom
                </a>
                <?php if ($_GET['order'] == 2) echo $desc_string; ?>
            </th>
            <th class="mdl-data-table__cell--non-numeric">
                <a class="link-no-style"
                   href="<?php echo (isset($_GET['desc']) AND $_GET['order'] == 3) ? 'admin.php?order=3' : 'admin.php?order=3&desc'; ?>">Mail
                </a>
                <?php if ($_GET['order'] == 3) echo $desc_string; ?>
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

?>