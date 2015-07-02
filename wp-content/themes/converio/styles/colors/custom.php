<?php
    $custom_color_3 = get_theme_mod('custom_color_3');
	$custom_color_3_without_hash = str_replace('#', '', $custom_color_3);
	$custom_color_4 = get_theme_mod('custom_color_4');
	$custom_color_4_without_hash = str_replace('#', '', $custom_color_4);
	$custom_color_6 = get_theme_mod('custom_color_6');
	
	$images_path = get_template_directory_uri() . '/images/';
?>

.color-custom a {color:<?php echo esc_attr($custom_color_4); ?>;}
.color-custom a:hover {color:<?php echo esc_attr($custom_color_6); ?>;}
.color-custom .wp-pagenavi .prevpostslink:hover,
.color-custom .wp-pagenavi .color-custom .nextpostslink:hover,
.color-custom .wp-pagenavi .prev:hover,
.color-custom .wp-pagenavi .next:hover {background:<?php echo esc_attr($custom_color_4); ?>;color:#FFF!important;}
.color-custom ul.accordion li > a {color:#333;}
.color-custom ul.accordion li > a:hover {color:<?php echo esc_attr($custom_color_4); ?>;}
.color-custom .portfolio .filters ul a:hover {background:<?php echo esc_attr($custom_color_4); ?>;color:#fff;border:1px solid <?php echo esc_attr($custom_color_4); ?>;text-decoration:none;}
.color-custom .portfolio .filters ul a.selected {background:<?php echo esc_attr($custom_color_4); ?>;color:#fff;border:none;}
.color-custom .page-portfolio .foot>p>a:hover {text-decoration:none;background:<?php echo esc_attr($custom_color_4); ?>;}
.color-custom .single .comment-author a.comment-reply-link {color:<?php echo esc_attr($custom_color_4); ?>;}
.color-custom .related article h3 a:hover{color:<?php echo esc_attr($custom_color_4); ?>;}
.color-custom .content aside a {color:<?php echo esc_attr($custom_color_4); ?>;}
.color-custom .pricing-plan.selected h2 {color: #fff;}
.color-custom .pricing-plan.selected .pricing-lead {background:<?php echo esc_attr($custom_color_4); ?>;box-shadow:none;}
.color-custom .pricing-plan.selected {border-top:2px solid <?php echo esc_attr($custom_color_4); ?>;background:#70c14a;}
.color-custom table.pricing tr.action td:first-child a {background:none;color:<?php echo esc_attr($custom_color_4); ?>;}
.color-customtable.pricing .button:hover {background:<?php echo esc_attr($custom_color_4); ?>!important;color:#fff;}
.color-custom .more-detail a:hover {background:<?php echo esc_attr($custom_color_4); ?>;color:#fff;text-decoration:none;}
.color-custom .widget_newsletterwidget {border-color: <?php echo esc_attr($custom_color_3); ?>;}
.color-custom .widget_newsletterwidget .newsletter-submit:hover,
.color-custom .newsletter-widget .newsletter-submit:hover {background-color:<?php echo esc_attr($custom_color_4); ?>;}
.color-custom .tabbed ul.tabs li a:hover {color:#fff;background:<?php echo esc_attr($custom_color_4); ?>;}
.color-custom .counter li.days span.num {background:<?php echo esc_attr($custom_color_4); ?>;}
.color-custom .content aside section ul.menu li.current-menu-item > a {background:none!important;color:<?php echo esc_attr($custom_color_4); ?>!important;}
.color-custom .widget_newsletterwidget a.btn:hover {color:#fff;background-color:<?php echo esc_attr($custom_color_4); ?>;}
.color-custom .woocommerce-pagination ul li .prev:hover,
.color-custom .woocommerce-pagination ul li .next:hover {background:<?php echo esc_attr($custom_color_4); ?>;color:#fff;}
.color-custom.cart .woocommerce .product-name > a {color:<?php echo esc_attr($custom_color_4); ?>;}
.color-custom.cart .cart_totals .checkout-button.button.alt:hover {background-color:<?php echo esc_attr($custom_color_4); ?>;}
.color-custom .lead-page .newsletter-submit:hover {background:<?php echo esc_attr($custom_color_4); ?>;}
.color-custom .woocommerce form .shop_table.cart .actions > p .checkout-button:hover,
.color-custom .woocommerce form .shop_table.cart .actions > .checkout-button:hover {background-color:<?php echo esc_attr($custom_color_4); ?>;}
#place_order:hover{background-color:<?php echo esc_attr($custom_color_4); ?>;}
.color-custom .login .form-row .button:hover {background:<?php echo esc_attr($custom_color_4); ?>;}
.color-custom .save-address-button:hover {background:<?php echo esc_attr($custom_color_4); ?>;}
.color-custom .wpcf7 .wpcf7-form [type="submit"]:hover {background:<?php echo esc_attr($custom_color_4); ?>;}
.color-custom .post-password-form input[type=submit]:hover {background:<?php echo esc_attr($custom_color_4); ?>;}

.color-custom ::-moz-selection {background:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom ::selection {background:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom a.button,
.color-custom button {background:<?php echo esc_attr($custom_color_3); ?>; color: #fff;}
.color-custom a.button:hover,
.color-custom button:hover {color: #fff;}
.color-custom .cart .add_to_cart_button.button.product_type_simple, .color-custom .button.product_type_variable {background: none;}
.color-custom blockquote {border-color: <?php echo esc_attr($custom_color_3); ?>;}
.color-custom ul.tick3 li:before {color:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom ul.tick4 li:before {background:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .wp-pagenavi .prevpostslink,
.wp-pagenavi .prev {background:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .wp-pagenavi .nextpostslink,
.color-custom .wp-pagenavi .next {background:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .slider11 article div>ul li:before {color:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .page-portfolio .foot>p>a {background:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .page-portfolio .filters ul a:hover {background:<?php echo esc_attr($custom_color_3); ?>; border-color: <?php echo esc_attr($custom_color_3); ?>;}
.color-custom .post-meta a, 
.color-custom .tags a {color: #333;}
.color-custom .post-meta a:hover,
.tags a:hover {color:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .single .tags a:hover {background:<?php echo esc_attr($custom_color_3); ?>;border-color: <?php echo esc_attr($custom_color_3); ?>;}
.color-custom .single .post-author {border-color: <?php echo esc_attr($custom_color_3); ?>;}
.color-custom .comment-form ul li em {color:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .post-author h3 a:hover {color:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom #review_form .comment-form .required{color:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom a.btn {color: #fff;}
.color-custom a.btn:hover {color: #fff;}
.color-custom a.btn.white {color: #333;}
.color-custom a.btn.light-gray {color: #444;}
.color-custom a.btn.light.white {color: #fff;}
.color-custom a.btn.light.white:hover {color: #333;}
.color-custom a.btn.custom {background: <?php echo esc_attr($custom_color_3); ?>;}
.color-custom a.btn.custom:hover {background: <?php echo esc_attr($custom_color_4); ?>;}
.color-custom p.progress>span.fill {background: <?php echo esc_attr($custom_color_3); ?>;}
.color-custom .pricing-plan {border-top-color: <?php echo esc_attr($custom_color_3); ?>; border-right-color: #e8e8e8;}
.color-custom .pricing-plan h2 {color:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .pricing-plan.selected {border-top-color: <?php echo esc_attr($custom_color_4); ?>;background:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom table.pricing .button {background:<?php echo esc_attr($custom_color_3); ?>!important;}
.color-custom .e404 button {background:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .call-out {border-top-color:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .postlist-blog .quote-typography p {color:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .more-detail a {background:<?php echo esc_attr($custom_color_3); ?>; color: #fff;}
.color-custom .postlist-blog .quote-text {color:<?php echo esc_attr($custom_color_3); ?>;}

/* quote.svg */
.color-custom .postlist.postlist-blog .post.item .quote-typography,
.color-custom .postlist-blog .post-detail .quote-text { background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px"><g><g><path fill-rule="evenodd" clip-rule="evenodd" fill="%23<?php echo esc_attr($custom_color_3_without_hash);?>" d="M6.225-0.012H2.73L0,6.97V16h6.99V6.97H3.495L6.225-0.012z M12.494,6.97l2.73-6.981H11.73L9,6.97V16h6.99V6.97H12.494z"/></g></g></svg>');}

/* quote2.svg */
.color-custom .hp-quote,
.color-custom blockquote.quote,
.color-custom .quote-typography,
.color-custom .postlist-blog .quote-typography,
.color-custom .postlist-blog .quote-text { background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="26px" height="24px"><g><g><path fill="%23<?php echo esc_attr($custom_color_3_without_hash);?>" d="M10,0H4L0,10v14h12V10H6L10,0z M20,10l4-10h-6l-4,10v14h12V10H20z"/></g></g></svg>');}

.color-custom .widget_newsletterwidget .newsletter-submit,
.color-custom .newsletter-widget .newsletter-submit {background-color:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom footer .newsletter-widget .newsletter-submit {background-color:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .content aside div.tagcloud a:hover,
.color-custom footer .tagcloud a:hover {background:<?php echo esc_attr($custom_color_3); ?>;border-color: <?php echo esc_attr($custom_color_3); ?>;}
.color-custom table#wp-calendar tbody td a:hover {background:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .tabbed ul.tabs {background-color:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .tabbed ul.tabs li a {background:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .tabbed ul.tabs li.tab-best a {background:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .ui-datepicker td a:hover,
.color-custom .ui-datepicker td a.ui-state-highlight {background:<?php echo esc_attr($custom_color_3); ?>!important;}
.color-custom .top-border {border-color:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .left-border {border-color:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .animated-milestone {color:<?php echo esc_attr($custom_color_3); ?>;}

.color-custom .tabbed.alt2 ul.tabs {border-top-color: <?php echo esc_attr($custom_color_3); ?>; background: none;}
.color-custom .tabbed.alt2 ul.tabs li a {background: none;}
.color-custom .tabbed.alt2 ul.tabs li a:hover, .tabbed.alt2 ul.tabs li a:hover .fa:before { background: #f6f6f6; color:<?php echo esc_attr($custom_color_3); ?>; }
.color-custom .tabbed.alt2.alt .tabs {border-left:4px solid <?php echo esc_attr($custom_color_3); ?>;}

.color-custom .counter li.days {background:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .counter.counter-2 li.days {background: none;}
.color-custom .counter.counter-2 li.days span.num {color:<?php echo esc_attr($custom_color_3); ?>;background: none;}
.color-custom .coming-soon input.newsletter-submit[type="submit"]{background-color: <?php echo esc_attr($custom_color_3); ?>;border-color:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .tabbed.alt3 ul.tabs {background: none;}
.color-custom .tabbed.alt3 ul.tabs li a {background: none;}
.color-custom .tabbed.alt3 ul.tabs li a:hover {box-shadow:0 -4px 0 0 <?php echo esc_attr($custom_color_3); ?> inset;color:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .woocommerce-pagination ul li .prev {background:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .woocommerce-pagination ul li .next {background:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom.cart .cart_totals{border-color: <?php echo esc_attr($custom_color_3); ?>;}
.color-custom.cart .cart_totals .checkout-button.button.alt {background-color: <?php echo esc_attr($custom_color_3); ?>;}
.color-custom .ui-slider .ui-slider-handle:hover {border-color:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .ui-slider .ui-slider-range {background:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .comment-form .required {color:<?php echo esc_attr($custom_color_3); ?>;}
.lead-page .newsletter-submit {background:<?php echo esc_attr($custom_color_3); ?>;}
.lead-page .box-default h2 {color:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .woocommerce form .shop_table.cart .actions > p .checkout-button,
.color-custom .woocommerce form .shop_table.cart .actions > .checkout-button {background-color:<?php echo esc_attr($custom_color_3); ?>;}
#place_order{background-color: <?php echo esc_attr($custom_color_3); ?>;}
.color-custom .login .form-row .button {background:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .save-address-button {background:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .wpcf7 .wpcf7-form [type="submit"] {background:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .wpcf7 .wpcf7-form div.wpcf7-mail-sent-ok {background-color:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .post-password-form input[type=submit] {background:<?php echo esc_attr($custom_color_3); ?>;}
.color-custom .landing-form form {border-color: <?php echo esc_attr($custom_color_3); ?>;}

/* headers */
.color-custom .btn-navbar.active {border-bottom-color: <?php echo esc_attr($custom_color_4); ?>;}
.color-custom header .title a:hover {color: <?php echo esc_attr($custom_color_4); ?>;}
.color-custom .mobile-nav li.active {border-bottom-color: <?php echo esc_attr($custom_color_4); ?>;}
.color-custom .shopping-bag li a:hover {color: <?php echo esc_attr($custom_color_4); ?>;}

.color-custom .cart-number-box.active {background-color: <?php echo esc_attr($custom_color_4); ?>;}
.color-custom .h1 .cart-number-box.active:after {border-right: 3px solid <?php echo esc_attr($custom_color_4); ?>;}

@media screen and (min-width: 646px) {
    .color-custom .h1 .cart-number-box.active:after {
		border-right: 3px solid transparent;
        border-top: 3px solid <?php echo esc_attr($custom_color_4); ?>;
    }
}

.color-custom .h2 .cart-number-box.active {background: <?php echo esc_attr($custom_color_4); ?>;}
.color-custom .h2 .cart-number-box.active:after {border-right: 3px solid <?php echo esc_attr($custom_color_4); ?>;}

.color-custom .menu-container li.menu-item.current-menu-item > a, 
.color-custom .menu-container li.page_item.current_page_item > a {color: <?php echo esc_attr($custom_color_4); ?>;}

.color-custom header li.page_item.current_page_item > .submenu-trigger,
.color-custom header li.menu-item.current-menu-item > .submenu-trigger, 
.color-custom header li.menu-item.current-menu-item > .submenu-trigger-container .submenu-trigger { color: <?php echo esc_attr($custom_color_4); ?>; }

@media screen and (max-width: 980px) {
    .color-custom header li.page_item.current_page_item:hover > .submenu-trigger,
	.color-custom header li.menu-item.current-menu-item:hover > .submenu-trigger,
	.color-custom header li.menu-item.current-menu-item:hover > .submenu-trigger-container .submenu-trigger { color: <?php echo esc_attr($custom_color_4); ?>;}
}

@media screen and (min-width: 981px) {
    .color-custom header li.menu-item.current-menu-ancestor > .submenu-trigger,
	.color-custom header li.page_item.current_page_ancestor > .submenu-trigger { color: <?php echo esc_attr($custom_color_4); ?>; }
    .color-custom header li.menu-item.current-menu-ancestor > .submenu-trigger,
	.color-custom header li.page_item.current_page_ancestor > .submenu-trigger { color: <?php echo esc_attr($custom_color_4); ?>; }
    .no-touch header li.menu-item.mi-depth-0 > .submenu-trigger:hover, 
	.no-touch header li.page_item.pi_depth_0 > .submenu-trigger:hover { border-bottom-color: <?php echo esc_attr($custom_color_4); ?>; }
    .no-touch header li.menu-item.mi-depth-0.menu-item-has-children:hover > .submenu-trigger,
	.no-touch header li.page_item.pi_depth_0.page_item_has_children:hover > .submenu-trigger { border-bottom-color: <?php echo esc_attr($custom_color_4); ?>; }
    .touch header li.menu-item.mi-depth-0.submenu-expanded > .submenu-trigger, 
	.touch header li.page_item.pi_depth_0.submenu-expanded > .submenu-trigger { border-bottom-color: <?php echo esc_attr($custom_color_4); ?>; }
    .no-touch header .top-navi li.menu-item.mi-depth-0.menu-item-has-children > .submenu-trigger:hover, 
	.no-touch header .top-navi li.page_item.pi_depth_0.page_item_has_children > .submenu-trigger:hover { border-bottom-color: <?php echo esc_attr($custom_color_4); ?>; }
    .color-custom header .top-navi li.menu-item.mi-depth-0.current-menu-ancestor > .submenu-trigger, 
	.color-custom header .top-navi li.page_item.pi_depth_0.current_page_ancestor > .submenu-trigger, 
	.color-custom header .top-navi li.menu-item.mi-depth-0.current-menu-item > .submenu-trigger, 
	.color-custom header .top-navi li.page_item.pi_depth_0.current_page_item > .submenu-trigger { color: <?php echo esc_attr($custom_color_4); ?>; }
    .color-custom .dropdownmenu-default li.menu-item.current-menu-ancestor:hover > .submenu-trigger, 
	.color-custom .dropdownmenu-default li.page_item.current_page_ancestor:hover > .submenu-trigger, 
	.color-custom .dropdownmenu-default li.menu-item.current-menu-item:hover > .submenu-trigger, 
	.color-custom .dropdownmenu-default li.page_item.current_page_item:hover > .submenu-trigger { color: <?php echo esc_attr($custom_color_4); ?>; }
    .color-custom .dropdownmenu-mega a.current-url { color: <?php echo esc_attr($custom_color_4); ?> !important; }
    .color-custom .dropdownmenu-mega li.menu-item.current-menu-item > a.submenu-trigger, 
	.color-custom .dropdownmenu-mega li.menu-item.current-menu-ancestor > a.submenu-trigger, 
	.color-custom .dropdownmenu-mega li.menu-item.current-menu-item > .submenu-trigger-container > a.submenu-trigger, 
	.color-custom .dropdownmenu-mega li.menu-item.current-menu-ancestor > .submenu-trigger-container > a.submenu-trigger { color: <?php echo esc_attr($custom_color_4); ?> !important; }
    
	.color-custom .h1 nav.mainmenu li.menu-item.mi-depth-0 > .submenu-trigger:hover, 
	.color-custom .h1 nav.mainmenu li.menu-item.mi-depth-0.menu-item-has-children:hover > .submenu-trigger, 
	.color-custom .h1 nav.mainmenu li.page_item.pi_depth_0 > .submenu-trigger:hover, 
	.color-custom .h1 nav.mainmenu li.page_item.pi_depth_0.page_item_has_children:hover > .submenu-trigger { color: <?php echo esc_attr($custom_color_4); ?>; }
    .color-custom .h1 nav.mainmenu li.menu-item.mi-depth-0.current-menu-ancestor > .submenu-trigger, 
	.color-custom .h1 nav.mainmenu li.page_item.pi_depth_0.current_page_ancestor > .submenu-trigger, 
	.color-custom .h1 nav.mainmenu li.menu-item.mi-depth-0.current-menu-item > .submenu-trigger, 
	.color-custom .h1 nav.mainmenu li.page_item.pi_depth_0.current_page_item > .submenu-trigger { color: <?php echo esc_attr($custom_color_4); ?>; }
    
	.color-custom .h2 nav.mainmenu li.menu-item.mi-depth-0 > .submenu-trigger:hover, 
	.color-custom .h2 nav.mainmenu li.menu-item.mi-depth-0.menu-item-has-children:hover > .submenu-trigger, 
	.color-custom .h2 nav.mainmenu li.page_item.pi_depth_0 > .submenu-trigger:hover, 
	.color-custom .h2 nav.mainmenu li.page_item.pi_depth_0.page_item_has_children:hover > .submenu-trigger { color: <?php echo esc_attr($custom_color_4); ?>; }
    .color-custom .h2 nav.mainmenu li.menu-item.mi-depth-0.current-menu-ancestor > .submenu-trigger, 
	.color-custom .h2 nav.mainmenu li.page_item.pi_depth_0.current_page_ancestor > .submenu-trigger, 
	.color-custom .h2 nav.mainmenu li.menu-item.mi-depth-0.current-menu-item > .submenu-trigger, 
	.color-custom .h2 nav.mainmenu li.page_item.pi_depth_0.current_page_item > .submenu-trigger { color: <?php echo esc_attr($custom_color_4); ?>; }
	
    .color-custom .h1 nav.mainmenu li.mi-depth-0.mi-with-dropdown-arrow:hover > .submenu-trigger span.mi-title {
        background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="10px" height="7px"><g><g><polygon fill="%23<?php echo esc_attr($custom_color_4_without_hash);?>" points="8.433,-0.06 4.985,3.325 1.539,-0.06 -0.066,1.546 4.985,6.566 10.037,1.546"/></g></g></svg>');}
    .color-custom .h2 nav.mainmenu li.mi-depth-0.mi-with-dropdown-arrow:hover > .submenu-trigger span.mi-title {
        background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="10px" height="7px"><g><g><polygon fill="%23<?php echo esc_attr($custom_color_4_without_hash);?>" points="8.433,-0.06 4.985,3.325 1.539,-0.06 -0.066,1.546 4.985,6.566 10.037,1.546"/></g></g></svg>');}
}

/*other*/
.color-custom footer a {color: #ccc;}
.color-custom footer a:hover {color: #fff;}

.color-custom .wp-pagenavi .page,
.color-custom .wp-pagenavi a {color: #333;}
.color-custom .wp-pagenavi .page:hover,
.color-custom  .wp-pagenavi a:hover {color:#333;}

.color-custom .button-more a {color: #444;}
.color-custom .button-more a:hover {color: #fff;background-color: <?php echo esc_attr($custom_color_4); ?>; border-color: <?php echo esc_attr($custom_color_4); ?>;}
.color-custom a.play,
.color-custom a.play:hover {color: #333;}
.color-custom a.play.white {color: #fff;}

.color-custom .postlist article h2 a:hover {color: <?php echo esc_attr($custom_color_6); ?>;}