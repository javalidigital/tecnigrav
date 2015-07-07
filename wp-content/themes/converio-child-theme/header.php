<!DOCTYPE html> 
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<?php
		$favicon = get_theme_mod('favicon') ? get_theme_mod('favicon') : false;
		if($favicon) : ?>
		<link rel="Shortcut icon" href="<?php echo esc_url($favicon) ?>">
	<?php endif; ?>
	
	<?php wp_head(); ?>
	
	<?php 
		$primary_font = get_theme_mod('primary_font') ? get_theme_mod('primary_font') : false;
		if ($primary_font == 'google') {
			$primary_google_font = get_theme_mod('primary_google_font');
			if (!$primary_google_font) $primary_font = false;
		}
	?>
	<?php 
		$secondary_font = get_theme_mod('secondary_font') ? get_theme_mod('secondary_font') : false;
		if ($secondary_font == 'google') {
			$secondary_google_font = get_theme_mod('secondary_google_font');
			if (!$secondary_google_font) $secondary_font = false;
		}
	?>	
	<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
	<?php if($primary_font || $secondary_font) : ?>
		<style type="text/css">
			<?php 
			if ($primary_font):
				if($primary_font == 'google') $primary_font = $primary_google_font;
			?>
				body,
				h1, h2, h3, h4, h5, h6,
				.col,
				ul.tabs li a,
				ul.accordion li>a,
				ul.accordion li>a:before,
				.main .content-slider.big article,
				.hp-quote p,
				.hp-intro p.slogan,
				.cat-archive ul li,
				.single h1,
				.product .price span,
				.events .rss-link a,
				.calendar th,
				.calendar td span.day, .calendar td a.day,
				.content>aside section.menu>ul,
				blockquote.quote p,
				p.progress,
				.box h4,
				.pricing-plan p.subtitle,
				.pricing-plan p.price,
				table.pricing th,
				.e404 p,
				.e404 article form+p,
				a.play {font-family: <?php echo esc_attr($primary_font); ?>;}
			<?php endif; ?>
			<?php 
			if ($secondary_font): 
				if($secondary_font == 'google') $secondary_font = $secondary_google_font;
			?>
				h1, h2, h3, h4, h5, h6,
				nav.mainmenu,
				header p.title,
				ul.tabs li a,
				ul.accordion li>a,
				ul.accordion li>a:before {font-family: <?php echo $secondary_font; ?>;}
			<?php endif; ?>
		</style>
	<?php endif; ?>
	<?php
	$color_scheme = get_theme_mod('color_scheme');
	if($color_scheme == 'custom') {
		$custom_external_stylesheet = get_theme_mod('custom_external_stylesheet');
		if(!$custom_external_stylesheet) { ?>
			<style type="text/css">
				<?php get_template_part('styles/colors/custom');?>
			</style>
		<?php } ?>
	<?php } ?>
	<?php 
	$custom_css = get_theme_mod('custom_css');
	if($custom_css) :
	?>
	<style type="text/css">
		<?php echo converio_sanitize_text_decode($custom_css); ?>
	</style>
	<?php endif; ?>
	<!--[if lt IE 9]>
		<script src="<?php echo get_template_directory_uri();?>/js/html5.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri() ?>/styles/style-ie.css" media="screen"></script>
	<![endif]-->
</head>

<?php

//Needed to detect plugin. For use on Front End only.
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

$body_classes = array();

$color_scheme = get_theme_mod('color_scheme') ? 'color-' . get_theme_mod('color_scheme') : false;
if($color_scheme) $body_classes[] = $color_scheme;

$boxed = get_theme_mod('layout_type') ? 'boxed' : false;
if($boxed) {
	$body_classes[] = $boxed;

	$pattern = get_theme_mod('layout_bg_pattern');
	if($pattern != 0)  {
		if ($pattern < 10) {
            $pattern = "0" . $pattern;
        }
        $pattern_class = "p".$pattern;
        $body_classes[] = $pattern_class;
	}

	$border = get_theme_mod('layout_border');
	if($border != 0)  {
		if ($border < 10) {
            $border = "0" . $border;
        }
        $border_class = "f".$border;
        $body_classes[] = $border_class;
	}
}

// add class for avatar
if(!get_theme_mod('avatar_shape')) {
	$body_classes[] = 'avatar-circle';
} else if (get_theme_mod('avatar_shape') == 1) {
	$body_classes[] = 'avatar-rounded-square';
} else if (get_theme_mod('avatar_shape') == 2) {
	$body_classes[] = 'avatar-square';
} 

?>

<?php 

function converio_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   return $rgb; // returns an array with the rgb values
}

$boxed = get_theme_mod('layout_type') ? 'boxed' : false;
if($boxed) {
	$body_classes[] = $boxed;

	$layout_border_width = get_theme_mod('layout_border_width') ? get_theme_mod('layout_border_width') : 0;
	$layout_border_color = get_theme_mod('layout_border_color') ? get_theme_mod('layout_border_color') : 0;
	$layout_border_opacity = get_theme_mod('layout_border_opacity') ? get_theme_mod('layout_border_opacity') : 0;
	
	$layout_shadow_size = get_theme_mod('layout_shadow_size') ? get_theme_mod('layout_shadow_size') : 0;
	$layout_shadow_color = get_theme_mod('layout_shadow_color') ? get_theme_mod('layout_shadow_color') : 0;
	$layout_shadow_opacity = get_theme_mod('layout_shadow_opacity') ? get_theme_mod('layout_shadow_opacity') : 0;

	/* Customize boxed version */

	$boxed_style_output='';
	
	//boxed version - border styles
	if ($layout_border_width & $layout_border_color & $layout_border_opacity) {
		$layout_border_color_rgb = converio_hex2rgb($layout_border_color);
		$boxed_style_output.= 'body.boxed .root { ';	
		$boxed_style_output.= 'border-width: 0 '. $layout_border_width .'px;';
		$boxed_style_output.= 'border-style: solid;';
		$boxed_style_output.= 'border-color: rgb(' . $layout_border_color_rgb[0] . ', ' . $layout_border_color_rgb[1] . ', ' . $layout_border_color_rgb[2] . ');';
		$boxed_style_output.= 'border-color: rgba(' . $layout_border_color_rgb[0] . ', ' . $layout_border_color_rgb[1] . ', ' . $layout_border_color_rgb[2] . ',  '.$layout_border_opacity.');';
		$boxed_style_output.= '-webkit-background-clip: padding-box;';
		$boxed_style_output.= 'background-clip: padding-box;';
		$boxed_style_output.= ' }';
	
	}

	//boxed version - shadow styles
	if ($layout_shadow_size & $layout_shadow_color & $layout_shadow_opacity) {
		$layout_shadow_color_rgb = converio_hex2rgb($layout_shadow_color);
		
		$boxed_style_output.= 'body.boxed .root { ';
    	$boxed_style_output.= 'box-shadow: 0 0 ' . $layout_shadow_size . 'px rgba(' . $layout_shadow_color_rgb[0] . ', ' . $layout_shadow_color_rgb[1] . ', ' . 		$layout_shadow_color_rgb[2] . ', ' .$layout_shadow_opacity. '); }';
	}
}

if(get_theme_mod('header_type') && (get_theme_mod('header_type') == 11 || get_theme_mod('header_type') == 12)) {
	if(get_theme_mod('header_type') == 11) {
		$body_classes[] = 'boxed-header';
	} else {
		$body_classes[] = 'boxed-header-rounded';
	}
}


?>


<body <?php body_class($body_classes); ?>><div class="root">
<div class="dados-contato">
<li class="titulo-float"><p style="text-align: center;"></i><strong>Podemos ajuda-lo?</strong></li>
<div class="iconfloat"><i class="fa fa-phone"></i></div><div class="sup">VENDAS E SUPORTE</br>(11) 3465-500</div>
<li class="cota"><a href="#">FAZER COTAÇÃO</a></li>
</div>
<div class="topo-containter">
<div class="topo-barra"><?php do_action('wpml_add_language_selector'); ?> Av. Álvaro Guimarães, 724 - São Bernardo/SP | Ligue-nos (11) 3508-1930 | contato@tecnigrav.com</div>
</div>	
	<?php

	if($boxed && $boxed_style_output != '') { 
		echo '<style type="text/css">'.esc_attr($boxed_style_output).'</style>';
	}	
	
	$sticky_disabled = get_theme_mod('sticky_disabled');
	$search_disabled = get_theme_mod('search_disabled');
	$cart_disabled = get_theme_mod('cart_disabled');
	$dropdown_on_hover_disabled = get_theme_mod('dropdown_on_hover_disabled');
	$social_colored_disabled = get_theme_mod('social_colored_disabled');
	$hide_top = get_theme_mod('hide_top');
	if (empty($hide_top)) $hide_top = 2;
	$header_type = get_theme_mod('header_type');
	if (empty($header_type)) $header_type = 1;
	if ($header_type == 2 || $header_type == 21 || $header_type == 22) {
		$header_type_class = 'h2';
	} else if($header_type == 1 || $header_type == 11 || $header_type == 12) {
		$header_type_class = 'h1';
	} else {
		$header_type_class = 'h'.$header_type;
	}
	
	$header_opacity_enabled = get_theme_mod('header_opacity_enabled');
	
	$top_header_msg = get_theme_mod('top_header_msg');
	$topbar_links = get_theme_mod('topbar_links') ? get_theme_mod('topbar_links') : 'social';
	//logo upload
	$logo_upload = get_theme_mod('logo_upload');
	$logo_width = get_theme_mod('logo_width');
	$logo_height = get_theme_mod('logo_height');
	

	$menu_animation_enabled = get_theme_mod('menu_animation_enabled');
	$hover_delay_enabled = get_theme_mod('hover_delay_enabled');
	$sticky_collapse = get_theme_mod('sticky_collapse');
	$sticky_opacity_enabled = get_theme_mod('sticky_opacity_enabled');
	$data_menu_slideup_duration = get_theme_mod('data_menu_slideup_duration');
	if (empty($data_menu_slideup_duration)) $data_menu_slideup_duration = 500;
	$data_menu_slidedown_duration = get_theme_mod('data_menu_slidedown_duration');
	if (empty($data_menu_slidedown_duration)) $data_menu_slidedown_duration = 400;
	$data_menu_fadein_duration = get_theme_mod('data_menu_fadein_duration');
	if (empty($data_menu_fadein_duration)) $data_menu_fadein_duration = 300;
	$data_menu_fadeout_duration = get_theme_mod('data_menu_fadeout_duration');
	if (empty($data_menu_fadeout_duration)) $data_menu_fadeout_duration = 400;
	$sticky_trigger_position = get_theme_mod('sticky_trigger_position');
	if (empty($sticky_trigger_position)) $sticky_trigger_position = 400;
	
	// if widget display on mobile device
	$widget_on_mobile_enabled = get_theme_mod('widget_on_mobile_enabled');
	?>
	<header class="<?php echo esc_attr($header_type_class) ?><?php if($header_opacity_enabled == 0) echo ' header-opacity-enabled'; ?><?php if($sticky_disabled < 2) echo ' sticky-enabled'; ?><?php if($sticky_disabled == 0) echo " sticky-no-topbar"; ?><?php if($menu_animation_enabled == 0) echo " menu-animation-enabled"; ?><?php if($hover_delay_enabled == 0) echo " hover-delay-enabled"; ?><?php if($sticky_collapse == 0) echo " sticky-collapse"; ?><?php if($sticky_opacity_enabled == 0) echo " sticky-opacity-enabled"; ?><?php if(!$search_disabled) echo ' with-search-box'; ?><?php if (class_exists('Woocommerce')) { if(!$cart_disabled) {echo ' with-cart-box';} }?><?php if(!$widget_on_mobile_enabled) echo ' lr-mi-with-widget-visible'; ?>"<?php if($sticky_trigger_position != '') {echo ' data-sticky-trigger-position="'.esc_attr($sticky_trigger_position).'"';}?><?php if($menu_animation_enabled == 0) { ?><?php if($data_menu_slidedown_duration != '') {echo ' data-menu-slidedown-duration="'.esc_attr($data_menu_slidedown_duration).'"';}?><?php if($data_menu_slideup_duration != '') {echo ' data-menu-slideup-duration="'.esc_attr($data_menu_slideup_duration).'"';}?><?php if($data_menu_fadein_duration != '') {echo ' data-menu-fadein-duration="'.esc_attr($data_menu_fadein_duration).'"';}?><?php if($data_menu_fadeout_duration != '') {echo ' data-menu-fadeout-duration="'.esc_attr($data_menu_fadeout_duration).'"';}?><?php } ?>>
		<?php if ($hide_top == 1) : ?>
		<section class="top <?php if ($topbar_links == 'social' || $topbar_links == 'search') echo 'with-social'; ?>">
			<div>
				<p class="top-message">
					<?php if ($top_header_msg <> '') echo converio_sanitize_text_decode($top_header_msg); ?>
				</p>
				<?php if ($topbar_links == 'menu') : ?>
					<button type="button" class="btn btn-navbar btn-top collapsed" data-toggle="collapse" data-target=".top-navi > ul"><?php esc_attr_e('Menu','converio');?></button>
					<nav class="top-navi menu-container">
					<?php wp_nav_menu( array('fallback_cb' => 'converio_page_menu', 'depth' => '0', 'theme_location' => 'secondary', 'link_before' => '', 'link_after' => '', 'container' => false, 'menu_class' => '', 'walker' => new Converio_Walker_Nav_Menu())); ?>
					</nav>
				<?php elseif ($topbar_links == 'social') : ?>
				<nav class="social social-light<?php if(!$social_colored_disabled) {echo ' social-colored';} ?>">
					<ul>
						<?php $social_links = converio_get_social_links(); 
						foreach($social_links as $link) : ?>
						<li><a href="<?php echo esc_url($link->url); ?>" class="<?php echo esc_attr($link->class) ?>" target="_blank"><?php echo esc_attr($link->name) ?></a></li>
						<?php endforeach; ?>
					</ul>
				</nav>
				<?php endif; ?>
			</div>
		</section>
		<?php endif ?>
		<?php if ($header_type == 1 || $header_type == 11 || $header_type == 12) : ?>
					<section class="main-header">
		                <div>
							<?php if (!$logo_upload) : ?>
		                    <div class="title">
		                        <div class="logo-wrapper">
		                            <a class="logo" href="<?php echo esc_url(home_url()); ?>/">
										<?php bloginfo( 'name' );?>
									</a>
		                        </div>
							</div>	
							<?php else : ?>
		                    <div itemtype="http://schema.org/Organization" itemscope="itemscope" class="title">
		                        <div class="logo-wrapper">
		                            <a class="logo" href="<?php echo esc_url(home_url()); ?>/" itemprop="url">
		                                <img alt="<?php bloginfo( 'name' );?>" src="<?php echo esc_url(get_theme_mod( 'logo_upload' )); ?>" itemprop="logo" 
										<?php if ($logo_width != '') {?> width="<?php echo esc_attr(get_theme_mod( 'logo_width' ));?>" <?php }?> 
		                				<?php if ($logo_height != '' ) {?> height="<?php echo esc_attr(get_theme_mod( 'logo_height' ));?>"<?php }?>
										/>
									</a>
		                        </div>
		                    </div>							
							<?php endif; ?>
		                    <div class="nav-container">
		                        <div class="mobile-group">
		                            <button type="button" class="btn btn-navbar collapsed" data-toggle="collapse" data-target="nav.mainmenu > ul"><?php echo esc_attr__('Menu', 'converio') ?></button>
		                            <ul class="mobile-nav<?php if(!$dropdown_on_hover_disabled) {echo ' dropdown-on-hover-enabled';}?>">
									<?php if(!$search_disabled) : ?>
		                                <li class="border">
		                                    <a class="search collapsed" data-target=".search-box" href="javascript:;">
		                                        <span class="search-icon"><?php echo esc_attr__('Search', 'converio') ?></span>
		                                    </a>
		                                </li>
									<?php endif; ?>
									<?php if(!$cart_disabled) : ?>
										<?php if (class_exists('Woocommerce')) {converio_cart_menu_item();} ?>
									<?php endif; ?>
		                            </ul>
		                        </div>

								<nav class="nav-collapse mainmenu menu-container">
									<?php wp_nav_menu( array('fallback_cb' => 'converio_page_menu', 'depth' => '0', 'theme_location' => 'primary', 'link_before' => '', 'link_after' => '', 'container' => false, 'walker' => new Converio_Walker_Nav_Menu()) ); ?>
									<div class="clear"></div>
								</nav>
		                    </div>

							<?php  if (class_exists('Woocommerce')) {converio_cart_shopping_bag();} ?>	

							<div class="search-box">
								<form method="get" action="<?php echo esc_url(home_url()); ?>">
									<input type="text" value="<?php echo esc_attr__('Type your keywords', 'converio') ?>" onFocus="if (this.value == '<?php echo esc_js(__('Type your keywords', 'converio')); ?>') this.value = '';" onBlur="if (this.value == '') this.value = '<?php echo esc_js(__('Type your keywords', 'converio')); ?>';" name="s">
								</form>
							</div>
		                </div>
		            </section>
					
		<?php elseif ($header_type == 2 || $header_type == 21 || $header_type == 22 ) : ?>
        <section class="main-header">
            <div>
			<?php if (!$logo_upload) : ?>
			<p class="title">
            	<a class="logo" href="<?php echo esc_url(home_url()); ?>/">
					<?php bloginfo( 'name' );?>
				</a>
				<?php if ( get_bloginfo( 'description' ) ) { ?><span><?php bloginfo('description'); ?></span><?php } ?>
			</p>	
			<?php else : ?>	
            <p itemtype="http://schema.org/Organization" itemscope="itemscope" class="title">
            	<a class="logo" href="<?php echo esc_url(home_url()); ?>/" itemprop="url">
                	<img alt="<?php bloginfo( 'name' );?>" src="<?php echo esc_url(get_theme_mod( 'logo_upload' )); ?>" itemprop="logo" 
					<?php if ($logo_width != '') {?> width="<?php echo esc_attr(get_theme_mod( 'logo_width' ));?>" <?php }?> 
					<?php if ($logo_height != '' ) {?> height="<?php echo esc_attr(get_theme_mod( 'logo_height' ));?>"<?php }?>
					/>
				</a>
                <?php if ( get_bloginfo( 'description' ) ) { ?><span><?php bloginfo('description'); ?></span><?php } ?>
            </p>
			<?php endif; ?>
			
			 <?php if ( $header_type  == 21 ) : ?>
                <form method="get" class="searchform" action="<?php echo esc_url(home_url()); ?>">
                    <fieldset>
                        <input type="text" value="" name="s" id="s" placeholder="<?php echo esc_attr__('Search...', 'converio') ?>">
                        <button type="submit" id="searchsubmit" value="<?php echo esc_attr__('Search', 'converio') ?>"></button>
                    </fieldset>
                </form>
			<?php endif; ?>
			<?php if ( $header_type == 22 ) : ?>
				<div class="box-social">
					<nav class="social<?php if(!$social_colored_disabled) {echo ' social-colored';} ?>">
						<ul>
							<?php $social_links = converio_get_social_links(); 
						foreach($social_links as $link) : ?>
							<li><a href="<?php echo esc_url($link->url); ?>" class="<?php echo esc_attr($link->class) ?>" title="<?php echo esc_attr($link->name) ?>" target="_blank"><?php echo esc_attr($link->name) ?></a></li>
							<?php endforeach; ?>
						</ul>
				    </nav>
				</div>
			<?php endif; ?>
				
            </div>
            <div class="clear"></div>
        </section>
        <div class="mainmenu-container">
            <nav class="mainmenu menu-container">
                <button type="button" class="btn btn-navbar collapsed" data-toggle="collapse" data-target="nav.mainmenu > ul"><?php echo esc_attr__('Menu', 'converio') ?></button>
                <div class="mobile-group">
                    <ul class="mobile-nav<?php if(!$dropdown_on_hover_disabled) {echo ' dropdown-on-hover-enabled';}?>">
					<?php if(!$search_disabled) : ?>
                        <li class="border">
                            <a class="search collapsed" data-target=".search-box" href="javascript:;" title="<?php echo esc_attr__('Search', 'converio') ?>">
                                <span class="search-icon"><?php echo esc_attr__('Search', 'converio') ?></span>
                            </a>
                        </li>
					<?php endif; ?>
					<?php if(!$cart_disabled) : ?>
						<?php if (class_exists('Woocommerce')) {converio_cart_menu_item();} ?>
					<?php endif; ?>
                    </ul>
					<?php  if (class_exists('Woocommerce')) {converio_cart_shopping_bag();} ?>
					<div class="search-box">
						<form method="get" action="<?php echo esc_url(home_url()); ?>">
							<input type="text" value="<?php echo esc_attr__('Type your keywords', 'converio') ?>" onFocus="if (this.value == '<?php echo esc_js(__('Type your keywords', 'converio')) ?>') this.value = '';" onBlur="if (this.value == '') this.value = '<?php echo esc_js(__('Type your keywords', 'converio')) ?>';" name="s">
						</form>
					</div>					
					
                </div>
				<?php wp_nav_menu( array('fallback_cb' => 'converio_page_menu', 'depth' => '0', 'theme_location' => 'primary', 'link_before' => '', 'link_after' => '', 'container' => false, 'walker' => new Converio_Walker_Nav_Menu()) ); ?>
                <div class="clear"></div>
            </nav>
        </div>
		
		<?php endif; ?>
	</header>

<?php do_action('multipurpose_sliders');?>

<?php // Revolution Slider
if ( is_plugin_active( 'revslider/revslider.php' ) && !is_search() && !is_404()) { 
	if ( get_post() ) {		
    	$revolution_slider = get_post_meta($post->ID, 'revolution_slider', true);
    	if ($revolution_slider) { ?>
      	    <section class="revolution-slider">  
			  <?php 
			  if(is_front_page() ) {
			      putRevSlider($revolution_slider,"Homepage");
			  } else {
			      putRevSlider($revolution_slider, $id);
			  }
			  ?>
      	   </section>
<?php } 
	}
} //end of Revolution Slider ?>

<?php if(!is_404() && !is_page_template('comming-soon.php') ) {
	if (function_exists('converio_breadcrumb')) {
		converio_breadcrumb();
	}	
} ?>

<?php
// getting the sidebar position for current page type
global $converio_sidebar_pos_global;
$converio_sidebar_pos_global = 0;
if(is_search()) $converio_sidebar_pos_global = get_theme_mod('sidebar_pos_search');
if(is_single()) $converio_sidebar_pos_global = get_theme_mod('sidebar_pos_post');
if(is_page()) $converio_sidebar_pos_global = get_theme_mod('sidebar_pos_page');
if(is_archive()) $converio_sidebar_pos_global = get_theme_mod('sidebar_pos_archive');
if(is_home()) $converio_sidebar_pos_global = get_theme_mod('sidebar_pos_blog');

if(!is_search() && !is_404() && !is_archive()) {
	global $converio_thisPageId;
	$converio_thisPageId = get_the_id();
	$sidebar_position = get_post_meta($converio_thisPageId, 'sidebar_position', true);
	if($sidebar_position == 3) $sidebar_position = $converio_sidebar_pos_global;
} else {
	$sidebar_position = $converio_sidebar_pos_global;
}

global $converio_sidebar_class;
$converio_sidebar_class = '';

switch($sidebar_position) {
	case 1: 
		// sidebar on the left
		$converio_sidebar_class = "reverse";
		break;
	case 2:
		// no sidebar
		$converio_sidebar_class = "wide";
		break;
	default: 
		// sidebar on the right (default)
		$converio_sidebar_class = "";
		break;
}
?>