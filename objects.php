<?php

$months = array('01' => "Janvier",
    '02' => "Février",
    '03' => "Mars",
    '04' => "Avril",
    '05' => "Mai",
    '06' => "Juin",
    '07' => "Juillet",
    '08' => "Août",
    '09' => "Septembre",
    '10' => "Octobre",
    '11' => "Novembre",
    '12' => "Décembre");

include('includes/header.php');

/* Valeurs par defaut */

$_GET['page'] = (isset($_GET['page'])) ? abs($_GET['page']) : 1;
$_GET['order'] = (isset($_GET['order'])) ? $_GET['order'] : 0;
$_GET['desc'] = (isset($_GET['desc'])) ? $_GET['desc'] : "true";

$objPerPage = 10; //nombre d'objets par page
$offset = ($_GET['page'] - 1) * $objPerPage;

$order_string = "ORDER BY Objet.";

switch ($_GET['order']) {
    case 0:
        $order_string .= "date_start";
        break;
    case 1:
        $order_string .= "date_stop";
        break;
    case 2:
        $order_string .= "prix_now";
        break;
    default:
        $order_string .= "date_start";
        break;
}
$order_string .= ($_GET['desc']=="true") ? ' DESC, Objet.`_id` DESC' : ', Objet.`_id`';
?>

    <div class="titre_page">

        Enchères<br>
        <!-- Left aligned menu below button -->
        <button class="mdl-button mdl-js-button mdl-button--icon" id="sort_menu">
          <i class="material-icons">sort</i>
        </button>
        <span class="mdl-tooltip" for="sort_menu">
            Trier
        </span>

        <a href="objects.php?order=<?php echo $_GET['order'];?>&desc=<?php echo ($_GET['desc'] == "true") ? "false" : "true"; ?>" class="mdl-button mdl-js-button mdl-button--icon" id="invert_sort">
            <i class="material-icons">import_export</i>
        </a>
        <span class="mdl-tooltip" for="invert_sort">
            Inverser l'ordre
        </span>

        <ul class="mdl-menu mdl-menu--bottom-left mdl-js-menu mdl-js-ripple-effect"
            for="sort_menu">
          <a href="objects.php?order=0&desc=true" class="link-no-style"><li class="mdl-menu__item">Date de mise en ligne</li></a>
          <a href="objects.php?order=1&desc=true" class="link-no-style"><li class="mdl-menu__item">Date de fin</li></a>
          <a href="objects.php?order=2&desc=false" class="link-no-style"><li class="mdl-menu__item">Prix</li></a>
        </ul>
    </div>

    <div class="list_card_wrapper">

<?php
// On fait la requete dans la DB Objet pour avoir l'id

$query_string = 'SELECT Objet.`_id`, Objet.nom AS `desc`, Objet.prix_now, Objet.date_stop, Objet.proprio_id, Objet.best_user_id, User.prenom, User.nom FROM Objet LEFT JOIN User ON Objet.best_user_id = User._id WHERE Objet.date_start <= NOW() AND date_stop >= NOW() '. $order_string . ' LIMIT :perPage OFFSET :off';

$req = $db->prepare($query_string);
$req->bindValue(':off', $offset, PDO::PARAM_INT);
$req->bindValue(':perPage', $objPerPage, PDO::PARAM_INT);
$req->execute();

if ($req->rowCount() >= 1) { // Correspondance trouvé dans la DB

    $res = $req->fetchAll();

    foreach ($res as $key => $value) {
        $date_stop = strtotime($value->date_stop);
        ?>

        <div class="mdl-card mdl-shadow--4dp list_card object_card" id="objet<?php echo $value->_id ?>">

            <div class="mdl-card__title titre_card" style="background: linear-gradient( rgba(0, 0, 0, 0.05), rgba(0, 0, 0, 0.25) ), url('./images/get_obj_img.php?id=<?php echo $value->_id ?>') center / cover;">
                <h1 class="mdl-card__title-text"><?php echo htmlentities($value->desc); ?></h1>
            </div>

            <div class="mdl-card__supporting-text">
                <?php
                    if(is_null($value->best_user_id)){
                        echo 'Prix de départ: ' . htmlentities($value->prix_now) . ' € <br>';
                        echo 'Aucune enchère n\'a été proposée <br>';
                    } else {
                        echo 'Meilleure enchère: ' . htmlentities($value->prix_now) . ' € <br>';
                        echo 'Par: ' . htmlentities($value->prenom) . " " . htmlentities($value->nom) . '<br>';
                    }
                    echo 'Jusqu\'au '.strftime("%d ", $date_stop) . $months[strftime("%m", $date_stop)] . strftime(" %G", $date_stop).'<br>';
                    echo '<span id="clock'.$value->_id.'"></span>';
                ?>

                <script>
                //Script gérant le conpte à rebours: affichage du temps restant, affichage en rouge si bientot fini, désactivation du form si timer fini

                $(document).ready(function() {
                    $('#clock<?php echo $value->_id?>').countdown('<?php echo strftime("%Y/%m/%d", $date_stop); ?>')
                .on('update.countdown', function(event) {

                    if (!$(this).hasClass("red") && event.strftime('%D') < 7) {
                        $(this).addClass("red");
                    }
                    $(this).html(event.strftime('%D jours %H:%M:%S restants'));

                })
                .on('finish.countdown', function() {

                    if ($(this).hasClass("red")) {
                        $(this).removeClass("red");
                    }
                    $(this).html('L\'enchère est terminée.');
                    $(this).parent().next().find(".prix").val('');
                    $(this).parent().next().find(".prix").parent().find("label").html('Objet indisponible...');
                    $(this).parent().next().find(".prix").attr("disabled", true);
                    $(this).parent().next().find(".submit_button").attr("disabled", true);

                });

                var img<?php echo $value->_id ?> = new Image();
                img<?php echo $value->_id ?>.onload = function() {
                    document.getElementById("objet<?php echo $value->_id ?>").getElementsByClassName("titre_card")[0].style.opacity = "1";
                };
                img<?php echo $value->_id ?>.src = './images/get_obj_img.php?id=<?php echo $value->_id ?>';
                });
                </script>
            </div>

        <?php
        if (isset($_SESSION['_id'])) {
            if ($value->proprio_id != $_SESSION['_id']) {
                ?>
                    <form method="POST" action="action/bid.php?id=<?php echo $value->_id; ?>">
                    <div class="mdl-card__supporting-text less_padding">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input prix" type="text" width="100px" name="prix"
                                  id="prix<?php echo $value->_id; ?>" pattern="^\d{1,6}((\.)\d{1,2})?$"/>
                            <label class="mdl-textfield__label" for="prix<?php echo $value->_id; ?>">Montant de l'enchère:</label>
                            <span class="mdl-textfield__error">Entrez un montant valide.</span>
                        </div>
                    </div>
                    <div class="mdl-card__actions">
                        <button class="mdl-button mdl-js-button mdl-js-ripple-effect submit-button" type="submit">
                            Enchérir
                        </button>
                    </div>
                    </form>
                <?php
            } else {
                ?>
                    <form method="POST" action="#">
                    <div class="mdl-card__supporting-text less_padding">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" pattern="[0-9]*" width="100px" name="prix"
                                   id="prix<?php echo $value->_id; ?>" disabled/>
                            <label class="mdl-textfield__label" for="prix<?php echo $value->_id; ?>">Vous ne pouvez pas enchérir votre
                                objet</label>
                        </div>
                    </div>
                    <div class="mdl-card__actions">
                        <button class="mdl-button mdl-js-button mdl-js-ripple-effect submit-button" type="submit"
                                disabled>
                            Enchérir
                        </button>
                    </div>
                    </form>
                <div class="mdl-card__menu">

                    <a href="edit_object.php?id=<?php echo $value->_id ?>" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" id="editobjet<?php echo $value->_id; ?>">
                        <i class="material-icons icone-modifier">edit</i>
                    </a>
                    <span class="mdl-tooltip" for="editobjet<?php echo $value->_id; ?>" style="margin-right: 50px;">
                        Modifier
                    </span>
                </div>
                <?php
            }
        } ?>
        </div>
    <?php
    } // Fin foreach

    echo '</div>';
    echo '<div class="page-selector">';

    //Sélecteur de page
    $query = $db->prepare('SELECT COUNT(_id) AS `count` FROM Objet WHERE date_start <= NOW() AND date_stop >= NOW()');
    $query->execute();

    $resQuery = $query->fetch();

    $pageCount = (int)(($resQuery->count)/$objPerPage);

    if ((int)(($resQuery->count)%$objPerPage)!= 0) $pageCount += 1;

    echo ($_GET['page'] == 1) ? '<a class="mdl-button mdl-js-button mdl-button--icon mdl-button--disabled link-no-style page-link"><</a>'
        : '<a href="objects.php?page='.($_GET['page']-1).'" class="mdl-button mdl-js-button mdl-button--icon link-no-style page-link"><</a>';


    for ($i = $_GET['page']-2; $i < $_GET['page']+3; $i++) {
        if (($i > 0) && ($i < $pageCount+1)) {
            echo ($i == $_GET['page']) ? '<a class="mdl-button mdl-js-button mdl-button--icon mdl-button--disabled link-no-style page-link"><b>'.$i.'</b></a>'
                : '<a href="objects.php?page='.$i.'&order='.$_GET['order'].'&desc='.$_GET['desc'].'" class="mdl-button mdl-js-button mdl-button--icon link-no-style page-link">   '.$i.'   </a>';
        }
    }

    echo ($_GET['page'] == $pageCount) ? '<a class="mdl-button mdl-js-button mdl-button--icon mdl-button--disabled link-no-style page-link">></a>'
        : '<a href="objects.php?page='.($_GET['page']+1).'&order='.$_GET['order'].'&desc='.$_GET['desc'].'" class="mdl-button mdl-js-button mdl-button--icon link-no-style page-link">></a>';


    echo '</div>';
}
include('includes/footer.php');

?>