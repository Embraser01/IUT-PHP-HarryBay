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

    <h3 class="centrer_texte">Carte de description de la saga:</h3>

<!-- Carte de description de Harry Potter -->

    <section class="section--center mdl-grid mdl-grid--no-spacing mdl-shadow--8dp film_card">
        <header
            class="section__play-btn mdl-cell mdl-cell--3-col-desktop mdl-cell--2-col-tablet mdl-cell--4-col-phone mdl-color--red-500 film_desc">
        </header>
        <div class="mdl-card mdl-cell mdl-cell--9-col-desktop mdl-cell--6-col-tablet mdl-cell--4-col-phone">
            <div class="mdl-card__title">
                <h1 class="mdl-card__title-text">La saga Harry Potter</h1>
            </div>
            <div class="mdl-card__supporting-text">
                Blah blah blah on parle des films, du succès qu'ils ont eu, blah blah, les conventions, blah blah, on
                leur met <a href="https://soundcloud.com/smiggleton/harry-potter-dubstep-3">un lien dubstep</a> (rule
                34.8 is life), on leur spoile la fin, même s'il faut pas dire que Dumbledore s'endort à la fin. On
                brode, etc etc...
            </div>
            <div class="mdl-card__actions mdl-card--border">
                <a href="http://www.imdb.com/title/tt0241527/" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-color-text--accent"><i class="material-icons">local_movies</i> Voir
                    sur IMDb</a>
            </div>
        </div>
    </section>

    <h3 class="centrer_texte">Meilleure vente à ce jour:</h3>

<?php

$req = $db->prepare('SELECT Objet.`_id`, Objet.nom AS `desc`, Objet.prix_now, Objet.date_stop, User.nom, User.prenom FROM Objet JOIN User ON Objet.proprio_id = User._id WHERE is_max = 1 LIMIT 1');
$req->execute();

if ($req->rowCount() >= 1) { // Correspondance trouvé dans la DB

    $res = $req->fetch(PDO::FETCH_OBJ);
    $date_stop = strtotime($res->date_stop);
    ?>

    <div class="mdl-card mdl-shadow--4dp object_card centre_card centrer_texte">

        <div class="mdl-card__title titre_card"
             style="background: linear-gradient( rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.25) ),url('./images/get_obj_img.php?id=<?php echo $res->_id ?>') center / cover;">
            <h1 class="mdl-card__title-text"><?php echo htmlentities($res->desc); ?></h1>
        </div>

        <div class="mdl-card__supporting-text">
            Meilleure enchère: <?php echo htmlentities($res->prix_now); ?> € <br/>
            Par: <?php echo htmlentities($res->prenom) . " " . htmlentities($res->nom); ?><br>
            Jusqu'au <?php echo strftime("%d ", $date_stop) . $months[strftime("%m", $date_stop)] . strftime(" %G", $date_stop); ?>
        </div>

        <div class="mdl-card__actions mdl-card--border">
            <form method="POST" action="action/bid.php">
                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                    <input class="mdl-textfield__input" type="text" pattern="[0-9]*" width="100px" name="prix"
                           id="prix"/>
                    <label class="mdl-textfield__label" for="prix">Montant de l'enchère:</label>
                    <span class="mdl-textfield__error">Entrez un montant valide.</span>
                </div>
                <div class="mdl-layout-spacer"></div>
                <button class="mdl-button mdl-js-button mdl-js-ripple-effect submit-button" type="submit">
                    Enchérir
                </button>
            </form>
        </div>

    </div>

    <?php
}
include('includes/footer.php');
?>