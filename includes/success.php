<?php

function display_success($num_success) {
    $num_success = intval($num_success);
    if (!is_int($num_success)) return;

    echo '<p class="success"><i class="material-icons">done</i><br/>';

    switch ($_GET['success']) {
        case 00:
            echo 'Votre objet a bien été ajouté';
            break;
        case 10:
            echo 'Votre enchère a bien été prise en compte';
            break;
        case 20:
            echo 'La base a été épurée';
            break;
        case 30:
            echo 'L\'objet a bien été supprimé.';
            break;
        case 40:
            echo 'Votre compte a bien été supprimé. Au revoir. :(';
            break;
        case 50:
            echo 'L\'objet a bien été modifié.';
            break;
        case 60:
            echo 'Votre mot de passe a bien été changé.';
            break;
        case 70:
            echo 'Votre compte a été mis à jour';
            break;
        default:
            echo 'Un message de félicitations devait s\'afficher ici, mais celui ci n\'a pas été configuré, car il n\'a pas été prévu par les développeurs.';
    }
    echo '</p><hr>';
}