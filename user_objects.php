<?php
/**
 * Created by PhpStorm.
 * User: Marc-Antoine
 * Date: 06/10/2015
 * Time: 14:03
 */

session_start();
if (!isset($_SESSION['mail'])) {
    header('Location: login.php');
    exit;
}

$months = array('01' => "janvier",
    '02' => "février",
    '03' => "mars",
    '04' => "avril",
    '05' => "mai",
    '06' => "juin",
    '07' => "juillet",
    '08' => "août",
    '09' => "septembre",
    '10' => "octobre",
    '11' => "novembre",
    '12' => "décembre");

include('includes/header.php');

?>

    <div class="titre_page">
        Mes objets
    </div>

    <div class="simple_text">
        Les objets que vous avez déposés sur le site sont affichés ici, triés par date d'ajout. Vous pouvez voir pour chaque objet s'il est actuellement ouvert aux enchères ou non, et vous pouvez modifier vos objets, y compris ceux qui seront proposés ultérieurement.
    </div>

    <div class="list_card_wrapper">

<?php
$objPerPage = 10; //nombre d'objets par page

$_GET['page'] = (isset($_GET['page'])) ? abs($_GET['page']) : 1;

$offset = ($_GET["page"] - 1) * $objPerPage;


// On fait la requete dans la DB Objet pour avoir l'id

$req = $db->prepare('SELECT Objet.`_id`, Objet.nom AS `desc`, Objet.prix_now, Objet.date_start, Objet.date_stop, Objet.best_user_id, User.prenom, User.nom FROM Objet LEFT JOIN User ON Objet.best_user_id = User._id WHERE Objet.proprio_id =:id AND Objet.is_max = FALSE ORDER BY Objet.`_id` DESC LIMIT :perPage OFFSET :off');
$req->bindValue(':off', $offset, PDO::PARAM_INT);
$req->bindValue(':perPage', $objPerPage, PDO::PARAM_INT);
$req->bindValue(':id', $_SESSION['_id'], PDO::PARAM_INT);
$req->execute();


if ($req->rowCount() >= 1) { // Correspondance trouvé dans la DB

    $res = $req->fetchAll();

    foreach ($res as $key => $value) {
        $date_stop = strtotime($value->date_stop);
        $date_start = strtotime($value->date_start);
        ?>

        <div class="mdl-card mdl-shadow--4dp list_card object_card" id="objet<?php echo $value->_id; ?>">

            <div class="mdl-card__title titre_card"
                 style="background: linear-gradient( rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.25) ),url('./images/get_obj_img.php?id=<?php echo $value->_id ?>') center / cover;">
                <h1 class="mdl-card__title-text"><?php echo htmlentities($value->desc); ?></h1>
            </div>

            <div class="mdl-card__supporting-text">

                <?php
                if ($value->date_start <= date("Y-m-d") AND $value->date_stop >= date("Y-m-d")) {
                    if(is_null($value->best_user_id)){
                        echo 'Prix de départ: ' . htmlentities($value->prix_now) . ' € <br>';
                        echo 'Aucune enchère n\'a été proposée <br>';
                    } else {
                        echo 'Meilleure enchère: ' . htmlentities($value->prix_now) . ' € <br>';
                        echo 'Par: ' . htmlentities($value->prenom) . " " . htmlentities($value->nom) . '<br>';
                    }
                } elseif($value->date_start > date("Y-m-d")) {
                    echo 'Prix de départ: ' . htmlentities($value->prix_now) . ' € <br>';
                    echo 'Votre objet sortira le: ' . strftime("%d ", $date_start) . $months[strftime("%m", $date_start)] . strftime(" %G", $date_start) . '<br>';
                } elseif($value->date_stop < date("Y-m-d")) {
                    echo 'Meilleure enchère: ' . htmlentities($value->prix_now) . ' € <br>';
                    echo 'Votre objet est sorti le: ' . strftime("%d ", $date_start) . $months[strftime("%m", $date_start)] . strftime(" %G", $date_start) . '<br>';
                } ?>

                Jusqu'au <?php echo strftime("%d ", $date_stop) . $months[strftime("%m", $date_stop)] . strftime(" %G", $date_stop) . '<br>';
                echo '<span id="clock'.$value->_id.'"></span>';
            ?>
            </div>
            <script>
                $(document).ready( function() {

                    $('#clock<?php echo $value->_id?>').countdown('<?php echo strftime("%Y/%m/%d", $date_stop); ?>').on('update.countdown', function(event) {

                    if (!$(this).hasClass("red") && event.strftime('%D') < 7) {
                        $(this).addClass("red");
                    }
                    $(this).html(event.strftime('%D jours %H:%M:%S restants'));
                    });

                    var img<?php echo $value->_id ?> = new Image();
                    img<?php echo $value->_id ?>.onload = function()
                    {
                        document.getElementById("objet<?php echo $value->_id ?>").getElementsByClassName("titre_card")[0].style.opacity = "1";
                    };
                    img<?php echo $value->_id ?>.src = './images/get_obj_img.php?id=<?php echo $value->_id ?>';
                });
            </script>

            <div class="mdl-card__menu">
            <?php if($value->date_start <= date("Y-m-d") AND $value->date_stop >= date("Y-m-d")){
                ?>
                <div class="mdl-button mdl-button--icon" id="showcased_objet<?php echo $value->_id; ?>">
                    <i class="material-icons icone-modifier no-interaction">visibility</i>
                </div>
                <span class="mdl-tooltip" for="showcased_objet<?php echo $value->_id; ?>"
                      style="margin-right: 50px;">
                    Publié
                </span>
            <?php } else { ?>

                <div class="mdl-button mdl-button--icon no-interaction" id="showcased_objet<?php echo $value->_id; ?>">
                    <i class="material-icons icone-modifier">visibility_off</i>
                </div>
                <span class="mdl-tooltip" for="showcased_objet<?php echo $value->_id; ?>"
                      style="margin-right: 50px;">
                    Non publié
                </span>

                <?php } ?>

                <a href="edit_object.php?id=<?php echo $value->_id ?>"
                   class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect"
                   id="editobjet<?php echo $value->_id; ?>">
                    <i class="material-icons icone-modifier">edit</i>
                </a>
                <span class="mdl-tooltip" for="editobjet<?php echo $value->_id; ?>" style="margin-right: 50px;">
                    Modifier
                </span>
            </div>

        </div>

    <?php
    }
}

echo '</div>';
echo '<div class="page-selector">';

//Sélecteur de page

$query = $db->prepare('SELECT COUNT(_id) AS `count` FROM Objet WHERE proprio_id=:user_id');
$query->bindParam(':user_id', $_SESSION['_id'], PDO::PARAM_INT);
$query->execute();

$resQuery = $query->fetch();

$pageCount = (int)(($resQuery->count)/$objPerPage);

if ((int)(($resQuery->count)%$objPerPage)!= 0) $pageCount += 1;

echo ($_GET['page'] == 1) ? '<a class="mdl-button mdl-js-button mdl-button--icon mdl-button--disabled link-no-style page-link"><</a>'
    : '<a href="user_objects.php?page='.($_GET['page']-1).'" class="mdl-button mdl-js-button mdl-button--icon link-no-style page-link"><</a>';


for ($i = $_GET['page']-2; $i < $_GET['page']+3; $i++) {
    if (($i > 0) && ($i < $pageCount+1)) {
        echo ($i == $_GET['page']) ? '<a class="mdl-button mdl-js-button mdl-button--icon mdl-button--disabled link-no-style page-link"><b>'.$i.'</b></a>'
            : '<a href="user_objects.php?page='.$i.'" class="mdl-button mdl-js-button mdl-button--icon link-no-style page-link">   '.$i.'   </a>';
    }
}

echo ($_GET['page'] == $pageCount) ? '<a class="mdl-button mdl-js-button mdl-button--icon mdl-button--disabled link-no-style page-link">></a>'
    : '<a href="user_objects.php?page='.($_GET['page']+1).'" class="mdl-button mdl-js-button mdl-button--icon link-no-style page-link">></a>';


echo '</div>';


include('includes/footer.php');

?>