<?php
/**
 * The template for displaying taxonomy event_category
 *
 * Used to display archive-type pages for posts with a post format.
 * If you'd like to further customize these Post Format views, you may create a
 * new template file for each specific one.
 *
 * @todo http://core.trac.wordpress.org/ticket/23257: Add plural versions of Post Format strings
 * and remove plurals below.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

if ( !class_exists('IworksEvents') ) {
    die(__('Please install IworksEvents plugin!'));
}
global $iworks_events;

get_header(); ?>

    <section id="primary" class="content-area">
        <div id="content" class="site-content" role="main">
            <?php do_action('iworks_events_alert_template'); ?>
<!--/* iworks events start */-->
    <div class="event">
<?php
/**
 * produce list of anchors or list of links
 */

if ( 'link' == $iworks_events->get_option('list_type') ) {
    echo $iworks_events->get_all_event_categories_linked();
} else {
    echo $iworks_events->get_all_event_categories();
}
?>
            <?php if ( have_posts() ) : ?>
            <?php
                    // Start the Loop.
$last_data = 0;
                    while ( have_posts() ) : the_post();
?>
<!-- Twenty_Fourteen -->
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-content">
<!-- /Twenty_Fourteen -->
<?php
        $date = intval($iworks_events->get_post_meta(get_the_ID(),'date'));
?>
        <section class="main postlist event-page postlist-blog">
            <!-- data wydarzen / wydarzenia -->
            <?php if ($last_data != $date) { ?>
            <article class="post groups-title">
                <p class="event-day-number"><?php echo date('d', $date ); ?></p>
                <p class="event-day-month"><?php echo __(date('F', $date )); ?> <span><?php echo __(date('l', $date )); ?></span></p>
            </article>
            <?php } ?>
            <?php $last_data = $date; ?>

            <!-- wydarzenie wedlug daty powyzej, przyklad postu z kategorii application -->
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <!-- godzina wydarzenia -->
                <p class="event-hour">
                    <?php echo $iworks_events->get_post_meta(get_the_ID(), 'hour'); ?>
                </p>
                <div class="post-right">
                    <!-- miniaturka wydarzenia -->
<?php
if ( has_post_thumbnail() ) {
    echo '<p>';
    the_post_thumbnail('event-category');
    echo '</p>';
}
?>
                    <!-- kategoria wydarzenia -->
                    <span class="event-category"><?php $iworks_events->get_event_category(); ?></span>
                    <!-- tytul wydarzenia -->
                    <h2 class="post-healine"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <!-- miejsce wydarzenia -->
                    <p class="event-place"><?php echo $iworks_events->get_post_meta(get_the_ID(), 'place'); ?>, <?php echo $iworks_events->get_post_meta(get_the_ID(), 'city'); ?></p>

                    <!-- czesc dodana przez plugin sharethis -->
                    <div class="info-post">
                        <div class="share">
                            <a href="#"><em class="count">6</em> shares</a>
                            <p class="share-social">
                            <em class="arrow"></em>
                            <span class='st_facebook_hcount' data-text='Facebook'  data-urll="http://wordpress.org"></span>
                            <span class='st_twitter_hcount' data-text='Tweet' data-url="http://wordpress.org"></span>
                            <span class='st_googleplus_hcount' data-text='Google +' data-url="http://wordpress.org"></span>
                            <span class='st_pinterest_hcount' data-text='Pinterest' data-url="http://wordpress.org"></span>
                            <span class='st_email_hcount' data-text='Email' data-url="http://wordpress.org"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </article>
        </section>
<!-- Twenty_Fourteen -->
    </div>
</article>
<!-- /Twenty_Fourteen -->
<?php
                    endwhile;
?>
<!-- /* iworks events end */ -->
<?php
                    // Previous/next page navigation.
                    if (function_exists('twentyfourteen_paging_nav')) :
                        twentyfourteen_paging_nav();
                    endif;

                else :
                    // If no content, include the "No posts found" template.
                    get_template_part( 'content', 'none' );

                endif;
            ?>
        </div><!-- #content -->
    </section><!-- #primary -->

<?php
get_sidebar( 'content' );
get_sidebar();
get_footer();
