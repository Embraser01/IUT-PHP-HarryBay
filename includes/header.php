<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('errors.php');
include('success.php');

?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>

        <title><?php if (isset($title)) {
                echo $title . ' | ';
            } ?>Harry Bay</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="stylesheet"
              href="https://storage.googleapis.com/code.getmdl.io/1.0.5/material.blue_grey-red.min.css"/>
        <link rel="stylesheet" href="styles/default.css">
        <link rel="icon" type="image/png" href="images/favicon.png">


        <link
            href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en"
            rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

        <script src="js/material.min.js"></script>
        <script src="js/jquery-2.1.4.min.js"></script>
        <script src="js/jquery.countdown.min.js"></script>
        <script src="js/harrykonami.js"></script>


        <meta charset="UTF-8"/>

    </head>

<body>
<div class="mdl-layout mdl-js-layout">
    <header class="mdl-layout__header mdl-layout--fixed-header">
        <div class="mdl-layout__header-row mdl-layout--fixed-header layout-no-menu">
            <!-- Title -->
            <span class="mdl-layout-title "><a class="link-no-style mdl-color-text--white " href="index.php">Harry Bay</a></span>
            <!-- Add spacer, to align navigation to the right -->
            <div class="mdl-layout-spacer"></div>
            <!-- Navigation -->
            <nav class="mdl-navigation">
                <a class="mdl-navigation__link" href="index.php">Accueil</a>
                <a class="mdl-navigation__link" href="objects.php">Enchères</a>

                <?php
                if (isset($_SESSION['mail']))
                    echo '<a class="mdl-navigation__link" href="user_objects.php">Mes objets</a>';
                ?>

                <a class="mdl-navigation__link" href="add_object.php">Ajouter un objet</a>
                <?php
                if (!isset($_SESSION['mail'])) {
                    echo '<a class="mdl-navigation__link" href="login.php">Se connecter</a>';
                } else {

                    echo '<a class="mdl-navigation__link" href="action/logout.php">Se déconnecter</a>';

                    echo ($_SESSION['_id'] == 1 OR $_SESSION['_id'] == 4 OR $_SESSION['_id'] == 7)
                        ? '<div class="mdl-navigation__link less_padding"><i class="material-icons" style="vertical-align: middle; !important">verified_user</i></div>'
                        : '<div class="mdl-navigation__link less_padding">|</div>';
                    echo '<a class="mdl-navigation__link" href="edit_user.php?id=' . $_SESSION['_id'] . '">' . $_SESSION['prenom'] . ' ' . $_SESSION['nom'] . '</a>';
                } ?>
            </nav>
        </div>
    </header>
    <main class="mdl-layout__content">

<?php
require __DIR__ . '/../lib/class.Database.php';

//Affichage de l'éventuel message d'erreur

if (isset($_GET['error'])) display_error($_GET['error']);
elseif (isset($_GET['success'])) display_success($_GET['success']); ?>