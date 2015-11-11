/**
 * Created by Nicolas POURPRIX on 02/11/2015.
 */

$(document).ready(function () {


    $("#submit_button_add_object").on('click', function () {
        if ($("#nom").val() !== ""
            && $("#prix_min").val() !== ""
            && $("#date_start").val() !== ""
            && $("#date_stop").val() !== ""
        ) {
            event.preventDefault();
            $(".mdl-spinner").addClass("is-active");
            $("#submit_button_add_object").remove();
            $('form').submit();
        }

    });

    $("#edit_user_card").find(".mdl-card__title").on('click', function () {

        if ($("#edit_user_card").find("form").is(":visible")) {
            $("#edit_user_card").find("form").slideToggle();
        }
        else {
            $("#edit_user_card").find("form").slideDown();
            $("#password_card").find("form").slideUp();
            $("#delete_user_card").find("form").slideUp();
        }


    });

    $("#password_card").find(".mdl-card__title").on('click', function () {

        if ($("#password_card").find("form").is(":visible")) {
            $("#password_card").find("form").slideToggle();
        }
        else {
            $("#edit_user_card").find("form").slideUp();
            $("#password_card").find("form").slideDown();
            $("#delete_user_card").find("form").slideUp();
        }

    });

    $("#delete_user_card").find(".mdl-card__title").on('click', function () {

        if ($("#delete_user_card").find("form").is(":visible")) {
            $("#delete_user_card").find("form").slideToggle();
        }
        else {
            $("#edit_user_card").find("form").slideUp();
            $("#password_card").find("form").slideUp();
            $("#delete_user_card").find("form").slideDown();
        }

    });

});