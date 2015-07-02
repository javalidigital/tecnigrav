<?php
return array(
    'slider03' => array(
        'class' => 'slider slider3',
        'label' => sprintf( esc_attr__( 'Selling Slider', 'iworks-sliders' ), 3 ),
        'title' => true,
        'image' => true,
        'text' => true,

        'link' => true,
        'link_text' => true,
        'link_template' => '<p><a href="%LINK_URL%" class="button">%LINK_TEXT%</a></p>',

        'link_more' => false,
        'video' => false,
        'pattern' => 7,
        'thumbnails_list_element' => false,
        'form_title' => false,
    ),
	
    'slider08' => array(
        'class' => 'slider slider8',
        'label' => sprintf( esc_attr__( 'Premium Slider', 'iworks-sliders' ), 8 ),
        'title' => true,
        'image' => true,
        'text' => true,

        'link' => true,
        'link_text' => true,
        'link_template' => '<p><a href="%LINK_URL%" class="button">%LINK_TEXT%</a></p>',

        'link_more' => false,
        'video' => false,
        'pattern' => false,
        'thumbnails_list_element' => false,
        'form_title' => false,
    ),	

    'slider11' => array(
        'class' => 'slider slider11',
        'label' => sprintf( esc_attr__( 'Landing Slider', 'iworks-sliders' ), 11 ),
        'title' => true,
        'image' => true,
        'text' => true,
        'email' => true,
        'service' => true,

        'link' => true,
        'link_text' => true,
        'link_template' => '<p class="more"><a href="%LINK_URL%">%LINK_TEXT%</a></p>',

        'link_more' => false,
        'video' => false,
        'pattern' => 3,
        'thumbnails_list_element' => false,

        'footer_button' => '%FOOTER_BUTTON_TEXT%',
        'form_title' => true,
    ),
);
