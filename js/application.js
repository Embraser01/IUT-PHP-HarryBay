/**
 * Created by Nicolas POURPRIX on 02/11/2015.
 */

$(document).ready(function () {
    $("#submit_button").on('click', function () {
        if ($("#nom").val() !== ""
            && $("#prix_min").val() !== ""
            && $("#date_start").val() !== ""
            && $("#date_stop").val() !== ""
        ) {
            event.preventDefault();
            $(".mdl-spinner").addClass("is-active");
            $(this).hide();
            $('form').submit();
        }

    });

    $("#edit_card").find(".mdl-card__title").on('click', function () {

        if ($("#edit_card").find("form").is(":visible")) {
            $("#edit_card").find("form").slideToggle();
        }
        else {
            $("#edit_card").find("form").slideDown();
            $("#password_card").find(".mdl-card__supporting-text").slideUp();
            $("#delete_user_card").find(".mdl-card__supporting-text").slideUp();
        }


    });

    $("#password_card").find(".mdl-card__title").on('click', function () {

        if ($("#password_card").find(".mdl-card__supporting-text").is(":visible")) {
            $("#password_card").find(".mdl-card__supporting-text").slideToggle();
        }
        else {
            $("#edit_card").find("form").slideUp();
            $("#password_card").find(".mdl-card__supporting-text").slideDown();
            $("#delete_user_card").find(".mdl-card__supporting-text").slideUp();
        }

    });

    $("#delete_user_card").find(".mdl-card__title").on('click', function () {

        if ($("#delete_user_card").find(".mdl-card__supporting-text").is(":visible")) {
            $("#delete_user_card").find(".mdl-card__supporting-text").slideToggle();
        }
        else {
            $("#edit_card").find("form").slideUp();
            $("#password_card").find(".mdl-card__supporting-text").slideUp();
            $("#delete_user_card").find(".mdl-card__supporting-text").slideDown();
        }

    });

});