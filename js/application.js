/**
 * Created by Nicolas POURPRIX on 02/11/2015.
 */

$(document).ready( function() {
    $("#submit_button").on('click', function() {
        if ($("#nom").val()!==""
        && $("#prix_min").val()!==""
        && $("#date_start").val()!==""
        && $("#date_stop").val()!==""
        )
        {
            event.preventDefault();
            $(".mdl-spinner").addClass("is-active");
            $(this).hide();
            $('form').submit();
        }

    });
});