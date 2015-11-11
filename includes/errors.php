<?php

function display_error ($num_error) {
    $num_error = intval($num_error);
    if (!is_int($num_error)) return;

    echo '<p class="error"><i class="material-icons md-48">error</i><br/>';

    switch ($_GET['error']) {
        case 2:
            echo 'L\'image que vous avez envoyée n\'est pas valide (extension, poids ou erreur d\'envoi).';
            break;
        case 3:
            echo 'Les informations que vous avez envoyées ne sont pas correctes.';
            break;
        case 4:
            echo 'Vous devez <a href="login.php">vous connecter</a> pour proposer une enchère.';
            break;


        case 12:
            echo 'Vous ne pouvez pas enchérir votre propre enchère.';
            break;
        case 13:
            echo 'Vous ne pouvez plus enchérir pour le moment.';
            break;
        case 14:
            echo 'Vous ne pouvez pas proposer une enchère inférieure à l\'enchère actuelle.';
            break;
        case 15:
            echo 'L\'objet que vous essayez d\'enchérir n\'éxiste pas.';
            break;
        case 16:
            echo 'Vous devez <a href="login.php">vous connecter</a> pour proposer une enchère.';
            break;


        case 22:
            echo 'Aucun objet à enlever';
            break;


        case 32:
            echo 'Cet objet est affiché sur la page d\'accueil. L\'objet n\'a pas été supprimé.';
            break;
        case 34:
            echo 'L\'objet que vous essayez de modifier n\'éxiste pas.';
            break;
        case 36:
            echo 'Vous devez <a href="login.php">vous connecter</a> pour modifier un objet.';
            break;


        case 42:
            echo 'Vous ne pouvez pas supprimer votre compte pour le moment, car il y a un objet dont vous êtes le meilleur enchérisseur.';
            break;
        case 44:
            echo 'Vous êtes déconnecté. Veuillez vous reconnecter pour supprimer votre compte.';
            break;

        case 52:
            echo 'La date de fin d\'enchère n\'est pas correcte.';
            break;
        case 53:
            echo 'La date de mise en ligne n\'est pas correcte.';
            break;
        case 55:
            echo 'Vous êtes déconnecté. Veuillez vous reconnecter pour modifier vos objets.';
            break;


        case 62:
            echo 'Le mot de passe actuel est incorrect.';
            break;
        case 64:
            echo 'Les deux mots de passe sont différents.';
            break;
        case 67:
            echo 'Vous êtes déconnecté. Veuillez vous reconnecter pour modifier votre mot de passe.';
            break;


        case 76:
            echo 'Vous êtes déconnecté. Veuillez vous reconnecter pour modifier les informations de votre compte.';
            break;


        case 81:
            echo 'Mauvais Email/Mot de passe.';
            break;
        case 82:
            echo 'Vous êtes déjà connecté, <a href="action/logout.php">se déconnecter</a>.';
            break;
        case 83:
            echo 'C\est bête mais il faut remplir les zones de texte pour pouvoir se connecter.';
            break;


        case 91:
            echo 'Cet utilisateur existe déjà.'; break;
        case 92:
            echo 'Mauvais mot de passe'; break;
        case 93:
            echo 'Vous êtes déjà connecté, <a href="action/logout.php">Se déconnecter</a>.'; break;
        case 94:
            echo 'Veuillez renseigner tous les champs.'; break;


        case 43:
        case 72:
            echo 'Le mot de passe est erroné.';
            break;
        case 46:
        case 54:
        case 65:
        case 74:
            echo 'Les champs ne sont pas tous renseignés.';
            break;
        case 63:
        case 73:
            echo 'Ce compte n\'existe pas.';
            break;
        case 66:
        case 75:
            echo 'Vous n\'êtes pas conecté avec le compte adéquat. Veuillez vous reconnecter avec le compte que vous voulez modifier.';
            break;
        default:
            echo 'Un problème est survenu.';
    }
    echo '</p><hr>';
}