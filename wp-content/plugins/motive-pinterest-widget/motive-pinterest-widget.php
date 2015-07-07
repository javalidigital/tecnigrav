<?php

/*
Plugin Name: Motive Pinterest Widget
Description: Widget Pinterest
Author: ThemeMotive
Author URI: http://thememotive.com
Version: 1.0.1
*/

include_once(ABSPATH . WPINC . '/feed.php');

class Motive_Pinterest_Widget extends WP_Widget {
	function __construct() {
		parent::__construct(
			'motive_pinterest_widget', // Base ID
			'Motive Pinterest', // Name
			array( 'description' => 'Pinterest Widget - show latest Pins', ) // Args
		);
	}

	var $form = array (
			array (
				'id' => 'title',
				'description' => 'Title',
				'default' => 'Pinterest',
				'type' => 'text'
			),
			array (
				'id' => 'username',
				'description' => 'Username',
				'default' => 'pinterest',
				'type' => 'text'
			),
			array (
				'id' => 'number',
				'description' => 'Pins to display (could not be more than 25)',
				'default' => 6,
				'type' => 'number'
			),
			array (
				'id' => 'width',
				'description' => 'Width of pins (set 0 for auto)',
				'default' => 78,
				'type' => 'number'
			),
			array (
				'id' => 'height',
				'description' => 'Height of pins (set 0 for auto)',
				'default' => 78,
				'type' => 'number'
			),
			array (
				'id' => 'cache-time',
				'description' => 'Interval to fetch new pins (in minutes)',
				'default' => 15,
				'type' => 'number'
			),
			array (
				'id' => 'follow-text',
				'description' => 'Anchor text to display',
				'default' => 'Follow our pinterest boards',
				'type' => 'text'
			)
		);

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );

		if ($instance['width'] == 0)
			$width = null;
		else
			$width = 'width="'.$instance['width'].'"';

		if ($instance['height'] == 0)
			$height = null;
		else
			$height = 'height="'.$instance['height'].'"';

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo str_replace ('<h3>', '<h3 class="pinterest">', $args['before_title'] ) . $title . $args['after_title'];

		// Download RSS
		$things = $this->download_rss ( $instance['username'], $instance['number'], $instance['cache-time'] );
		if ( is_null ( $things ) )
			printf ( 'Unable to load Pinterest pins for %s', $instance['username'] );
		else {
			echo '<ul class="latest-works pinterest">';
			foreach ($things as $thing) {
				array_push($thing, $width);
				array_push($thing, $height);
				vprintf( '<li><a href="%s" rel="external nofollow"><img src="%s" alt="%s" %s %s /></a></li>', $thing);
				// may we can use style="height:auto; width:auto; max-width:%dpx; max-height:%dpx;" ?
			}
			echo '</ul>';
			if (($instance['follow-text']) != '')
				printf('<a class="view-all" href="http://pinterest.com/%s/" rel="">%s</a>', $instance['username'], $instance['follow-text']);
		}

		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 */
	public function form( $instance ) {

		foreach ($this->form as $input) {
			if (isset ($instance[$input['id']]))
				$value = $instance[$input['id']];
			else
				$value = $input['default'];

			$class = '';
			$checked = 0;
			if ($input['type'] != 'checkbox')
				$class = 'widefat';
			else {
				$checked = $value;
				$value = 1;
				$class = 'checkbox';
			}

			printf('<p><label for="%s">%s:</label>', $this->get_field_id($input['id']), $input['description']);
			printf('<input class="%s" id="%s" name="%s" type="%s" value="%s" %s/></p>', $class, $this->get_field_id($input['id']), $this->get_field_name($input['id']), $input['type'], $value, checked($checked, 1, false));

		}
	}

	/**
	 * Sanitize widget form values as they are saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		foreach($this->form as $input) {
			$instance[$input['id']] = ( ! empty( $new_instance[$input['id']] ) ) ? strip_tags( $new_instance[$input['id']] ) : '';
			if($input['type'] == 'number')
				$instance[$input['id']] = (int) $instance[$input['id']];
			if($input['type'] == 'checkbox')
				$instance[$input['id']] = (bool) $instance[$input['id']];
		}

		return $instance;
	}

	function download_rss ($username, $number, $livetime) {
		// Set caching.
        add_filter('wp_feed_cache_transient_lifetime', create_function('$a', 'return '. $livetime*60 .';'));

        // Get the RSS feed.
        $url = sprintf('https://www.pinterest.com/%s/feed.rss', $username);
        $rss = fetch_feed($url);
        if (is_wp_error($rss)) {
            return null;
        }
        
        $maxitems = $rss->get_item_quantity($number);
        $rss_items = $rss->get_items(0, $maxitems);
        $pins = null;

        if (!is_null($rss_items)) {
        	$search = array('_b.jpg');
            $replace = array('_t.jpg');
        	
			$search2 = array('/236x/');
            $replace2 = array('/90x90/');
			
            $pins = array();
            foreach ($rss_items as $item) {
                $title = $item->get_title();
                $description = $item->get_description();
                $url = $item->get_permalink();
                if (preg_match_all('/<img src="([^"]*)".*>/i', $description, $matches)) {
                    $image = str_replace($search, $replace, $matches[1][0]);
					$image = str_replace($search2, $replace2, $matches[1][0]);
                }
                array_push($pins, array($url, $image, $title));
            }
        }
        return $pins;
	}

} // class Foo_Widget


// Registering Widget
add_action('widgets_init', create_function('', 'return register_widget("Motive_Pinterest_Widget");'));