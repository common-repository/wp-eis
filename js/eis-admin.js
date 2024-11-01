jQuery(document).ready(function($) {

    $('.pickcolor').bind('click', function(e) {
        colorPicker = $(this).parent().find('div.colorpicker');
        input = $(this).prev('input');
        
        $.farbtastic($(colorPicker), function(a) { $(input).val(a).css('background', a); });
        
        colorPicker.show();
        e.preventDefault();
        
        $(document).bind('mousedown', function() { $(colorPicker).hide(); });
        $(this).bind('click', function() { $(colorPicker).toggle(); });
    });

    $('.defaultcolor').bind('click', function(e) {
        var input = $(this).parent('div').find('input.color'), 
            defaultColor = input.data('default-color');
        e.preventDefault();

        input.attr('value', defaultColor);
        input.css({'background-color': defaultColor});
    });

    $('.wp-eis-add-images .upload-flash-bypass').remove();
    $('.wp-eis-add-images .max-upload-size').wrap('<p />');
})