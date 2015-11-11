<?php
/**
 * Created by PhpStorm.
 * User: Marc-Antoine
 * Date: 06/10/2015
 * Time: 14:03
 */

include('includes/header.php');

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

?>

    <div class="titre_page">
        <img src="./images/logo.png" alt="Logo de Harry Bay"/>
    </div>

    <div class="simple_text">
        Bienvenue sur Harry Bay, le seul site qui a comme nom un jeu de mots plutôt badass. Sur ce site, vous pourrez
        vendre et acheter des objets ayant servi au tournage des films de la saga Harry Potter. C'est en quelque sorte
        un eBay pour Harry Potter (d'où le jeu de mots!).
    </div>

    <!-- Carte de description de Harry Potter -->

    <div id="description">
        <div class="mdl-card mdl-shadow--4dp object_card centre_card">
            <div class="mdl-card__title">
                <h1 class="mdl-card__title-text">La saga Harry Potter</h1>
            </div>
            <div class="mdl-card__supporting-text">
                Harry Potter est une suite romanesque fantasy comprenant sept romans, écrits par J. K. Rowling et parus
                entre 1997 et 2007. Elle raconte les aventures d'un apprenti sorcier nommé Harry Potter et de ses amis
                Ron Weasley et Hermione Granger à l'école de sorcellerie Poudlard. L'intrigue principale de la série met
                en scène le combat du jeune Harry Potter contre un mage noir réputé invincible, Lord Voldemort, qui a
                tué autrefois ses parents<a class="link-no-style mdl-color-text--primary"
                                            href="https://soundcloud.com/smiggleton/harry-potter-dubstep-3">;</a> à la
                tête d'un clan de mages noirs, les Mangemorts, Voldemort cherche depuis des décennies à prendre le
                pouvoir sur le monde des sorciers. Chacun de ces romans ont été adaptés au cinéma par le studio Warner
                Bros.
            </div>
            <div class="mdl-card__actions mdl-card--border">
                <a href="http://www.imdb.com/title/tt0241527/"
                   class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--accent"><i
                        class="material-icons">local_movies</i> Voir
                    sur IMDb</a>
            </div>
        </div>
    </div>

<?php

$req = $db->prepare('SELECT Objet.`_id`, Objet.nom AS `desc`, Objet.prix_now, Objet.date_stop, Objet.proprio_id, User.nom, User.prenom FROM Objet LEFT JOIN User ON Objet.proprio_id = User._id WHERE is_max = 1 LIMIT 1');
$req->execute();

if ($req->rowCount() >= 1) { // Correspondance trouvé dans la DB

    $res = $req->fetch(PDO::FETCH_OBJ);
    $date_stop = strtotime($res->date_stop);
    ?>
    <div id="meilleure_enchere">

        <div class="more_padding">

            <div class="centrer_texte mdl-typography--title-color-contrast">Meilleure vente à ce jour:</div>

            <div class="mdl-card mdl-shadow--4dp object_card centre_card"
                 id="objet<?php echo $res->_id ?>">

                <div class="mdl-card__title titre_card"
                     style="background: linear-gradient( rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.25) ),url('./images/get_obj_img.php?id=<?php echo $res->_id ?>') center / cover;">
                    <h1 class="mdl-card__title-text"><?php echo htmlentities($res->desc); ?></h1>
                </div>

                <div class="mdl-card__supporting-text">
                    Vendu <?php echo htmlentities($res->prix_now); ?> € <br/>
                    par <?php echo ($res->proprio_id == 0) ? "inconnu" : (htmlentities($res->prenom) . " " . htmlentities($res->nom)); ?>
                    <br>
                    le <?php echo strftime("%d ", $date_stop) . $months[strftime("%m", $date_stop)] . strftime(" %G", $date_stop); ?>
                </div>

            </div>

        </div>

        <a class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--accent"
           href="objects.php?page=1&order=0&desc=true"
           style="width: 100% !important;">
            Voir les objets en vente
        </a>

    </div>

    <script>
        $(document).ready(function () {
            var img<?php echo $res->_id ?> = new Image();
            img<?php echo $res->_id ?>.onload = function () {
                document.getElementById("objet<?php echo $res->_id ?>").getElementsByClassName("titre_card")[0].style.opacity = "1";
                //$("#meilleure_enchere").css({"-webkit-filter: blur(10px); -moz-filter: blur");

            };
            img<?php echo $res->_id ?>.src = './images/get_obj_img.php?id=<?php echo $res->_id ?>';
        });
    </script>

    <?php
}

if (!isset($_SESSION['mail'])) {

    ?>

    <div class="invitation">
        <div class="simple_text centrer_texte white">
            Vous souhaitez faire comme tous ces gens sur l'image de fond?<br>
                <a class="mdl-button mdl-button--colored mdl-color-text--accent" href="login.php">Connectez-vous</a> ou
                <a class="mdl-button mdl-button--colored mdl-color-text--accent" href="signin.php">inscrivez-vous</a>
        </div>
    </div>

    <?php
}
include('includes/footer.php');
?>