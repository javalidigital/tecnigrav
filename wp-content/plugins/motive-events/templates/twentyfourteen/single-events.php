<?php
/**
 * The Template for displaying all single custom post type "events"
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

if ( !class_exists('IworksEvents') ) {
    die(__('Please install IworksEvents plugin!'));
}

get_header(); ?>

<div id="primary" class="content-area">
    <div id="content" class="site-content" role="main">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="entry-content">
            <?php do_action('iworks_events_alert_template'); ?>
<!-- iworks_events: start -->
<?php
global $iworks_events;
while ( have_posts() ) : the_post();
        $date = intval($iworks_events->get_post_meta(get_the_ID(),'date'));
?>
<section class="main single">
    <article>
        <!-- data wdarzenia -->
        <p class="event-day-number"><?php echo date('d', $date ); ?></p>
        <p class="event-day-month"><?php echo __(date('F', $date )); ?> <span><?php echo __(date('l', $date )); ?></span></p>
        <p class="event-hour"><?php echo $iworks_events->get_post_meta(get_the_ID(), 'hour'); ?></p>

        <!-- kategoria wdarzenia -->
        <p class="event-category"><?php $iworks_events->get_event_category(); ?></p>

        <!-- linki do strony ze wszystkim wydarzeniami, poprzedniego wydarzenia i nastepnego wydarzenia -->
        <ul class="single-btn">
            <li><a class="all" href="<?php $iworks_events->all_link();?>"><?php echo $iworks_events->get_option('all_name'); ?></a></li>
            <?php echo $iworks_events->get_previous_post_link(get_the_ID()); ?>
            <?php echo $iworks_events->get_next_post_link(get_the_ID()); ?>
        </ul>

        <!-- tytul posta/wydarzenia -->
        <h1><?php the_title(); ?></h1>

        <!-- miejsce wydarzenia -->
        <p class="event-place"><?php echo $iworks_events->get_post_meta(get_the_ID(), 'place'); ?></p>

        <!-- adres wdarzenia -->
        <p class="event-address"><?php echo $iworks_events->get_post_meta(get_the_ID(), 'city'); ?></p>
        <p class="event-address"><?php echo $iworks_events->get_post_meta(get_the_ID(), 'address'); ?></p>

        <!-- duzy obrazek wydarzenia -->
<?php
if ( has_post_thumbnail() ) {
    echo '<p>';
    the_post_thumbnail('event-full');
    echo '</p>';
}
?>

         <!-- krotki opis -->
         <p class="bold-text"><?php the_excerpt(); ?></p>

         <!-- pozostala tresc posta -->
         <?php the_content(); ?>

         <h3>Admission</h3>
         <ul>
             <li>Adults – $15,00</li>
             <li>Students – $10,00</li>
             <li>Children under 12 (accompanied by an adult) – Free</li>
         </ul>

         </article>

         <!-- czesc obslugiwana przez plugin share this -->
         <div class="share-post">
             <h3>Share this event</h3>
                <h3>Share this event</h3>
                <p class="share-social">
<span class='st_facebook_hcount' data-text='Facebook' data-urll="http://wordpress.org"></span>
<span class='st_twitter_hcount' data-text='Tweet' data-url="http://wordpress.org"></span>
<span class='st_googleplus_hcount' data-text='Google +' data-url="http://wordpress.org"></span>
<span class='st_pinterest_hcount' data-text='Pinterest' data-url="http://wordpress.org"></span>
<span class='st_email_hcount' data-text='Email' data-url="http://wordpress.org"></span>
<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "131d5b76-f3b0-46c6-8304-fc5a6ce7cf8d", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
                </p>
         </div>

        <?php echo $iworks_events->upcoming_events(); ?>

        </section>
<!-- end of wp_query -->
<?php endwhile; ?>
<!-- iworks_events: end -->

            </div>
        </article>
    </div><!-- #content -->
</div><!-- #primary -->

 <?php
 get_sidebar( 'content' );
 get_sidebar();
 get_footer();
