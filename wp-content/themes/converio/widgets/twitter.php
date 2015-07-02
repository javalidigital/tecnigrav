<?php
add_action('widgets_init', 'converio_twitter_widget_register');

function converio_twitter_widget_register() {
	register_widget('Converio_Twitter_Widget');
}

/*
 * The widget
 */

class Converio_Twitter_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'twitter_widget',
			__('Converio Twitter', 'converio'),
			array('description' => esc_attr__('Shows tweets', 'converio'))
		);
	}

	public function widget( $args, $instance ) {
		$title = apply_filters('widget_title', $instance['title']); 
	    $limit = is_numeric($instance['limit']) ? $instance['limit'] : 5;
	    $screen_name = !empty($instance['screen_name']) ? trim(strip_tags($instance['screen_name'])) : '';
	    $consumer_key = !empty($instance['consumer_key']) ? trim(strip_tags($instance['consumer_key'])) : '';
	    $consumer_secret = !empty($instance['consumer_secret']) ? trim(strip_tags($instance['consumer_secret'])) : '';
	    $access_token = !empty($instance['access_token']) ? trim(strip_tags($instance['access_token'])) : '';
	    $access_secret = !empty($instance['access_secret']) ? trim(strip_tags($instance['access_secret'])) : '';


	    echo $args['before_widget'];  
	    if (!empty($title)) {
	    	echo $args['before_title'] . esc_attr($title) . $args['after_title'];  
	    }  
	    echo converio_display_latest_tweets($limit, $screen_name, $consumer_key, $consumer_secret, $access_token, $access_secret);
	    echo $args['after_widget'];  
	}

	public function form($instance) {
		$title = isset($instance['title']) ? $instance['title'] : '';
		?>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'converio'); ?></label> 
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
		<?php 
		$limit = isset($instance['limit']) ? $instance['limit'] : '';
		?>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('limit')); ?>"><?php esc_attr_e('Number of tweets:', 'converio'); ?></label> 
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('limit')); ?>" name="<?php echo esc_attr($this->get_field_name('limit')); ?>" type="text" value="<?php echo esc_attr($limit); ?>" />
		</p>
		<?php 
		$screen_name = isset($instance['screen_name']) ? $instance['screen_name'] : '';
		?>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('screen_name')); ?>"><?php esc_attr_e('Twitter screen name:', 'converio'); ?></label> 
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('screen_name')); ?>" name="<?php echo esc_attr($this->get_field_name('screen_name')); ?>" type="text" value="<?php echo esc_attr($screen_name); ?>" />
		</p>
		<?php 
		$consumer_key = isset($instance['consumer_key']) ? $instance['consumer_key'] : '';
		?>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('consumer_key')); ?>"><?php esc_attr_e('Consumer key:', 'converio'); ?></label> 
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('consumer_key')); ?>" name="<?php echo esc_attr($this->get_field_name('consumer_key')); ?>" type="text" value="<?php echo esc_attr($consumer_key); ?>" />
		</p>
		<?php 
		$consumer_secret = isset($instance['consumer_secret']) ? $instance['consumer_secret'] : '';
		?>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('consumer_secret')); ?>"><?php esc_attr_e('Consumer secret:', 'converio'); ?></label> 
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('consumer_secret')); ?>" name="<?php echo esc_attr($this->get_field_name('consumer_secret')); ?>" type="text" value="<?php echo esc_attr($consumer_secret); ?>" />
		</p>
		<?php 
		$access_token = isset($instance['access_token']) ? $instance['access_token'] : '';
		?>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('access_token')); ?>"><?php esc_attr_e('Access token:', 'converio'); ?></label> 
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('access_token')); ?>" name="<?php echo esc_attr($this->get_field_name('access_token')); ?>" type="text" value="<?php echo esc_attr($access_token); ?>" />
		</p>
		<?php 
		$access_secret = isset($instance['access_secret']) ? $instance['access_secret'] : '';
		?>
		<p>
		<label for="<?php echo esc_attr($this->get_field_id('access_secret')); ?>"><?php esc_attr_e('Access secret:', 'converio'); ?></label> 
		<input class="widefat" id="<?php echo esc_attr($this->get_field_id('access_secret')); ?>" name="<?php echo esc_attr($this->get_field_name('access_secret')); ?>" type="text" value="<?php echo esc_attr($access_secret); ?>" />
		</p>
		<?php
	}

	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['limit'] = is_numeric( $new_instance['limit']) ? $new_instance['limit'] : 0;
	    $instance['screen_name'] = !empty($new_instance['screen_name']) ? trim(strip_tags($new_instance['screen_name'])) : '';
	    $instance['consumer_key'] = !empty($new_instance['consumer_key']) ? trim(strip_tags($new_instance['consumer_key'])) : '';
	    $instance['consumer_secret'] = !empty($new_instance['consumer_secret']) ? trim(strip_tags($new_instance['consumer_secret'])) : '';
	    $instance['access_token'] = !empty($new_instance['access_token']) ? trim(strip_tags($new_instance['access_token'])) : '';
	    $instance['access_secret'] = !empty($new_instance['access_secret']) ? trim(strip_tags($new_instance['access_secret'])) : '';
		return $instance;
	}	
}


/*
 * Some helper functions
 */

/**
 * Format the time when displaying our latest Tweets
 */
function ago($time){
   $periods = array(
   		__("second", "converio"), 
   		__("minute", "converio"), 
   		__("hour", "converio"), 
   		__("day", "converio"), 
   		__("week", "converio"), 
   		__("month", "converio"), 
   		__("year", "converio")
   		);
   $lengths = array("60","60","24","7","4.35","12","10");
 
   $now = time();
   $difference = $now - $time;
   $tense = "ago";
 
   for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
       $difference /= $lengths[$j];
   }
 
   $difference = round($difference);
 
   if($difference != 1) {
       $periods[$j].= "s";
   }
 
   return "$difference $periods[$j] ago ";
}
 
/**
 * Display latest Tweets
 */
function converio_display_latest_tweets($no_tweets, $screen_name, $consumer_key, $consumer_secret, $access_token, $access_secret){

    $twitterConnection = new Converio_TwitterOAuth($consumer_key, $consumer_secret, $access_token, $access_secret);
    $twitterData = $twitterConnection->get(
        'statuses/user_timeline',
          array(
            'screen_name'     => $screen_name,
            'count'           => $no_tweets,
            'exclude_replies' => true
          )
        );  

    if($twitterData && is_array($twitterData)) :
    ?>
        <ul class="tweets">
            <?php foreach($twitterData as $tweet): 
            	$latestTweet = $tweet->text;
                $latestTweet = preg_replace('/http:\/\/([a-z0-9_\.\-\+\&\!\#\~\/\,]+)/i', '<a href="http://$1" target="_blank">http://$1</a>', $latestTweet);
                $twitterTime = strtotime($tweet->created_at);
                $timeAgo = ago($twitterTime);
            ?>
            <li>
                <?php echo $latestTweet; ?>
                <br><span><a href="http://twitter.com/<?php echo $tweet->user->screen_name; ?>/statuses/<?php echo $tweet->id_str; ?>"><?php echo $timeAgo; ?></a></span>
            </li>
            <?php endforeach; ?>
        </ul>
    <?php
    endif;
}

?>