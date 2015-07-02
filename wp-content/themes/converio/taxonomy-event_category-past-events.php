<?php
/**
 * The template for displaying taxonomy event_category
 */

if ( !class_exists('IworksEvents') ) {
    die(__('Please install MotiveEvents plugin!','converio'));
}
global $iworks_events;

get_header(); ?>
	
	    <section class="content event">
	        <section class="main postlist event-page postlist-blog">            
	<!--/* motive events start */-->

            <?php if ( have_posts() ) : ?>
            <?php
                    // Start the Loop.
$last_data = 0;
                    while ( have_posts() ) : the_post();
?>
<article class="events type-events post">
	
            <?php do_action('motive_events_alert_template'); ?>
<?php
        $date = intval($iworks_events->get_post_meta(get_the_ID(),'date'));
?>
            <!-- event date -->
            <?php if ($last_data != $date) { ?>
            <article class="post groups-title">
                <p class="event-day-number"><?php echo esc_attr(date('d', $date )); ?></p>
                <p class="event-day-month"><?php echo esc_attr__(date('F, Y', $date )); ?> <span><?php echo esc_attr__(date('l', $date )); ?></span></p>
            </article>
            <?php } ?>
            <?php $last_data = $date; ?>

            <!-- event from the date above -->
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <!-- hour -->
                <p class="event-hour">
                    <?php echo esc_attr($iworks_events->get_post_meta(get_the_ID(), 'hour')); ?>
                </p>
                <div class="post-right">
                    <!-- thumbnail -->
<?php
if ( has_post_thumbnail() ) {
    echo '<p>';
    the_post_thumbnail('event-category');
    echo '</p>';
}
?>
                    <!-- category -->
                    <span class="event-category"><?php esc_attr($iworks_events->get_event_category()); ?></span>
                    <!-- title -->
                    <h2 class="post-headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <!-- place -->
                    <p class="event-place"><?php echo esc_attr($iworks_events->get_post_meta(get_the_ID(), 'place')); ?>, <?php echo esc_attr($iworks_events->get_post_meta(get_the_ID(), 'city')); ?></p>

					<?php /* ShareThis */
					include_once( ABSPATH . 'wp-admin/includes/plugin.php' );?>
					<?php if ( is_plugin_active( 'share-this/sharethis.php' ) ) { ?>
					<div class="info-post share-this">
						<div class="share">
							<a href="#"><?php esc_attr_e('Share', 'converio');?></a>
							<p class="share-social">
							<em class="arrow"></em>
							<?php get_template_part('share-this');?>
							</p>
						</div>
						<?php if ( comments_open() ) : ?><span>/</span><?php comments_popup_link('0 comments', '1 comment', '% comments', 'comment-link'); ?><?php endif; ?>
					</div>
					<?php } else {
						/* Default sharing icons */
		   				$share_links = get_theme_mod('share_links');
		   				if (empty($share_links)) $share_links = 2;
		   				if($share_links == 2) : ?>		
						<div class="info-post">
							<div class="share">
								<a href="#"><?php esc_attr_e('Share', 'converio');?></a>
								<div class="share-social">
									<em class="arrow"></em>
									<?php get_template_part('share');?>
								</div>
							</div>
							<?php if ( comments_open() ) : ?><span>/</span><?php comments_popup_link('0 comments', '1 comment', '% comments', 'comment-link'); ?><?php endif; ?>
						</div>
						<?php endif; ?>
						<?php } ?>
                </div>
            </article>
</article>
<?php
                    endwhile;
?>
<!-- /* motive events end */ -->
<?php

                else :
                    // If no content, include the "No posts found" template.
                    get_template_part('no-content');

                endif;
            ?>
        </section>
		
		<?php 
		global $wp_query;
		$big = 999999999;
		?>
		<?php if($wp_query->max_num_pages > 1) { ?>
		<div class='wp-pagenavi'>
		<?php
		echo paginate_links(array(
		    'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),  
		    'format' => '/page/%#%',
		    'current' => max( 1, get_query_var('paged') ),
		    'total' => $wp_query->max_num_pages,
		    'prev_text' => esc_attr__('', 'converio'),
		    'next_text' => esc_attr__('', 'converio'),
		));
		?> 
		</div>
		<?php } ?>
		
 <?php
 get_sidebar( 'content' );
 get_sidebar();
 ?>
    </section>
<?php get_template_part('call-to-action');?>
<?php get_footer();
