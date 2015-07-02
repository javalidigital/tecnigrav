<?php

require get_template_directory() . '/functions/custom-page-walker.php';
require get_template_directory() . '/functions/custom-nav-menu-walker.php';
require get_template_directory() . '/functions/custom-nav-menu-edit-walker.php';

//Multi-level pages menu
function converio_page_menu() {
    if (is_page()) { $highlight = "page_item"; } else {$highlight = "menu-item current-menu-item"; }

    $args = array(
		'container' => '',
		'sort_column' => 'menu_order',
		'title_li' => '',
		'link_before' => '',
		'link_after' => '',
		'depth' => 3,
		'walker' => new Converio_Walker_Page()
	);

    echo '<ul>';
    wp_list_pages($args);
    echo '</ul>';
}

function converio_page_menu2() {
    if (is_page()) { $highlight = "page_item"; } else {$highlight = "menu-item current-menu-item"; }
    echo '<ul class="navi h8-navi">';
    wp_list_pages('container=&sort_column=menu_order&title_li=&link_before=&link_after=&depth=3');
    echo '</ul>';
}

function converio_mobile_menu_fallback () {
	if (is_page()) { $highlight = "page_item"; } else {$highlight = "menu-item current-menu-item"; }
    $links = get_pages('sort_column=menu_order&depth=3');
    echo '<select>';
    echo '<option value="'.home_url().'">'.esc_attr__('Menu', 'converio').'</option>';
    foreach($links as $lnk) {
    	$permalink = get_permalink($lnk->ID);
    	if($permalink == converio_current_page_url()) $selected = 'selected="selected"';
		else $selected = '';
    	echo '<option value="'.$permalink.'" '.$selected.'>'.$lnk->post_title.'</option>';
    }
    echo '</select>';

}

/*
* 	Menu custom for icon/description, widget, megamenu
*/

add_filter('wp_edit_nav_menu_walker', 'converio_replace_menu_edit_walker');
function converio_replace_menu_edit_walker() {
	return 'Converio_Walker_Nav_Menu_Edit';
}

add_action( 'converio_menu_item_options', 'converio_create_menu_item_options_fields', 10, 2);

function converio_create_menu_item_options_fields($item, $depth) {
	global $wp_registered_sidebars;

	$item_id = esc_attr( $item->ID );
	$converio_menu_item_options = get_post_meta( $item_id , 'converio-menu-item-options', true );

	$reverse_direction = isset($converio_menu_item_options['converio-reverse-direction'])? esc_attr($converio_menu_item_options['converio-reverse-direction']) : '';
	$icon = isset($converio_menu_item_options['converio-icon'])? esc_attr($converio_menu_item_options['converio-icon']) : '';
	$hide_navigation_label = isset($converio_menu_item_options['converio-hide-navigation-label'])? esc_attr($converio_menu_item_options['converio-hide-navigation-label']) : '';
	$is_megamenu = isset($converio_menu_item_options['converio-is-megamenu'])? esc_attr($converio_menu_item_options['converio-is-megamenu']) : '';
	$columns_headline_disabled = isset($converio_menu_item_options['converio-columns-headline-disabled'])? esc_attr($converio_menu_item_options['converio-columns-headline-disabled']) : '';
	$column_number = isset($converio_menu_item_options['converio-megamenu-columns-' . $item_id])? esc_attr($converio_menu_item_options['converio-megamenu-columns-' . $item_id]) : '';
	$not_fullwidth_megamemu = isset($converio_menu_item_options['converio-not-fullwidth-megamemu'])? esc_attr($converio_menu_item_options['converio-not-fullwidth-megamemu']) : '';
	$show_latest_posts = isset($converio_menu_item_options['converio-show-latest-posts'])? esc_attr($converio_menu_item_options['converio-show-latest-posts']) : '';
	$show_author_info = isset($converio_menu_item_options['converio-show-author-info'])? esc_attr($converio_menu_item_options['converio-show-author-info']) : '';
	$widget_area = isset($converio_menu_item_options['converio-widget-area'])? esc_attr($converio_menu_item_options['converio-widget-area']) : '';
?>
	<div class="clearfix"></div>

	<fieldset class="converio-menu-item-option-box">
		<legend>Converio Menu Options</legend>

		<input id="converio-menu-item-options-<?php echo $item_id;?>" class="converio-menu-item-options-input" name="converio-menu-item-options[<?php echo $item_id;?>]" type="hidden" value="" >

		<p class="field-converio-reverse-direction description description-wide">
			<label for="edit-menu-item-converio-reverse-direction-<?php echo $item_id; ?>">
				<input type="checkbox" id="edit-menu-item-converio-reverse-direction-<?php echo $item_id; ?>" class="widefat code edit-menu-item-converio-reverse-direction" data-field-name="converio-reverse-direction" value="checked" <?php echo $reverse_direction; ?> >
				<?php esc_attr_e( 'Reverse dropdown menu direction', 'converio' ); ?><br>
			</label>
		</p>

		<p class="field-converio-icon description description-wide">
			<label for="edit-menu-item-converio-icon-<?php echo $item_id; ?>">
				<?php esc_attr_e( 'Font Awesome icon name, for example: fa-rocket', 'converio' ); ?><br>
				<input type="text" id="edit-menu-item-converio-icon-<?php echo $item_id; ?>" class="widefat code edit-menu-item-converio-icon" data-field-name="converio-icon" value="<?php echo esc_attr($icon); ?>" >
			</label><br>
		</p>

		<p class="field-converio-hide-navigation-label description description-wide">
			<label for="edit-menu-item-converio-hide-navigation-label-<?php echo $item_id; ?>">
				<input type="checkbox" id="edit-menu-item-converio-hide-navigation-label-<?php echo $item_id; ?>" class="widefat code edit-menu-item-converio-hide-navigation-label" data-field-name="converio-hide-navigation-label" value="checked" <?php echo $hide_navigation_label; ?> >
				<?php esc_attr_e( 'Hide navigation label if there is an icon', 'converio' ); ?><br>
			</label>
		</p>

		<div class="converio-menu-item-megamenu-option-box">
			<p class="field-converio-is-megamenu description description-wide">
				<label for="edit-menu-item-converio-is-megamenu-<?php echo $item_id; ?>">
					<input type="checkbox" id="edit-menu-item-converio-is-megamenu-<?php echo $item_id; ?>" class="widefat code edit-menu-item-converio-is-megamenu" data-field-name="converio-is-megamenu" value="checked" <?php echo $is_megamenu; ?> >
					<?php esc_attr_e( 'Megamenu', 'converio' ); ?><br>
				</label>
			</p>

			<div class="clearfix"></div>

			<div class="converio-menu-item-megamenu-collapse-option-box">
				
				<p class="field-converio-megamenu-columns description description-wide">
					<label for="edit-menu-item-converio-megamenu-columns2-<?php echo $item_id; ?>">
						<input type="radio" class="widefat code edit-menu-item-converio-megamenu-columns" name="converio-megamenu-columns-<?php echo $item_id; ?>" value="columns2" <?php echo $column_number == 'columns2'? 'checked' : ''; ?> >
						<?php esc_attr_e( '2 Columns', 'converio' ); ?>
					</label>
					<label for="edit-menu-item-converio-megamenu-columns3-<?php echo $item_id; ?>">
						<input type="radio" class="widefat code edit-menu-item-converio-megamenu-columns" name="converio-megamenu-columns-<?php echo $item_id; ?>" value="columns3" <?php echo $column_number == 'columns3'? 'checked' : ''; ?> >
						<?php esc_attr_e( '3 Columns', 'converio' ); ?>
					</label>
					<label for="edit-menu-item-converio-megamenu-columns4-<?php echo $item_id; ?>">
						<input type="radio" class="widefat code edit-menu-item-converio-megamenu-columns" name="converio-megamenu-columns-<?php echo $item_id; ?>" value="columns4" <?php echo $column_number == '' || $column_number == 'columns4'? 'checked' : ''; ?> >
						<?php esc_attr_e( '4 Columns', 'converio' ); ?>
					</label>
					<label for="edit-menu-item-converio-megamenu-columns5-<?php echo $item_id; ?>">
						<input type="radio" class="widefat code edit-menu-item-converio-megamenu-columns" name="converio-megamenu-columns-<?php echo $item_id; ?>" value="columns5" <?php echo $column_number == 'columns5'? 'checked' : ''; ?> >
						<?php esc_attr_e( '5 Columns', 'converio' ); ?>
					</label>
					<label for="edit-menu-item-converio-megamenu-columns6-<?php echo $item_id; ?>">
						<input type="radio" class="widefat code edit-menu-item-converio-megamenu-columns" name="converio-megamenu-columns-<?php echo $item_id; ?>" value="columns6" <?php echo $column_number == 'columns6'? 'checked' : ''; ?> >
						<?php esc_attr_e( '6 Columns', 'converio' ); ?>
					</label>
				</p>

	 			<p class="field-converio-not-fullwidth-megamemu description description-wide">
					<label for="edit-menu-item-converio-not-fullwidth-megamemu-<?php echo $item_id; ?>">
						<input type="checkbox" id="edit-menu-item-converio-not-fullwidth-megamemu-<?php echo $item_id; ?>" class="widefat code edit-menu-item-converio-not-fullwidth-megamemu" data-field-name="converio-not-fullwidth-megamemu" value="checked" <?php echo $not_fullwidth_megamemu; ?> >
						<?php _e( 'Not full-width', 'converio' ); ?><br>
					</label>
				</p>

	 			<p class="field-converio-show-latest-posts description description-wide">
					<label for="edit-menu-item-converio-show-latest-posts-<?php echo $item_id; ?>">
						<input type="checkbox" id="edit-menu-item-converio-show-latest-posts-<?php echo $item_id; ?>" class="widefat code edit-menu-item-converio-show-latest-posts" data-field-name="converio-show-latest-posts" value="checked" <?php echo $show_latest_posts; ?> >
						<?php _e( 'Show latest posts <span class="from-this-category">from this category </span>in megamenu', 'converio' ); ?><br>
					</label>
				</p>
	 			<p class="field-converio-show-author-info description description-wide">
					<label for="edit-menu-item-converio-show-author-info-<?php echo $item_id; ?>">
						<input type="checkbox" id="edit-menu-item-converio-show-author-info-<?php echo $item_id; ?>" class="widefat code edit-menu-item-converio-show-author-info" data-field-name="converio-show-author-info" value="checked" <?php echo $show_author_info; ?> >
						<?php esc_attr_e( 'Show author info in megamenu', 'converio' ); ?><br>
					</label>
				</p>
			</div>
		</div>

		<p class="field-converio-columns-headline-disabled description description-wide">
			<label for="edit-menu-item-converio-columns-headline-disabled-<?php echo $item_id; ?>">
				<input type="checkbox" id="edit-menu-item-converio-columns-headline-disabled-<?php echo $item_id; ?>" class="widefat code edit-menu-item-converio-columns-headline-disabled" data-field-name="converio-columns-headline-disabled" value="checked" <?php echo $columns_headline_disabled; ?> >
				<?php esc_attr_e( 'Don\'t display as columns headline in megamenu', 'converio' ); ?><br>
			</label>
		</p>

		<p class="field-converio-widget-area description description-wide">
			<label for="edit-menu-item-converio-widget-area-<?php echo $item_id; ?>">
				<?php esc_attr_e( 'Mega Menu Widget Area', 'converio' ); ?><br>
				<select id="edit-menu-item-converio-widget-area-<?php echo $item_id; ?>" class="widefat code edit-menu-item-converio-widget-area" data-field-name="converio-widget-area">
					<option value=""><?php esc_attr_e( 'Select Widget Area', 'converio' ); ?></option>
					<?php foreach ($wp_registered_sidebars as $sbname => $sbvalue) : ?>
					<option value="<?php echo $sbname; ?>" <?php echo $sbname == $widget_area? 'selected' : ''; ?> ><?php echo $sbvalue['name']; ?></option>
					<?php endforeach; ?>
				</select>
			</label><br>
		</p>
	</fieldset>
<?php
}

// replace this line with your theme version
define('CONVERIO_VERSION', '1.0.1');

add_action( 'admin_print_styles-nav-menus.php', 'converio_load_admin_nav_menu_JS');
add_action( 'admin_print_styles-nav-menus.php', 'converio_load_admin_nav_menu_CSS');

function converio_load_admin_nav_menu_JS() {
	wp_enqueue_script('converio-admin-js', get_template_directory_uri().'/js/converio.admin.js', array('jquery'), CONVERIO_VERSION, true);
}

function converio_load_admin_nav_menu_CSS() {
	wp_enqueue_style('converio-admin-css', get_template_directory_uri().'/styles/style-admin.css', array(), CONVERIO_VERSION, 'all');
}

//Appearance > Menus : save custom menu options
add_action( 'wp_update_nav_menu_item', 'update_converio_nav_menu_item', 10, 3);

function update_converio_nav_menu_item( $menu_id, $menu_item_db_id, $args ) {
	$optionDefaults = array();
	$menu_item_options = array();

	//Parse the serialized string of UberMenu Options into an array
	$menu_item_options_str = isset( $_POST['converio-menu-item-options'][$menu_item_db_id] ) ? $_POST['converio-menu-item-options'][$menu_item_db_id] : '';
	parse_str($menu_item_options_str, $menu_item_options);
	$menu_item_options = wp_parse_args($menu_item_options, $optionDefaults);

	update_post_meta($menu_item_db_id, 'converio-menu-item-options', $menu_item_options);
}