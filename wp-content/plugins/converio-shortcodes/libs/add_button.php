<?php
//Creating TinyMCE buttons
//********************************************************************
//check user has correct permissions and hook some functions into the tiny MCE architecture.
function add_editor_button() {
   //Check if user has correct level of privileges + hook into Tiny MC methods.
   if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') )
   {
     //Check if Editor is in Visual, or rich text, edior mode.
     if (get_user_option('rich_editing')) {
        //Called when tiny MCE loads plugins - 'add_custom' is defined below.
        add_filter('mce_external_plugins', 'add_custom');
        //Called when buttons are loading. -'register_button' is defined below.
        add_filter('mce_buttons', 'register_button');
     }
   }
} 
 
//add action is a wordpress function, it adds a function to a specific action...
//in this case the function is added to the 'init' action. Init action runs after wordpress is finished loading!
add_action('init', 'add_editor_button');

//Add button to the button array.
function register_button($buttons) {
   //Use PHP 'array_push' function to add the columnThird button to the $buttons array
   array_push($buttons, "columnThird");
   //Return buttons array to TinyMCE
   return $buttons;
} 
 
//Add custom plugin to TinyMCE - returns associative array which contains link to JS file. The JS file will contain your plugin when created in the following step.
function add_custom($plugin_array) {
		if(get_bloginfo('version') > 3.8)
       		$plugin_array['columnThird'] = plugin_dir_url( __file__ ).'js/shortcode-3.9.js';
       	else
       		$plugin_array['columnThird'] = plugin_dir_url( __file__ ).'js/shortcode.js';

       	return $plugin_array;
}
function add_shortcode_menu() {
?>	

<div class="hidden" style="display:none">
<div class="shortcode-html">
	<a href="javascript:;" class="button-add-shortcode">[+]</a>
	<ul>
		<li class="parent">
			<a href="javascript:;" onclick="return false;">Elements</a>
			<ul>				
				<li><a href="#" data-all='[accordions]<br/>[accordion title="Accordion one"]<p>Accordion one content</p>[/accordion]<br/>[accordion title="Accordion two"]<p>Accordion two content</p>[/accordion]<br/>[accordion title="Accordion three"]<p>Accordion three content</p>[/accordion]<br/>[/accordions]'>Accordion</a></li>
				<li><a href="#" data-param='box_type="e.g. info, success, notice, error" hide_text="x"'  data-tag='messages' data-all="" data-text="Messages text">Alert message</a></li>
				<li><a href="#" data-param='color="e.g. green, turquoise, light-blue, blue, purple, pink, red, orange, yellow, brown, custom" size="e.g. default, small, large" light="e.g. yes, no" icon="e.g. fa-map-marker" open_in_new_window="e.g. yes, no" link=""' data-tag='button' data-all='' data-text='Button text'>Button</a></li>
				<li><a href="#" data-all='[checklist style="e.g. 1, 2, 3, 4"]<ul><li>Item 1</li><li>Item 2</li><li>Item 3</li></ul>[/checklist]'>Checklist</a></li>
				<li><a href="#" data-all='<dl><dt>Lorem ipsum dolor</dt><dd><p class="default">Duis porttitor dapibus pellentesque feugiat lectus ipsum vehicula, ut convallis tortor lorem.</p><p class="default">Curabitur dui libero posuere quis blandit non ullamcorper vitae ipsum.</p></dd><dt>Suspendisse sagittis sodales</dt><dd><p class="default">Sum sociis natoque penatibus et magnis dis parturient montes nascetur ridiculus. </p></dd></dl>'>Definition list</a></li>	
				<li><a href="#" data-param='padding_top="e.g. 0" padding_bottom="e.g. 0" background_color="e.g. #fff" background_image="Put Image Url" background_class="e.g. white, brown, purple" top_border="e.g. yes, no" shadow="e.g. yes, no" parallax="e.g. yes/no" bottom_margin="yes or no" background_position="e.g. left top, left center, left bottom, right top, right center, right bottom, center top, center center, center bottom" background_repeat="e.g. repeat, no-repeat, repeat-x, repeat-y" pattern="e.g. 01, 02, 03, 04, 05, 06, 07, 08, 09, 10" opacity="e.g. 0.10"' data-tag='fullwidth' data-all="" data-text="">Fullwidth Container</a></li>
				<li><a href="#" data-all='[gap size="" id="" class="" style=""]'>Gap</a></li>
				<li><a href="#" data-param='image_url="put image URL here" image_alt="Image description" class=""' data-tag='lead' data-all="" data-text="<h1>Lead heading</h1><p>Place for text</p>">Lead</a></li>
				<li><a href="#" data-all='[toggles]<br/>[toggle title="Toggle one"]<p>Toggle one content</p>[/toggle]<br/>[toggle title="Toggle two"]<p>Toggle two content</p>[/toggle]<br/>[toggle title="Toggle three"]<p>Toggle three content</p>[/toggle]<br/>[/toggles]'>Toggle</a></li>				
				<li><a href="#" data-all='<table><caption>Table caption. Lorem ipsum dolor sit amet.</caption><tr><th>Name</th><th>Cups</th><th>Type of Coffee</th><th>Sugar</th></tr><tr><td><b>N. Dynamite</b></td><td>10</td><td>Espresso</td><td>Yes</td></tr><tr><td><b>P. Sánchez</b></td><td>3</td><td>Decaf</td><td>No</td></tr><tr><td><b>S. Wheatley</b></td><td>4</td><td>Americano</td><td>No</td></tr></table>	'>Table</a></li>
				<li><a href="#" data-param='button_text="e.g. Show more" button_color="e.g. orange, green, turquoise, blue, purple, pink, red, dark-gray, light-gray" button_size="e.g. default, small, large" button_light="e.g. yes, no" button_icon="e.g. fa-map-marker"' data-tag='show_hide' data-all="" data-text="<p>Lorem ipsum dolor sit amet</p><p>Lorem ipsum dolor sit amet</p>">Show/Hide Content</a></li>
			</ul>	
		</li>
		<li class="parent">
			<a href="javascript:;" onclick="return false;">Columns</a>
			<ul>
				<li><a href="#" data-param='' data-tag='columns' data-all="" data-text="">Columns Container</a></li>
				<li><a href="#" data-all='[columns]<br/>[one_full]One Full[/one_full]<br/>[/columns]'>1/1</a></li>
				<li><a href="#" data-all='[columns]<br/>[one_half]One half[/one_half] <br/>[one_half]One half[/one_half]<br/>[/columns]'>1/2, 1/2</a></li>
				<li><a href="#" data-all='[columns]<br/>[one_third]One third[/one_third]<br/>[one_third]One third[/one_third]<br/>[one_third]One third[/one_third]<br/>[/columns]'>1/3, 1/3, 1/3</a></li>
				<li><a href="#" data-all='[columns]<br/>[one_fourth]One fourth[/one_fourth]<br/>[one_fourth]One fourth[/one_fourth]<br/>[one_fourth]One fourth[/one_fourth]<br/>[one_fourth]One fourth[/one_fourth]<br/>[/columns]'>1/4, 1/4, 1/4, 1/4</a></li>
				<li><a href="#" data-all='[columns]<br/>[one_third]One third[/one_third]<br/>[two_thirds]Two thirds[/two_thirds]<br/>[/columns]'>1/3, 2/3</a></li>
				<li><a href="#" data-all='[columns]<br/>[one_fourth]One fourth[/one_fourth]<br/>[one_fourth]One fourth[/one_fourth]<br/>[one_half]One half[/one_half]<br/>[/columns]'>1/4, 1/4, 1/2</a></li>
				<li><a href="#" data-all='[columns]<br/>[one_fourth]One fourth[/one_fourth]<br/>[three_fourths]Three fourths[/three_fourths]<br/>[/columns]'>1/4, 3/4</a></li>
				<li><a href="#" data-all='[columns]<br/>[one_fifth]One fifth[/one_fifth]<br/>[one_fifth]One fifth[/one_fifth]<br/>[one_fifth]One fifth[/one_fifth]<br/>[one_fifth]One fifth[/one_fifth]<br/>[one_fifth]One fifth[/one_fifth]<br/>[/columns]'>1/5, 1/5, 1/5, 1/5, 1/5</a></li>
				<li><a href="#" data-all='[columns]<br/>[one_sixth]One sixth[/one_sixth]<br/>[one_sixth]One sixth[/one_sixth]<br/>[one_sixth]One sixth[/one_sixth]<br/>[one_sixth]One sixth[/one_sixth]<br/>[one_sixth]One sixth[/one_sixth]<br/>[one_sixth]One sixth[/one_sixth]<br/>[/columns]'>1/6, 1/6, 1/6, 1/6, 1/6, 1/6</a></li>
			</ul>	
		</li>
		<li class="parent">
			<a href="javascript:;" onclick="return false;">Sections</a>
			<ul>
				<li><a href="#" data-all='[clients title="Place for title" title_size="e.g. 2, 3, 4"]<br/>[client title="client one" link="Link" image_alt="Image alt" image_url="Image url"][/client]<br/>[client title="client two" link="Link" image_alt="Image alt" image_url="Image url"][/client]<br/>[client title="client three" link="Link" image_alt="Image alt" image_url="Image url"][/client]<br/>[/clients]'>Clients</a></li>
				<li><a href="#" data-all='[image_slider type="e.g. 1,2" translate_next="Next" translate_previous="Previous"]<br/>[image url="Put image URL here" width="e.g. 730" height="e.g. 390" alt="Image description"]<br/>[image url="Put image URL here" width="e.g. 730" height="e.g. 390" alt="Image description"]<br/>[image url="Put image URL here" width="e.g. 730" height="e.g. 390" alt="Image description"]<br/>[/image_slider]'>Image Slider</a></li>
				<li><a href="#" data-all='[promobox pattern="e.g. 01, 02, 03, 04, 05, 06, 07, 08, 09, 10" opacity="e.g. 0.15" background_color="e.g. #70c14a" fullwidth="e.g. yes, no" bottom_margin="e.g. yes, no" top_margin="e.g. yes, no"]<br>[slogan color="e.g. #fff"]Place for slogan text[/slogan]<br>[button color="e.g. orange, green, turquoise, blue, purple, pink, red, dark-gray, light-gray" size="e.g. default, small, large" light="e.g. yes, no" icon="e.g. fa-map-marker" open_in_new_window="e.g. yes, no" link=""]Button text[/button]<br>[/promobox]'>Promo box</a></li>
				<li><a href="#" data-all='[recent_works title="Featured Works" number_columns="e.g. 3, 4" number_posts="e.g. 10" view_anchor_text="View" headline="yes or no" category_name=""][/recent_works]'>Recent Projects Carousel</a></li>
				<li><a href="#" data-all='[recent_posts number_of_columns="e.g. 4" number_of_posts="e.g. 4"]'>Recent Posts</a></li>
				<li><a href="#" data-all='[slider] <br/>[slide title="Slide one" image_url="Image url" image_alt="Image alt"]<br/>[slide_content]Slide content[/slide_content]<br/>[/slide]<br/>[slide title="slide two" image_url="Image url" image_alt="Image alt"]<br/>[slide_content]Slide content[/slide_content]<br>[/slide]<br/>[slide title="Slide three" image_url="Image url" image_alt="Image alt"]<br/>[slide_content]Slide content[/slide_content]<br>[/slide]<br/>[/slider]'>Slider</a></li>
				<li><a href="#" data-all='[social_icons style="e.g. default, light" colored="e.g. yes, no"]<br>[social link="Link" type="e.g. email, facebook, twitter, pinterest, rss, linkedin, flickr, vimeo, blogger, tumblr, skype, behance, googleplus, youtube, dribble, instagram, picasa, github, stumbleupon, lastfm, etc." title="" open_in_new_window="e.g. yes, no"]Social icon[/social]<br>[social link="Link" type="e.g. email, facebook, twitter, pinterest, rss, linkedin, flickr, vimeo, blogger, tumblr, skype, behance, googleplus, youtube, dribble, instagram, picasa, github, stumbleupon, lastfm, etc." title="" open_in_new_window="e.g. yes, no"]Social icon[/social]<br>[social link="Link" type="e.g. email, facebook, twitter, pinterest, rss, linkedin, flickr, vimeo, blogger, tumblr, skype, behance, googleplus, youtube, dribble, instagram, picasa, github, stumbleupon, lastfm, etc." title="" open_in_new_window="e.g. yes, no"]Social icon[/social]<br>[/social_icons]'>Social Icon</a></li>
				<li><a href="#" data-all='[table style="e.g. style1, style2"]<br/>[table_open_row]<br/>[table_title]Table title[/table_title]<br/>[/table_open_row]<br/>[table_open_row]<br/>[table_content]Table content[/table_content]<br/>[/table_open_row]<br/>[table_open_row]<br/>[table_title]Table title[/table_title]<br/>[/table_open_row]<br/>[table_open_row]<br/>[table_content]Table content[/table_content]<br/>[/table_open_row]<br/>[table_open_row]<br/>[table_title]Table title[/table_title]<br/>[/table_open_row]<br/>[table_open_row]<br/>[table_content]Table content[/table_content]<br/>[/table_open_row]<br/>[/table]'>Table</a></li>
				<li><a href="#" data-all='[tabs style="e.g. 1, 2, 3" vertical="e.g. yes, no" title_align="e.g. default, centered, right"]<br>[tab title="Tab one" icon="e.g. fa-heart"]<p>Tab one content</p>[/tab]<br>[tab title="Tab two" icon="e.g. fa-heart"]<p>Tab two content</p>[/tab]<br>[tab title="Tab three" icon="e.g. fa-heart"]<p>Tab three content</p>[/tab]<br>[/tabs]'>Tabbed content</a></li>
				<li><a href="#" data-all='[team_member name="e.g. Michael Brown" position="e.g. CEO / Co-Founder" image_url="put image URL here" image_alt="Image description" divider="e.g. yes, no" link="" boxed="e.g. yes, no" circled_image="e.g. yes, no" centered="e.g. yes, no" social_colored="e.g. yes, no" social_vertical="e.g. yes, no" twitter="https://twitter.com" facebook="https://facebook.com/" email="put e-mail here" linkedin="https://www.linkedin.com/" google="https://google.com/" github="https://github.com/" behance="https://www.behance.net/" xing="https://www.xing.com/" dribble="https://www.dribble.com/"][/team_member]'>Team Member</a></li>	
				<li><a href="#" data-all='[columns]<h4>Our Team</h4>[team_slider]<br>[one_fourth]<br>[team_member name="e.g. Michael Brown" position="e.g. CEO / Co-Founder" image_url="put image URL here" image_alt="Image description" divider="e.g. yes, no" boxed="e.g. yes, no" circled_image="e.g. yes, no" centered="e.g. yes, no" social_colored="e.g. yes, no" social_vertical="e.g. yes, no" twitter="https://twitter.com" facebook="https://facebook.com/" email="put e-mail here" linkedin="https://www.linkedin.com/" google="https://google.com/" github="https://github.com/" behance="https://www.behance.net/" xing="https://www.xing.com/" dribble="https://www.dribble.com/"]Place for text[/team_member]<br>[/one_fourth]<br>[one_fourth]<br>[team_member name="e.g. Michael Brown" position="e.g. CEO / Co-Founder" image_url="put image URL here" image_alt="Image description" divider="e.g. yes, no" boxed="e.g. yes, no" circled_image="e.g. yes, no" centered="e.g. yes, no" social_colored="e.g. yes, no" social_vertical="e.g. yes, no" twitter="https://twitter.com" facebook="https://facebook.com/" email="put e-mail here" linkedin="https://www.linkedin.com/" google="https://google.com/" github="https://github.com/" behance="https://www.behance.net/" xing="https://www.xing.com/" dribble="https://www.dribble.com/"]Place for text[/team_member]<br>[/one_fourth]<br>[one_fourth]<br>[team_member name="e.g. Michael Brown" position="e.g. CEO / Co-Founder" image_url="put image URL here" image_alt="Image description" divider="e.g. yes, no" boxed="e.g. yes, no" circled_image="e.g. yes, no" centered="e.g. yes, no" social_colored="e.g. yes, no" social_vertical="e.g. yes, no" twitter="https://twitter.com" facebook="https://facebook.com/" email="put e-mail here" linkedin="https://www.linkedin.com/" google="https://google.com/" github="https://github.com/" behance="https://www.behance.net/" xing="https://www.xing.com/" dribble="https://www.dribble.com/"]Place for text[/team_member]<br>[/one_fourth]<br>[one_fourth]<br>[team_member name="e.g. Michael Brown" position="e.g. CEO / Co-Founder" image_url="put image URL here" image_alt="Image description" divider="e.g. yes, no" boxed="e.g. yes, no" circled_image="e.g. yes, no" centered="e.g. yes, no" social_colored="e.g. yes, no" social_vertical="e.g. yes, no" twitter="https://twitter.com" facebook="https://facebook.com/" email="put e-mail here" linkedin="https://www.linkedin.com/" google="https://google.com/" github="https://github.com/" behance="https://www.behance.net/" xing="https://www.xing.com/" dribble="https://www.dribble.com/"]Place for text[/team_member]<br>[/one_fourth]<br>[one_fourth]<br>[team_member name="e.g. Michael Brown" position="e.g. CEO / Co-Founder" image_url="put image URL here" image_alt="Image description" divider="e.g. yes, no" boxed="e.g. yes, no" circled_image="e.g. yes, no" centered="e.g. yes, no" social_colored="e.g. yes, no" social_vertical="e.g. yes, no" twitter="https://twitter.com" facebook="https://facebook.com/" email="put e-mail here" linkedin="https://www.linkedin.com/" google="https://google.com/" github="https://github.com/" behance="https://www.behance.net/" xing="https://www.xing.com/" dribble="https://www.dribble.com/"]Place for text[/team_member]<br>[/one_fourth]<br>[/team_slider]<br>[/columns]'>Team Slider</a></li>

			</ul>	
		</li>
		<li class="parent">
			<a href="javascript:;" onclick="return false;">Boxes</a>
			<ul>
				<li><a href="#" data-param='box_type="e.g. confirm, info, alert, warning" alternative_style="e.g. yes, no" hide_text="x"' data-tag='combined_notifications' data-all="" data-text="Message headline (h4 with strong) and content (paragraph)">Combined Notifications</a></li>
				<li><a href="#" data-param='box_type="e.g. normal, confirm, warning, info, alert" title="Title"' data-tag='content_box' data-all="" data-text="Content box">Content box</a></li>
				<li><a href="#" data-param='icon_color="e.g. #b8a7d7" icon_background="e.g. #fafafa" icon="e.g. fa-camera-retro" title="Box title"' data-tag='box' data-all="" data-text="Place for text">Box</a></li>
				<li><a href="#" data-param='icon_background="e.g. #fafafa" icon="e.g. fa-cloud-download" title="Box title"' data-tag='box2' data-all="" data-text="Place for text">Box2</a></li>
				<li><a href="#" data-param='border_position="e.g. top-border, left-border" border_color="e.g. #F0846F" border_width="e.g. 5px"' data-tag='box_default' data-all="" data-text="<h4>Place for title</h4><p>Place for text</p>">Box Default</a></li>
				<li><a href="#" data-param='icon="e.g. fa-rocket" icon_color="e.g. #fff" icon_background="e.g. #70c14a" icon_size="e.g. 2, 3' data-tag='box_icon_left' data-all="" data-text="<h4>Place for title</h4><p>Place for text</p>">Box Icon Left</a></li>
				<li><a href="#" data-param='icon_color="e.g. #fff" icon="e.g. fa-camera-retro" background="e.g. #84C753" title="e.g. Box Colored"' data-tag='box_colored' data-all="" data-text="Place for text">Box Colored</a></li>
				<li><a href="#" data-param='icon="e.g. fa-cloud-download" icon_background="e.g. #57b7d6" border_color="e.g. #57b7d6" title="e.g. Box colored alternative"' data-tag='box_colored_alternative' data-all="" data-text="Place for text">Box Colored Alternative</a></li>
			</ul>	
		</li>
		<li class="parent">
			<a href="javascript:;" onclick="return false;">Testimonials</a>
			<ul>
				<li><a href="#" data-all='[testimonial name="e.g. Michael Brown" org=" / e.g. Company Name" avatar="put image url here" avatar_position="e.g. left, bottom" font_size="e.g. 1, 2, 3, 4" quote="e.g. yes, no"]Testimonial content[/testimonial]'>Testimonial</a></li>
				<li><a href="#" data-all='[testimonial_slider title="Testimonials title" delay="9000" duration="800" auto="1" bottom_paginator="e.g. yes, no"]<br/>[testimonial name="e.g. Michael Brown" org="e.g. Company Name" avatar="put image url here" avatar_position="e.g. left, bottom" font_size="e.g. 1, 2, 3, 4" quote="e.g. yes, no"]Testimonial content[/testimonial]<br/>[testimonial name="e.g. Michael Brown" org="e.g. Company Name" avatar="put image url here" avatar_position="e.g. left, bottom" font_size="e.g. 1, 2, 3, 4" quote="e.g. yes, no"]Testimonial content[/testimonial]<br/>[testimonial name="e.g. Michael Brown" org="e.g. Company Name" avatar="put image url here" avatar_position="e.g. left, bottom" font_size="e.g. 1, 2, 3, 4" quote="e.g. yes, no"]Testimonial content[/testimonial]<br/>[/testimonial_slider]'>Testimonial Slider</a></li>	
			</ul>	
		</li>
		<li class="parent">
			<a href="javascript:;" onclick="return false;">Counters and Bar Graphs</a>
			<ul>
				<li><a href="#" data-all='[animated_circle_chart width="120" height="120" percent="75" color="#70c14a"]e.g. 75%[/animated_circle_chart]'>Animated Circle Chart</a></li>
				<li><a href="#" data-param='value="e.g. 24" speed="e.g. 2400" value_suffix="e.g. %" color="e.g. 9a83c4"' data-tag='animated_milestone' data-all="" data-text="Put starting value here (e.g. 0)">Animated Milestone</a></li>
				<li><a href="#" data-all='[counter style="e.g. 1, 2" month="e.g. 1" year="e.g. 2014" day="e.g. 26" hour="e.g. 19" minute="e.g. 0" translate_days="e.g. days" translate_day="e.g. day" translate_hours="e.g. hours" translate_hour="e.g. hour" translate_minutes="e.g. minutes" translate_minute="e.g. minute" translate_seconds="e.g. seconds" translate_second="e.g. second" location="e.g. GMT"]'>Counters</a></li>	
				<li><a href="#" data-param='percent="100%" size="e.g. default, small"' data-tag='progress' data-all="" data-text="Progress text">Progress bar</a></li>
			</ul>	
		</li>
		<li class="parent">
			<a href="javascript:;" onclick="return false;">Icons</a>
			<ul>
				<li><a href="#" data-all='[icon name="e.g. fa-rocket" size="e.g. 1, 2, 3, 4, 5" color="empty or e.g. #ccc" background_color="empty or e.g. #fff" circled_background="e.g. yes, no" color_on_hover="e.g. #ef8f6a" background_color_on_hover="e.g. #fff"]'>Icon</a></li>
				<li><a href="#" data-all='[icon_colored_on_hover size="e.g. 1, 2, 3, 4, 5" name="e.g. fa-rocket" color="e.g. #ccc" background_color="e.g. #fff" color_on_hover="e.g. #f47d71" background_color_on_hover="e.g. #fff" circled_background="e.g. yes, no"]'>Icon Colored On Hover</a></li>
			</ul>	
		</li>
		<li class="parent">
			<a href="javascript:;" onclick="return false;">Typography</a>
			<ul>
				<li><a href="#" data-param='type="1, 2" position="e.g. default, left, right" author="e.g. Author Name"' data-tag='blockquote' data-all='' data-text="Blockquote">Blockquote</a></li>
				<li><a href="#" data-param='style="e.g. default, circle, square" size="e.g. normal, small"' data-tag='dropcap' data-all='' data-text="e.g. Dropcap">Dropcap</a></li>
				<li><a href="#" data-param='type="e.g. 1, 2" width="e.g. small, medium, wide"' data-tag='divider' data-all='' data-text="">Divider</a></li>
				<li><a href="#" data-param='background_color="empty or e.g. #e3f35e" color="e.g. #666"' data-tag='highlight' data-all="" data-text="Highlighted text">Highlight</a></li>
				<li><a href="#" data-param='' data-tag='image_caption' data-all="" data-text="Place for text">Image Caption</a></li>
				<li><a href="#" data-param='class="e.g. narrow" title="e.g. Title" text="Put content here"' data-tag='lead_block' data-all="" data-text="">Lead Block</a></li>
				<li><a href="#" data-param='size="e.g. default, small"' data-tag='note' data-all="" data-text="Place for text">Note</a></li>
				<li><a href="#" data-all='[quote]<br/>[quote_content]Quote content[/quote_content]<br/>[quote_signature name="Name"]Quote signature[/quote_signature]<br/>[/quote]'>Quote</a></li>
				<li><a href="#" data-param='type="e.g. h1, h2, h3, h4, h5, h6"' data-tag='special_heading' data-all='' data-text="Heading">Special heading</a></li>
				<li><a href="#" data-param='title="Tooltip title"' data-tag='tooltip' data-all="" data-text="Tooltip text">Tooltip</a></li>	
				<li><a href="#" data-param='' data-tag='underline' data-all="" data-text="Underline the text with a dotted line.">Underline</a></li>
				<li><a href="#" data-all='[unordered_list style="e.g. custom, unstyled, font-awesome-list"]<ul><li>Item 1</li><li>Item 2</li><li>Item 3</li></ul>[/unordered_list]'>Unordered list</a></li>	
			</ul>	
		</li>
		<li class="parent">
			<a href="javascript:;" onclick="return false;">Pricing</a>
			<ul>
				<li><a href="#"  data-all='[pricing_plans]<br>[pricing_plan is_selected="no"]<br>[pricing_lead]<h2>Basic</h2>[subtitle]Personal[/subtitle]<br>[/pricing_lead]<hr>[price][strong][currency][/currency]Free[/strong][/price]<ul><li><strong>Unlimited</strong> Users</li><li><strong>5</strong> Projects</li><li><strong>15Gb</strong> Storage</li><li><strong>50Gb</strong> Bandwidth</li><li><strong>1 GB</strong> Storage</li></ul>[button color="dark-gray" size="large" light="no" icon="" open_in_new_window="no" link="#"]Sign up[/button]<br>[/pricing_plan]<br>[pricing_plan is_selected="no"]<br>[pricing_lead]<h2>Startup</h2>[subtitle]Best value[/subtitle]<br>[/pricing_lead]<hr>[price][strong][currency]$[/currency]29[/strong] / mo[/price]<ul><li><strong>Unlimited</strong> Users</li><li><strong>5</strong> Projects</li><li><strong>15Gb</strong> Storage</li><li><strong>50Gb</strong> Bandwidth</li><li><strong>1 GB</strong> Storage</li></ul>[button color="dark-gray" size="large" light="no" icon="" open_in_new_window="no" link="#"]Sign up[/button]<br>[/pricing_plan]<br>[pricing_plan is_selected="yes"]<br>[pricing_lead]<h2>Premium</h2>[subtitle]Business[/subtitle]<br>[/pricing_lead]<hr>[price][strong][currency]$[/currency]99[/strong] / mo[/price]<ul><li><strong>Unlimited</strong> Users</li><li><strong>5</strong> Projects</li><li><strong>15Gb</strong> Storage</li><li><strong>50Gb</strong> Bandwidth</li><li><strong>1 GB</strong> Storage</li></ul>[button color="white" size="large" light="no" icon="" open_in_new_window="no" link="#"]Sign up[/button]<br>[/pricing_plan]<br>[pricing_plan is_selected="no"]<br>[pricing_lead]<h2>Professional</h2>[subtitle]Big Business[/subtitle]<br>[/pricing_lead]<hr>[price][strong][currency]$[/currency]239[/strong] / mo[/price]<ul><li><strong>Unlimited</strong> Users</li><li><strong>5</strong> Projects</li><li><strong>15Gb</strong> Storage</li><li><strong>50Gb</strong> Bandwidth</li><li><strong>1 GB</strong> Storage</li></ul>[button color="dark-gray" size="large" light="no" icon="" open_in_new_window="no" link="#"]Sign up[/button]<br>[/pricing_plan]<br>[/pricing_plans]'>Pricing Plans</a></li>
				<li><a href="#"  data-all='<div class="table"><table class="pricing"><tr><th></th><th><strong>FREE</strong><span>Personal</span></th><th><strong><span class="currency">$</span>149</strong> / mo<span>Team</span></th><th><strong><span class="currency">$</span>219</strong> / mo<span>Corporate</span></th><th><strong><span class="currency">$</span>449</strong> / mo<span>Ultimate</span></th></tr><tr><td>Users</td><td>1</td><td>20</td><td>100</td><td>Unlimited</td></tr><tr><td>Storage</td><td>10GB</td><td>100GB</td><td>500GB</td><td>2000GB</td></tr><tr><td>Emails</td><td>5</td><td>10</td><td>500</td><td>Unlimited</td></tr><tr><td>Bandwidth</td><td>2 GB</td><td>Unlimited</td><td>Unlimited</td><td>Unlimited</td></tr><tr><td>Databases</td><td>1</td><td>20</td><td>Unlimited</td><td>Unlimited</td></tr><tr><td>Customizable templates</td><td>[icon name="fa-times" size="4" color="#ed5946"]</i></td><td>[icon name="fa-times" size="4" color="#ed5946"]</i></td><td>[icon name="fa-check" size="4" color="#70c14a"]</i></td><td>[icon name="fa-check" size="4" color="#70c14a"]</i></td></tr><tr><td>Sync and Integration</td><td>[icon name="fa-times" size="4" color="#ed5946"]</i></td><td>[icon name="fa-check" size="4" color="#70c14a"]</i></td><td>[icon name="fa-check" size="4" color="#70c14a"]</i></td><td>[icon name="fa-check" size="4" color="#70c14a"]</i></td></tr><tr><td>Free 30-Day Trial</td><td>[icon name="fa-check" size="4" color="#70c14a"]</i></td><td>[icon name="fa-check" size="4" color="#70c14a"]</i></td><td>[icon name="fa-check" size="4" color="#70c14a"]</i></td><td>[icon name="fa-check" size="4" color="#70c14a"]</i></td></tr><tr class="action"><td><a href="#">Inquire us for pricing</a></td><td><a href="#" class="button">Select</a></td><td><a href="#" class="button">Select</a></td><td><a href="#" class="button">Select</a></td><td><a href="#" class="button">Select</a></td></tr></table></div>'>Pricing Table</a></li>
			</ul>	
		</li>
		<li class="boder"><a href="#" data-all='' data-tag='heading' data-param='size="e.g. 1, 2, 3, 4, 5, 6" color="e.g. #000"' data-text="Default Heading">Heading</a></li>	
	</ul>
</div>
</div>

<?php 
}
add_action('admin_head', 'add_shortcode_menu');

?>