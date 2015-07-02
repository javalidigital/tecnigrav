<?php

/**
 * Create HTML list of nav menu items.
 *
 * Customize: output HTML structure base on the custom fields
 *
 * @since 3.0.0
 * @uses Walker
 */
class Converio_Walker_Nav_Menu extends Walker_Nav_Menu {
	private $is_processing_megamenu = false;
	private $is_processing_magazine_megamenu = false;
	private $is_dropdown_arrow_disabled = 1;
	// used to temporarily store the HTML codes of headline menu item of each column in megamenu, this menu item will merge to next level submenu
	private $headline_merged_cache = '';

	function __construct() {
		$dropdown_arrow_disabled = get_theme_mod('dropdown_arrow_disabled');
		if(isset($dropdown_arrow_disabled) && $dropdown_arrow_disabled === '0') {
			$this->is_dropdown_arrow_disabled = 0;
		}
	}

	function should_display_dropdown_arrow($menuItem, $converio_menu_item_options, $depth) {
		if(!$this->is_dropdown_arrow_disabled && $depth == 0) {

			if($this->has_children($menuItem)) {
				return true;
			} else {
				if($this->is_magazine_megamenu($converio_menu_item_options, $depth) && $this->is_magazine_megamenu_not_empty($menuItem, $converio_menu_item_options, $depth)) {
					return true;
				} else {
					return false;
				}
			}
		} else {
			return false;
		}
	}

	function should_output_font_icons($converio_menu_item_options) {
		return isset($converio_menu_item_options['converio-icon']) && $converio_menu_item_options['converio-icon'];
	}

	function should_output_widget($converio_menu_item_options, $depth) {
		return $this->is_processing_megamenu && $depth >= 1 && isset($converio_menu_item_options['converio-widget-area']) && $converio_menu_item_options['converio-widget-area'] && is_active_sidebar($converio_menu_item_options['converio-widget-area']);
	}

	function has_children($menuItem) {
		return in_array('menu-item-has-children', $menuItem->classes);
	}

	function is_current_menu_item($menuItem) {
		return in_array('current-menu-item', $menuItem->classes);
	}

	function is_current_menu_ancestor($menuItem) {
		return in_array('current-menu-ancestor', $menuItem->classes);
	}

	function should_hide_navigation_label($converio_menu_item_options) {
		return $this->should_output_font_icons($converio_menu_item_options) && isset($converio_menu_item_options['converio-hide-navigation-label']) && $converio_menu_item_options['converio-hide-navigation-label'] == 'checked';
	}

	function is_megamenu($converio_menu_item_options, $depth) {
		return $depth == 0 && isset($converio_menu_item_options['converio-is-megamenu']) && $converio_menu_item_options['converio-is-megamenu'] == 'checked';
	}

	function is_not_fullwidth_megamenu($converio_menu_item_options, $depth) {
		return $this->is_megamenu($converio_menu_item_options, $depth) && isset($converio_menu_item_options['converio-not-fullwidth-megamemu']) && $converio_menu_item_options['converio-not-fullwidth-megamemu'] == 'checked';
	}

	function is_magazine_megamenu($converio_menu_item_options, $depth) {
		return $this->is_megamenu($converio_menu_item_options, $depth) && isset($converio_menu_item_options['converio-show-latest-posts']) && $converio_menu_item_options['converio-show-latest-posts'] == 'checked';
	}

	// check if the magazine megamenu has submenu or has latest posts
	function is_magazine_megamenu_not_empty($item, $converio_menu_item_options, $depth) {
		return $this->is_magazine_megamenu($converio_menu_item_options, $depth) && ($this->has_children($item) || $this->has_latest_posts($item, $converio_menu_item_options, $depth));
	}

	function is_megamenu_columns_headline_disabled($converio_menu_item_options) {
		return isset($converio_menu_item_options['converio-columns-headline-disabled']) && $converio_menu_item_options['converio-columns-headline-disabled'] == 'checked';
	}

	function is_second_level_magazine_menu_item($depth) {
		return $this->is_processing_magazine_megamenu && $depth >= 1;
	}

	function should_output_megamenu_columns_headline($converio_menu_item_options, $depth) {
		return $this->is_processing_megamenu && !$this->is_processing_magazine_megamenu && !$this->is_megamenu_columns_headline_disabled($converio_menu_item_options) && $depth === 1;
	}

	function should_output_text_title($link_attrs, $depth) {
		return $this->is_processing_megamenu && !$this->is_processing_magazine_megamenu && !$this->is_not_empty_link($link_attrs) && $depth === 1;
	}

	function is_category($menuItem) {
		return in_array('menu-item-object-category', $menuItem->classes);
	}

	function is_not_empty_link($link_attrs) {
		foreach ($link_attrs as $attr => $value) {
			$value = trim($value);
			if ('href' === $attr && !empty($value) && $value !== '#') {
				return true;
			}
		}

		return false;
	}

	function should_merge_headline_to_this_level($depth) {
		return $this->is_processing_megamenu && !$this->is_processing_magazine_megamenu && $depth === 1 && !empty($this->headline_merged_cache);
	}

	function should_merge_headline_to_next_level($converio_menu_item_options, $depth) {
		return $this->is_processing_megamenu && !$this->is_processing_magazine_megamenu && $depth === 1 && $this-> is_megamenu_columns_headline_disabled($converio_menu_item_options) && empty($this->headline_merged_cache);
	}

	function create_dummy_menu_item_for_down_merge($item, $depth) {
		$item_output = '';
		$dummy_class_names = '';

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$dummy_classes = empty( $item->classes ) ? array() : (array) $item->classes;

		if($this->is_current_menu_item($item)) {
			// remove the class 'current-menu-ancestor'
			$dummy_classes[] = 'current-menu-ancestor';

			// remove the class 'current-menu-item'
			if(($key = array_search('current-menu-item', $dummy_classes)) !== false) {
				unset($dummy_classes[$key]);
			}
		}

		$dummy_classes[] = 'mi-depth-' . $depth;

		$dummy_classes[] = 'mi-headline-merged';

		$dummy_class_names = join(' ', $dummy_classes);
		$dummy_class_names = $dummy_class_names ? ' class="' . esc_attr( $dummy_class_names ) . '"' : '';

		$item_output = $indent . '<li' . $dummy_class_names .'>';

		return $item_output;
	}

	/**
	 * Starts the list before the elements are added.
	 *
	 * @see Walker::start_lvl()
	 *
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"sub-menu\">\n";

		// merge headline from higher level sub menu
		// Pay attention to the $depth, it doesn't +1 yet.
		if($this->should_merge_headline_to_this_level($depth)) {
			$output .= $this->headline_merged_cache;
			$this->headline_merged_cache = '';
		}
	}

	/**
	 * Start the element output.
	 *
	 * @see Walker::start_el()
	 *
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 * @param int    $id     Current item ID.
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$converio_menu_item_options = get_post_meta( $item->ID , 'converio-menu-item-options', true );

		$class_names = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;

		$classes[] = 'menu-item-' . $item->ID;

		if($this->is_second_level_magazine_menu_item($depth) || $this->should_merge_headline_to_next_level($converio_menu_item_options, $depth)) {
			$classes[] = 'mi-depth-' . ($depth+1);
		} else {
			$classes[] = 'mi-depth-' . $depth;
		}
		
		// class for menu item icon
		if($this->should_output_font_icons($converio_menu_item_options)) {
			$classes[] = 'mi-with-icon';
		}
		if($this->should_hide_navigation_label($converio_menu_item_options)) {
			$classes[] = 'mi-without-title';
		}

		// class for menu item description
		if($item->description) {
			$classes[] = 'mi-with-description';
		} else {
			// use this class for the position of icon in small screen
			$classes[] = 'mi-without-description';
		}

		if($this->should_display_dropdown_arrow($item, $converio_menu_item_options, $depth)) {
			$classes[] = 'mi-with-dropdown-arrow';
		}

		// add class "menu-item-has-children" to magazine if it doesn't have submenu but have "latest posts"
		if($this->is_magazine_megamenu($converio_menu_item_options, $depth) && $this->is_magazine_megamenu_not_empty($item, $converio_menu_item_options, $depth)) {
			if(!in_array('menu-item-has-children', $classes)) {
				$classes[] = 'menu-item-has-children';
			}
		}

		// class for dropdown menu
		if($depth == 0) {
			if($this->is_megamenu($converio_menu_item_options, $depth)) {
				$this->is_processing_megamenu = true;
				$classes[] = 'dropdownmenu';
				$classes[] = 'dropdownmenu-mega';

				if($this->is_not_fullwidth_megamenu($converio_menu_item_options, $depth)) {
					$classes[] = 'mega-not-fullwidth';
				}

				if($this->is_magazine_megamenu($converio_menu_item_options, $depth)) {
					$this->is_processing_magazine_megamenu = true;
					$classes[] = 'mega-magazine';
				} else {
					$classes[] = 'mega-default';
				}

				// columns
				if(!$this->is_magazine_megamenu($converio_menu_item_options, $depth)) {
					if(isset($converio_menu_item_options['converio-megamenu-columns-' . $item->ID])) {
						$classes[] = $converio_menu_item_options['converio-megamenu-columns-' . $item->ID];
					} else {
						$classes[] = 'columns4';
					}
				}
			} else if($this->has_children($item)) {
				$classes[] = 'dropdownmenu';
				$classes[] = 'dropdownmenu-default';
			} else {
				// do nothing for menu item without dropdown menu
			}
		}

		// class for widget
		if($this->should_output_widget($converio_menu_item_options, $depth)) {
			$classes[] = 'mi-with-widget';
		}

		// class for direction reverse
		if(!$this->is_processing_megamenu && isset($converio_menu_item_options['converio-reverse-direction']) && $converio_menu_item_options['converio-reverse-direction']) {
			$classes[] = 'reverse';
		}

		/**
		 * Filter the CSS class(es) applied to a menu item's <li>.
		 *
		 * @since 3.0.0
		 *
		 * @see wp_nav_menu()
		 *
		 * @param array  $classes The CSS classes that are applied to the menu item's <li>.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of wp_nav_menu() arguments.
		 */
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		/**
		 * Filter the ID applied to a menu item's <li>.
		 *
		 * @since 3.0.1
		 *
		 * @see wp_nav_menu()
		 *
		 * @param string $menu_id The ID that is applied to the menu item's <li>.
		 * @param object $item    The current menu item.
		 * @param array  $args    An array of wp_nav_menu() arguments.
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		if(!$this->should_merge_headline_to_next_level($converio_menu_item_options, $depth)) {
			$output .= $indent . '<li' . $id . $class_names .'>';
		}

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';
		// if <a> doesn't have an empty link, assign .submenu-trigger to it. Otherwise, assign to .nolink-title
		$atts['class']  = 'submenu-trigger';

		/**
		 * Filter the HTML attributes applied to a menu item's <a>.
		 *
		 * @since 3.6.0
		 *
		 * @see wp_nav_menu()
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's <a>, empty strings are ignored.
		 *
		 *     @type string $title  Title attribute.
		 *     @type string $target Target attribute.
		 *     @type string $rel    The rel attribute.
		 *     @type string $href   The href attribute.
		 * }
		 * @param object $item The current menu item.
		 * @param array  $args An array of wp_nav_menu() arguments.
		 */
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/* create icon output */
		$icon_output = '';
		if($this->should_output_font_icons($converio_menu_item_options)) {
			$icon_output = '<i class=\'fa ' . esc_attr(strtolower($converio_menu_item_options['converio-icon'])).'\' title=\'' . apply_filters( 'the_title', esc_attr($item->title), $item->ID ) . '\'></i>';
		}

		/* create description output */
		$description_output = '';
		if($item->description) {
			$description_output = '<span class=\'mi-desc\'>' . esc_attr($item->description) . '</span>';
		}

		if($this->should_output_widget($converio_menu_item_options, $depth)) {
			$output .= '<div class="widget-area">';
			ob_start();
			dynamic_sidebar($converio_menu_item_options['converio-widget-area']);
			$output .= ob_get_clean();
			$output .= '</div>';
		} else {
			$item_output = '';

			// <h3> start tag, add <h3> for megamenu headline
			if($this->should_output_megamenu_columns_headline($converio_menu_item_options, $depth)) {
				$item_output .= '<h3 class="submenu-trigger-container">';
			}

			// link start tag
			if($this->should_output_text_title($atts, $depth)) {
				$item_output .= '<span class="nolink-title submenu-trigger">';
			} else {
				$item_output .= $args->before;
				$item_output .= '<a'. $attributes .'>';
			}

			/** This filter is documented in wp-includes/post-template.php */
			$item_output .= $args->link_before . '<span class="mi-title-wrapper"><span class="mi-title">' . $icon_output;

			if(!$this->should_hide_navigation_label($converio_menu_item_options)) {
				$item_output .= apply_filters( 'the_title', esc_attr($item->title), $item->ID );
			}

			$item_output .= '</span></span>' . $description_output . $args->link_after;

			// link close tag, same condition with link start tag
			if($this->should_output_text_title($atts, $depth)) {
				$item_output .= '</span>';
			} else {
				$item_output .= '</a>';
				$item_output .= $args->after;
			}

			// <h3> close tag, same condition with <h3> start tag
			if($this->should_output_megamenu_columns_headline($converio_menu_item_options, $depth)) {
				$item_output .= '</h3>';
			}

			if($this->should_merge_headline_to_next_level($converio_menu_item_options, $depth)) {

                // remove class .current-menu-ancestor and .current-menu-parent from $class_names
                $class_names = str_replace('current-menu-ancestor', '', $class_names);
                $class_names = str_replace('current-menu-parent', '', $class_names);

				// cache the $item_output to use in next level submenu
				$this->headline_merged_cache = $indent . "\t" . '<li' . $id . $class_names .'>' . $item_output . "</li>\n";

				// totally create a new menu item for this <li>
				$item_output = $this->create_dummy_menu_item_for_down_merge($item, $depth);
			}

			/**
			 * Filter a menu item's starting output.
			 *
			 * The menu item's starting output only includes $args->before, the opening <a>,
			 * the menu item's title, the closing </a>, and $args->after. Currently, there is
			 * no filter for modifying the opening and closing <li> for a menu item.
			 *
			 * @since 3.0.0
			 *
			 * @see wp_nav_menu()
			 *
			 * @param string $item_output The menu item's starting HTML output.
			 * @param object $item        Menu item data object.
			 * @param int    $depth       Depth of menu item. Used for padding.
			 * @param array  $args        An array of wp_nav_menu() arguments.
			 */
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

			/**
			 * Structures for magazine megamenu
			 */
			if($this->is_magazine_megamenu($converio_menu_item_options, $depth)) {
				if($this->has_children($item) || $this->has_latest_posts($item, $converio_menu_item_options, $depth)) {
					$output .= '<ul class="sub-menu content-wrapper">';

					if($this->has_children($item)) {
						$output .= '<li class="menu-item sub-menu-wrapper mi-depth-1">';
					}
				}
			}
		}
	}

	function build_latest_posts_query_options($item) {
		if($this->has_children($item)) {
			$post_number = 3;
		} else {
			$post_number = 4;
		}
		
	    $options = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => $post_number,
			'ignore_sticky_posts' => true,
			'orderby' => 'date',
			'order' => 'desc'
		);

		if($this->is_category($item)) {
			$options['cat'] = $item->object_id;
		}

		return $options;
	}

	function has_latest_posts($item, $converio_menu_item_options, $depth) {
		if($this->is_magazine_megamenu($converio_menu_item_options, $depth)) {
			$options = $this->build_latest_posts_query_options($item);
			$posts_query = new WP_Query($options);

			return $posts_query->have_posts();
		}

		return false;
	}

	function get_latest_posts_for_magazine_megamenu(&$output, $item, $converio_menu_item_options, $depth) {
		global $post;

		if($this->is_magazine_megamenu($converio_menu_item_options, $depth)) {
			$options = $this->build_latest_posts_query_options($item);
			$posts_query = new WP_Query($options);

			if($this->has_children($item)) {
				$post_number = 3;
			} else {
				$post_number = 4;
			}

			$output .= '<li class="menu-item latest-posts-wrapper"><ul class="latest-posts columns';
			$output .= esc_attr($post_number);
			$output .= '">';

			if($posts_query->have_posts()) {
				while($posts_query->have_posts()) {
					$posts_query->the_post();

					if(has_post_thumbnail(get_the_ID())) {
						$output .= '<li>';
						$output .= '<a href="';
						$output .= esc_url(get_permalink());
						$output .= '">';
						$output .= get_the_post_thumbnail(get_the_ID(), 'thumbnail-related');
						$output .= '</a>';
						$this->get_post_title($output);
					} else {
						$output .= '<li class="post-without-image">';
						$this->get_post_title($output);
						$output .= '<p class="excerpt">';
						
						$output .= converio_custom_excerpt(30, $post);
						
						$output .= '</p>';
					}

					if($this->should_output_author_info($converio_menu_item_options)) {
						$output .= '<div class="author-and-category">';
						$this->get_author_info($output);
						$output .= '<br>';
						$this->get_post_category($output);
						$output .= '</div>';
					}

					$output .= '</li>';
				}
			}
			wp_reset_postdata();

			$output .= '</ul></li>';
		}
	}

	function get_post_title(&$output) {
		$output .= '<h3><a href="';
		$output .= esc_url(get_the_permalink());
		$output .= '">';
		$output .= esc_attr(get_the_title());
		$output .= '</a></h3>';
	}

	function get_author_info(&$output) {
		$output .= '<a class="avatar-link" href="';
		$output .= esc_url(get_author_posts_url(get_the_author_meta('ID')));
		$output .= '">';
		$output .= get_avatar(get_the_author_meta('ID'), 40);
		$output .= '</a>';
		$output .= '<a class="author-link" href="';
		$output .= esc_url(get_author_posts_url(get_the_author_meta('ID')));
		$output .= '">By ';
		$output .= esc_attr(get_the_author());
		$output .= '</a>';
	}

	function get_post_category(&$output) {
		$category = get_the_category(); 
		if($category[0]){
			$output .= '<a class="category-link" href="' . esc_url(get_category_link($category[0]->term_id )) . '">' . esc_attr($category[0]->cat_name) . '</a>';
		}
	}

	function should_output_author_info($converio_menu_item_options) {
		return isset($converio_menu_item_options['converio-show-author-info']) && $converio_menu_item_options['converio-show-author-info'] == 'checked';
	}

	/**
	 * Ends the element output, if needed.
	 *
	 * @see Walker::end_el()
	 *
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Page data object. Not used.
	 * @param int    $depth  Depth of page. Not Used.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$converio_menu_item_options = get_post_meta( $item->ID , 'converio-menu-item-options', true );

		if($this->is_magazine_megamenu($converio_menu_item_options, $depth)) {
			if($this->has_children($item)) {
				$output .= "</li>\n";
			}

			$this->get_latest_posts_for_magazine_megamenu($output, $item, $converio_menu_item_options, $depth);

			if($this->has_children($item) || $this->has_latest_posts($item, $converio_menu_item_options, $depth)) {
				$output .= "</ul>\n";
			}
		}

		$output .= "</li>\n";
		
		// reset the flags of megamenu, finish the structure population of this megamenu
		if($this->is_megamenu($converio_menu_item_options, $depth)) {
			$this->is_processing_megamenu = false;

			if($this->is_magazine_megamenu($converio_menu_item_options, $depth)) {
				$this->is_processing_magazine_megamenu = false;
			}
		}

		apply_filters( 'converio_end_el', $item);
	}

} // Walker_Nav_Menu

?>