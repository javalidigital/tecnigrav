<?php
return array(
    'slider01' => array(
        'class' => 'slider slider1',
        'label' => __( 'Best Slider', 'iworks-sliders' ),
        'title' => true,
        'image' => true,
        'text' => true,

        'link' => true,
        'link_text' => true,
        'link_template' => '<a href="%LINK_MORE_URL%" class="button">%LINK_MORE_TEXT%</a>',

        'link_more' => true,
        'link_more_template' => '<a href="%LINK_URL%">%LINK_TEXT%</a>',

        'video' => false,
        'pattern' => false,
        'thumbnails_list_element' => false,
        'form_title' => false,
    ),
    'slider02' => array(
        'class' => 'slider slider2',
        'label' => __('Slide Show Slider', 'iworks-slider'),
        'title' => true,
        'image' => true,
        'text' => false,
        'link' => false,
        'link_text' => false,
        'video' => false,
        'pattern' => false,
        'thumbnails_list_element' => false,
        'form_title' => false,
    ),
    'slider03' => array(
        'class' => 'slider slider3',
        'label' => __('Selling Slider', 'iworks-slider'),
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
    'slider04' => array(
        'class' => 'slider slider4',
        'label' => __('Simple Slider', 'iworks-slider'),
        'title' => true,
        'image' => true,
        'text' => true,

        'link' => true,
        'link_text' => true,
        'link_template' => '<p><a href="%LINK_URL%" class="button">%LINK_TEXT%</a></p>',

        'link_more' => false,
        'video' => '<div class="video">%IMG%</div>',
        'pattern' => 9,
        'thumbnails_list_element' => false,
        'form_title' => false,
    ),
    'slider05' => array(
        'class' => 'slider5',
        'label' => __('Form Slider', 'iworks-slider'),
        'title' => true,
        'image' => true,
        'text' => false,
        'email' => true,

        'slider_text' => true,
        'text_before_message' => __( 'Enter form description.', 'iworks-slider' ),

        'link' => false,
        'link_text' => false,
        'video' => false,
        'pattern' => 1,
        'thumbnails_list_element' => false,
        'form_title' => false,
    ),
    'slider06' => array(
        'class' => 'slider slider6',
        'label' => __('Featured Article Slider', 'iworks-slider'),
        'title' => true,
        'image' => true,
        'text' => false,

        'link' => true,
        'link_text' => false,

        'link_more' => false,

        'video' => false,
        'pattern' => 7,
        'thumbnails_list_element' => '<li><a href="#"><span class="img-border">%IMG%</span></a></li>',
        'thumbnail_width' => 160,
        'thumbnail_height' => 96,
        'form_title' => false,
    ),
    'slider07' => array(
        'class' => 'slider7',
        'label' => __('Promo Slider', 'iworks-slider'),
        'title' => true,
        'image' => true,
        'text' => true,

        'link' => false,
        'link_text' => false,
        'link_classes' => array( 'camera', 'info', 'cart', 'people', 'mail' ),

        'link_more' => false,

        'video' => '<div class="video">%IMG%</div>',
        'pattern' => 5,

        'thumbnails_list_element' => '<li><a href="#" class="%LINK_CLASSES%">%TITLE%</a></li>',

        'footer_button' => '<a href="%FOOTER_BUTTON_URL%" class="button">%FOOTER_BUTTON_TEXT%</a>',
        'footer_button_url' => true,
        'footer_link_more' => '<span class="more"><a href="%FOOTER_LINK_MORE_URL%">%FOOTER_LINK_MORE_TEXT%</a></span>',
        'footer_link_more_url' => true,
        'form_title' => false,
    ),
    'slider08' => array(
        'class' => 'slider slider8',
        'label' => __('Premium Slider', 'iworks-slider'),
        'title' => true,
        'image' => true,
        'text' => true,

        'link' => true,
        'link_text' => true,
        'link_template' => '<p><a href="%LINK_URL%" class="cta">%LINK_TEXT%</a></p>',

        'link_more' => false,
        'video' => false,
        'pattern' => false,
        'thumbnails_list_element' => false,
        'form_title' => false,
    ),
    'slider09' => array(
        'class' => 'slider slider9',
        'label' => __('Modern Slider', 'iworks-slider'),
        'title' => true,
        'image' => true,
        'text' => true,

        'link' => true,
        'link_text' => true,
        'link_template' => '<a href="%LINK_URL%" class="button">%LINK_TEXT%</a>',

        'link_more' => true,
        'link_more_template' => '<span class="more"><a href="%LINK_MORE_URL%">%LINK_MORE_TEXT%</a></span>',

        'video' => false,
        'pattern' => 5,
        'thumbnails_list_element' => '<li><a href="#">%TITLE%</a></li>',
        'form_title' => false,
    ),
    'slider10' => array(
        'class' => 'slider slider10',
        'label' => __('Product Slider', 'iworks-slider'),
        'title' => false,
        'image' => true,
        'text' => false,
        'link' => true,
        'link_text' => false,
        'link_more' => false,
        'video' => false,
        'pattern' => false,
        'thumbnails_list_element' => false,
        'form_title' => false,
    ),
    'slider11' => array(
        'class' => 'slider slider11',
        'label' => __('Landing Slider', 'iworks-slider'),
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
        'pattern' => 5,
        'thumbnails_list_element' => false,

        'footer_button' => '%FOOTER_BUTTON_TEXT%',
        'form_title' => true,
    ),
    'slider12' => array(
        'class' => 'slider slider12',
        'label' => __('Content Slider', 'iworks-slider'),
        'title' => true,
        'image' => true,
        'text' => true,

        'link' => true,
        'link_text' => true,
        'link_template' => '<p><a href="%LINK_URL%">%LINK_TEXT%</a></p>',

        'link_more' => false,

        'video' => '<div class="video">%IMG%</div>',
        'pattern' => 5,
        'thumbnails_list_element' => false,
        'form_title' => false,
    ),
);
