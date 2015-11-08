<?php
/**
 * Created by PhpStorm.
 * User: Marc-Antoine
 * Date: 06/10/2015
 * Time: 14:03
 */

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

?>

    <div class="titre_page">
        Enchères
    </div>

    <div class="list_card_wrapper">

<?php

$objPerPage = 10; //nombre d'objets par page

if (!isset($_GET['page']))
    $_GET['page'] = 1;

$offset = ($_GET["page"] - 1) * $objPerPage;


// On fait la requete dans la DB Objet pour avoir l'id

$req = $db->prepare('SELECT Objet.`_id`, Objet.nom AS `desc`, Objet.prix_now, Objet.date_stop, Objet.proprio_id, Objet.best_user_id, User.prenom, User.nom FROM Objet LEFT JOIN User ON Objet.best_user_id = User._id WHERE Objet.date_start <= NOW() AND date_stop >= NOW() ORDER BY Objet.date_start DESC, Objet.`_id` DESC LIMIT :perPage OFFSET :off');
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
                $(this).parent().next().find("#prix").val('');
                $(this).parent().next().find("#prix").parent().find("label").html('Objet indisponible...');
                $(this).parent().next().find("#prix").attr("disabled", true);
                $(this).parent().next().find("#submit_button").attr("disabled", true);

            });

            var img<?php echo $value->_id ?> = new Image();
            img<?php echo $value->_id ?>.onload = function()
            {
                //alert(document.getElementById("objet<?php echo $value->_id ?>").getElementsByClassName("titre_card")[0].nodeName);
                document.getElementById("objet<?php echo $value->_id ?>").getElementsByClassName("titre_card")[0].style.opacity = "1";
            };
            img<?php echo $value->_id ?>.src = './images/get_obj_img.php?id=<?php echo $value->_id ?>';
            });


            /*$(document).ready(function() {
                $("#annuler<?php //echo $value->_id ?>").on('click', function() {
                $(this).parentsUntil("mdl-card__supporting-text", true).next().find("#prix").val('');
                $(this).parentsUntil("mdl-card__supporting-text", true).next().find("#prix").parent().find("label").html('Enchère désactivée...');
                $(this).parentsUntil("mdl-card__supporting-text", true).next().find("#prix").attr("disabled", true);
                $(this).parentsUntil("mdl-card__supporting-text", true).next().find("#submit_button").attr("disabled", true);
            });
            });*/


            </script>

<!--
            <br><div class="mdl-button mdl-js-button" id="annuler<?php echo $value->_id ?>">
                Désactiver enchère
            </div>
-->


        </div>

        <?php
        if (isset($_SESSION['_id'])) {
            if ($value->proprio_id != $_SESSION['_id']) {
                ?>
                <div class="mdl-card__actions mdl-card--border">
                    <form method="POST" action="action/bid.php?id=<?php echo $value->_id; ?>">
                    <div class="custom_textfield">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" width="100px" name="prix"
                                   id="prix" pattern="^\d{1,6}((,|\.)\d{1,2})?$"/>
                            <label class="mdl-textfield__label" for="prix">Montant de l'enchère:</label>
                            <span class="mdl-textfield__error">Entrez un montant valide.</span>
                        </div>
                        </div>
                        <button class="mdl-button mdl-js-button mdl-js-ripple-effect submit-button" id="submit_button" type="submit">
                            Enchérir
                        </button>
                    </form>
                </div>
                <!--<div class="mdl-card__menu">
                    <div class="mdl-button mdl-js-button mdl-js-ripple-effect" id="annuler<?php echo $value->_id; ?>">
                        <i class="material-icons icone-modifier">thumb_down</i>
                    </div>
                </div>-->

                </div>

                <?php
            } else {
                ?>
                <div class="mdl-card__actions mdl-card--border">
                    <form method="POST" action="#">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                            <input class="mdl-textfield__input" type="text" pattern="[0-9]*" width="100px" name="prix"
                                   id="prix" disabled/>
                            <label class="mdl-textfield__label" for="prix">Vous ne pouvez pas enchérir votre
                                objet</label>
                        </div>
                        <div class="mdl-layout-spacer"></div>
                        <button class="mdl-button mdl-js-button mdl-js-ripple-effect submit-button" type="submit"
                                disabled>
                            Enchérir
                        </button>
                    </form>
                </div>
                <div class="mdl-card__menu">

                    <a href="edit_object.php?id=<?php echo $value->_id ?>" class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" id="editobjet<?php echo $value->_id; ?>">
                        <i class="material-icons icone-modifier">edit</i>
                    </a>
                    <span class="mdl-tooltip" for="editobjet<?php echo $value->_id; ?>" style="margin-right: 50px;">
                        Modifier
                    </span>
                </div>



                </div>
                <?php
            }
        } else {
            ?>

                </div>

            <?php
        }
    }

    echo '</div>';
    echo '<div class="page-selector">';

    //Sélecteur de page
    $query = $db->prepare('SELECT COUNT(_id) AS `count` FROM Objet WHERE date_start <= NOW() AND date_stop >= NOW()');
    $query->execute();

    $resQuery = $query->fetch();

    if ((int)(($resQuery->count)%$objPerPage)==0) {
        $pageCount = (int)(($resQuery->count)/$objPerPage);
    }
    else {
        $pageCount = (int)(($resQuery->count)/$objPerPage)+1;
    }

    if ($_GET['page'] == 1) {
        echo '<a class="mdl-button mdl-js-button mdl-button--icon mdl-button--disabled link-no-style page-link"><</a>';
    }
    else {
        echo '<a href="objects.php?page='.($_GET['page']-1).'" class="mdl-button mdl-js-button mdl-button--icon link-no-style page-link"><</a>';
    }

    for ($i = $_GET['page']-2; $i < $_GET['page']+3; $i++) {
        if (($i > 0) && ($i < $pageCount+1)) {
            if ($i == $_GET['page']) {
                echo '<a class="mdl-button mdl-js-button mdl-button--icon mdl-button--disabled link-no-style page-link"><b>'.$i.'</b></a>';
            }
            else {
                echo '<a href="objects.php?page='.$i.'" class="mdl-button mdl-js-button mdl-button--icon link-no-style page-link">   '.$i.'   </a>';
            }
        }
    }

    if ($_GET['page'] == $pageCount) {
        echo '<a class="mdl-button mdl-js-button mdl-button--icon mdl-button--disabled link-no-style page-link">></a>';
    }
    else {
        echo '<a href="objects.php?page='.($_GET['page']+1).'" class="mdl-button mdl-js-button mdl-button--icon link-no-style page-link">></a>';
    }

    echo '</div>';
}

include('includes/footer.php');

?>