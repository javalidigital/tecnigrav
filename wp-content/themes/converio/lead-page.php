<?php
/*
* Template name: Lead Page
*/
?>
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
		<link rel="Shortcut icon" href="<?php echo esc_url($favicon);?>">
	<?php endif; ?>
	<?php 
		$primary_font = get_theme_mod('primary_font') ? get_theme_mod('primary_font') : false;
		if ($primary_font == 'google') {
			$primary_google_font = get_theme_mod('primary_google_font');
			if (!$primary_google_font) $primary_font = false;
		}
	?>
	<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
	<?php if($primary_font) : ?>
		<style type="text/css">
			<?php 
			if($primary_font == 'google') $primary_font = $primary_google_font;
			?>
			body,
			h1, h2, h3, h4, h5, h6,
			.col,
			ul.tabs li a,
			ul.accordion li>a,
			ul.accordion li>a:before,
			.main .content-slider.big article,
			.slider4 h3+p,
			.slider5 h3,
			.slider5 h2 + p,
			.slider6 article h3,
			.slider7 .controls ul a,
			.slider9 h3+p,
			.slider9 .slider-titles a,
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
			a.play {font-family: <?php echo esc_attr($primary_font) ?>;}
		</style>
	<?php endif; ?>
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
	<style>
	<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' ); ?>
	html, body { min-height:100%; }
	#custom-bg {background: url("<?php echo esc_url($image[0]); ?>") no-repeat 50% 50%; background-size: cover; min-height:100%;}
	</style>
	<?php wp_head(); ?>
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


?>

<body id="custom-bg" <?php body_class($body_classes); ?>>
<div class="root">
	<section class="content">
		<section class="lead-page">
			<?php //logo upload
			$logo_upload = get_theme_mod('logo_upload');
			$logo_width = get_theme_mod('logo_width');
			$logo_height = get_theme_mod('logo_height');?>			
			<?php if (!$logo_upload) : ?>       
			        <?php else : ?>
			            <p class="logo"><img src="<?php echo esc_url(get_theme_mod( 'logo_upload' )); ?>" <?php if ($logo_width != '') {?>
			                width="<?php echo esc_attr(get_theme_mod( 'logo_width' ));?>"
				            <?php }?> 
				            <?php if ($logo_height != '' ) {?> height="<?php echo esc_attr(get_theme_mod( 'logo_height' ));?>"
				            <?php }?>
				        alt="<?php bloginfo( 'name' ); ?>"/></p>
		    <?php endif; ?>			
	
			<div class="box-default top-border">

				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<h2><?php the_title(); ?></h2>		
				<?php the_content(); ?>				
				<?php endwhile; endif; ?>				


				<?php if (function_exists('dynamic_sidebar')) { dynamic_sidebar('lead-page-widget-area'); } ?>
				<p class="info"><?php esc_attr_e('Your information will *never* be shared with a third party.','converio');?></p>
			</div>

		</section>
	</section>
</div>
<?php wp_footer(); ?>
</body>
</html>