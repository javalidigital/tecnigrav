jQuery( document ).ready( function( $ ) {
    var kind_id,iworks_sliders_setup_fields,slide_data;

    kind_id = '#'+iworks_slider_vars.option_global_name;
    slider = '#input_'+iworks_slider_vars.option_name+'_kind';

    iworks_sliders_setup_fields = function(slider_type) {

        if( iworks_slider_vars.slide != $('input[name=post_type]').val()) {
            return;
        }

        if('undefined' == typeof(slider_type) ) {
            return;
        }

        $('#postdivrich, #postimagediv, #iworks_sliders_video, #iworks_sliders_link_url, #iworks_sliders_link_text, #iworks_sliders_link_classes, #iworks_sliders_link_more_url, #iworks_sliders_link_more_text, #iworks_sliders_info').hide(); 
        if(slider_type) {
            slide_data = iworks_sliders[slider_type];
            if ( 'undefined' != typeof( slide_data ) ) {
                $('#iworks_sliders_info').hide();
                if( slide_data.text ) {
                    $('#postdivrich').show();
                }
                if( slide_data.image ) {
                    $('#postimagediv').show();
                }
                if ( slide_data.video ) {
                    $('#iworks_sliders_video').show();
                }
                if ( slide_data.link ) {
                    $('#iworks_sliders_link_url').show();
                }
                if ( slide_data.link_text ) {
                    $('#iworks_sliders_link_text').show();
                }
                if ( slide_data.link_classes ) {
                    $('#iworks_sliders_link_classes').show();
                    o = $('#iworks_sliders_link_classes select');
                    for( i = 0; i < slide_data.link_classes.length; i++ ) {
                        v = slide_data.link_classes[i];
                        if ( v == $('#iworks_sliders_link_classes_hidden').val() ) {
                            o.append('<option value="'+v+'" selected="selected">'+v+'</option>');
                        } else {
                            o.append('<option value="'+v+'">'+v+'</option>');
                        }
                    }
                }
                if ( slide_data.link_more ) {
                    $('#iworks_sliders_link_more_url').show();
                    $('#iworks_sliders_link_more_text').show();
                }
            }
        }
    }

    select_slider_type_by_slider_id = function(slider_id) {
        var data = {
            action: 'get_slider_kind',
            slider_id: slider_id
        };
        $.post(ajaxurl, data, function(response) {
            iworks_sliders_setup_fields( response );
        });
    }

    $(kind_id).bind( 'change', function() {
        select_slider_type_by_slider_id( $(this).val() );
    });

    $(slider).bind( 'change', function() {
        $('#iworks_sliders_pattern input').removeAttr('value');
        show_slider_fields_by_slider_type( $(this).val() );
    });

    show_slider_fields_by_slider_type = function( slider_type ) {
        if( iworks_slider_vars.slider != $('input[name=post_type]').val()) {
            return;
        }
        if (slider_type) {
            slider_settings_data = iworks_sliders[slider_type];
            if ( 'undefined' != typeof( slider_settings_data ) ) {
                $('#iworks_sliders_pattern, #postdivrich, .text_before_message, #iworks_sliders_footer_button_text, #iworks_sliders_footer_link_more_text, #iworks_sliders_email, #iworks_sliders_service, #iworks_sliders_footer_button_url, #iworks_sliders_footer_link_more_url, #iworks_sliders_form_title').hide();
                if( slider_settings_data.slider_text ) {
                    $('#postdivrich').show();
                    if( slider_settings_data.text_before_message ) {
                        $('.text_before_message.'+slider_type).show();
                    }
                }
                if ( slider_settings_data.pattern ) {
                    $('#iworks_sliders_pattern').show();
                    if ( '' == $('#iworks_sliders_pattern input').val()) {
                        $('#iworks_sliders_pattern input').val(parseInt(slider_settings_data.pattern));
                    }
                }
                if ( slider_settings_data.footer_button ) {
                    $('#iworks_sliders_footer_button_text').show();
                }
                if ( slider_settings_data.footer_link_more ) {
                    $('#iworks_sliders_footer_link_more_text').show();
                }
                if ( slider_settings_data.footer_button_url ) {
                    $('#iworks_sliders_footer_button_url').show();
                }
                if ( slider_settings_data.footer_link_more_url ) {
                    $('#iworks_sliders_footer_link_more_url').show();
                }
                if ( slider_settings_data.email ) {
                    $('#iworks_sliders_email').show();
                }
                if ( slider_settings_data.service ) {
                    $('#iworks_sliders_service').show();
                }
                if ( slider_settings_data.form_title ) {
                    $('#iworks_sliders_form_title').show();
                }
            }
        }
    }

    if ( $(kind_id) ) {
        iworks_sliders_setup_fields( select_slider_type_by_slider_id( $(kind_id).val() ) );
    }

    if ( $(slider) ) {
        show_slider_fields_by_slider_type( $(slider).val() );
    }

});

