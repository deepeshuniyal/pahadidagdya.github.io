(function ($) {
    var completeVar = Array('100(Ultra Light)', '200 (Light)', '300 (Book)', '400 (Normal)', '500 (Medium)', '600 (Semi Bold)', '700 (Bold)', '800 (Extra Bold)', '900 (Ultra Bold)');

    $('#customize-control-menu_font select').live('change', function () {
        var menuFontVariant = $(this).val(),
            arrMenuFontVariant = menuFontVariant.split('__'),
            arrVariant = arrMenuFontVariant[1].split(','),
            currentVal = $('#customize-control-menu_font_weight select').val(),
            currentValExist = false;

        $('#customize-control-menu_font_weight select option').remove();
        for (i = 0; i < arrVariant.length; i++) {
            var variant = arrVariant[i].replace('italic', '');
            if (currentVal == variant) currentValExist = true;
            $('#customize-control-menu_font_weight select').append('<option value="' + variant + '">' + completeVar[(variant / 100) - 1] + '</option>');
        }
        if (!currentValExist) {
            $('#customize-control-menu_font_weight select').val(variant).change();
            $('#customize-control-menu_font_weight select option[value="' + variant + '"]').prop("selected", "selected").change();
        } else {
            $('#customize-control-menu_font_weight select').val(currentVal).change();
            $('#customize-control-menu_font_weight select option[value="' + currentVal + '"]').prop("selected", "selected").change();
        }
    });

    $('#customize-control-heading_font select').live('change', function () {
        var headingFontVariant = $(this).val(),
            arrHeadingFontVariant = headingFontVariant.split('__'),
            arrVariant = arrHeadingFontVariant[1].split(','),
            currentVal = $('#customize-control-menu_font_weight select').val(),
            currentValExist = false;

        $('#customize-control-heading_font_weight select option').remove();
        for (i = 0; i < arrVariant.length; i++) {
            var variant = arrVariant[i].replace('italic', '');
            if (currentVal == variant) currentValExist = true;
            $('#customize-control-heading_font_weight select').append('<option value="' + variant + '">' + completeVar[(variant / 100) - 1] + '</option>');
        }
        if (!currentValExist) {
            $('#customize-control-heading_font_weight select').val(variant).change();
            $('#customize-control-heading_font_weight select option[value="' + variant + '"]').prop("selected", "selected").change();
        } else {
            $('#customize-control-heading_font_weight select').val(currentVal).change();
            $('#customize-control-heading_font_weight select option[value="' + currentVal + '"]').prop("selected", "selected").change();
        }
    });

})(jQuery);