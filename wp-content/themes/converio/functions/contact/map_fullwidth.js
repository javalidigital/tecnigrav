function converio_map_fullwidth(mode) {
    var select = jQuery('#page_template option:selected');
    if ( !select ) {
        return;
    }
    var metabox = jQuery('#converio_map_fullwidthadd_meta_boxes');
    if ( 'contact-fullwidth-map.php' == select.val() ) {
        if ( 'init' == mode ) {
            metabox.show();
        } else {
            metabox.slideDown();
        }
    } else {
        if ( 'init' == mode ) {
            metabox.hide();
        } else {
            metabox.slideUp();
        }
    }
    console.log(mode);
}

jQuery( document ).ready(function() {
    converio_map_fullwidth('init');
    jQuery('#page_template').on('change', function() {
        converio_map_fullwidth('toggle');
    });
});

