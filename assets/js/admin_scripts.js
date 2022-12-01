jQuery(document).ready(function ($) {
    $('.pwa_color').wpColorPicker();
    $('#pwa_options_form').submit(function(event){
        event.preventDefault();
        var pwaForm = $(this);
        $.ajax({
            url : pwaForm.attr('action'),
            data : pwaForm.serialize(),
            type : 'POST',
            success : function( response ){
                console.log(response);
            }
        });

        return false;
    })
});