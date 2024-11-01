jQuery(document).on('click', '#btn219, #ssaclose', function () {
    var href = myScript.pluginsUrl;
    jQuery('#modal219')
            .load(href + '/public/ssainfo.php')
            .fadeToggle({
                'duration': 'fast',
                'done': function () {
                    var windowHeight = jQuery(window).height();
                    var windowWidth = jQuery(window).width();
                    var modalheight = jQuery('#modal219').height();
                    jQuery('#ssabody').css('height', modalheight - 67 + 'px');
                    if (windowWidth < 1100) {
                        jQuery('#modal219').css('width', '98%');
                        jQuery('#modal219').css('height', '100%');
                        jQuery('#modal219').css('margin', '10px 5px 0px 5px;');
                        jQuery('#ssabody').css('height', windowHeight - jQuery('#ssaheader').height() - 30);
                        jQuery('#ssabody').css('padding', '10px 10px 40px 10px');
                        jQuery('#ssaheader').css('text-align', 'left').css('padding-left', '10px');
                        jQuery('#ssaheader span').css('font-size', '80%');
                        jQuery('#ssabody button').css('font-size', '12px!important');
                        jQuery('#ssabody button').css('font-weight', '200!important');
                        jQuery('#ssaclose').css('right', '11px');
                    }
                    if (jQuery('#modal219').css('display') === 'block') {
                        jQuery(this).before('<div id="modal219bg"/>');
                        if (jQuery(document).height() > jQuery(window).height()) {
                            var scrollTop = (jQuery('html').scrollTop()) ? jQuery('html').scrollTop() : jQuery('body').scrollTop(); // Works for Chrome, Firefox, IE...
                            jQuery('html').toggleClass('noscroll');
                            jQuery('html').css('top', -scrollTop);

                        }
                    } else {
                        jQuery('#modal219bg').remove();
                        jQuery('#modal219').empty();
                        var scrollTop = parseInt(jQuery('html').css('top'));
                        jQuery('html').toggleClass('noscroll');
                        jQuery('html,body').scrollTop(-scrollTop);
                    }
                }
            });
    return false;

});

jQuery(document).on('click', '.ssaaccordion', function () {
    jQuery(this).toggleClass("ssaactive");
    var elem = jQuery(this).next('.ssapanel');
    if (elem.css('max-height') === '0' || elem.css('max-height') === '0px') {
        var newHeight = elem.prop('scrollHeight') + "px";
        elem.css('max-height', newHeight);

    } else {
        elem.css('max-height', '0px');
    }

});


