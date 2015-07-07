<?php

require_once($plugin_dir_path.'/libs/add_button.php');

function converio_shortcode_empty_paragraph_fix($content){   
    $array = array (
        '<p>[' => '[', 
        ']</p>' => ']', 
        ']<br />' => ']'
    );

    $content = strtr($content, $array);
    return $content;
}

add_filter('the_content', 'converio_shortcode_empty_paragraph_fix');

//Remove e.g. from values
function converio_arrangement_shortcode_value($value) {
	return preg_replace('/^e.g.\s*/', '', $value);
}

function converio_arrangement_shortcode_arr_value(&$value) {
	$value = preg_replace('/^e.g.\s*/', '', $value);
}

include('shortcodes/lead_block.php');
include('shortcodes/counter.php');
include('shortcodes/animated_milestone.php');
include('shortcodes/testimonial.php');
include('shortcodes/team_member.php');
include('shortcodes/columns.php');
include('shortcodes/promobox.php');
include('shortcodes/divider.php');
include('shortcodes/fullwidth.php');
include('shortcodes/box.php');
include('shortcodes/image_slider.php');
include('shortcodes/animated_circle_chart.php');
include('shortcodes/dropcap.php');
include('shortcodes/progress.php');
include('shortcodes/icon.php');
include('shortcodes/icon_colored_on_hover.php');
include('shortcodes/button.php');
include('shortcodes/blockquote.php');
include('shortcodes/tooltip.php');
include('shortcodes/combined_notifications.php');
include('shortcodes/tabs.php');
include('shortcodes/lead.php');
include('shortcodes/highlight.php');
include('shortcodes/checklist.php');
include('shortcodes/social_icons.php');
include('shortcodes/accordions.php');
include('shortcodes/table.php');
include('shortcodes/content_box.php');
include('shortcodes/messages.php');
include('shortcodes/quote.php');
include('shortcodes/toggles.php');
include('shortcodes/note.php');
include('shortcodes/unordered_list.php');
include('shortcodes/image_caption.php');
include('shortcodes/show_hide.php');
include('shortcodes/pricing_plans.php');
include('shortcodes/clients.php');
include('shortcodes/slider.php');
include('shortcodes/heading.php');
include('shortcodes/recent_works.php');
include('shortcodes/pricing_table.php');
include('shortcodes/underline.php');
include('shortcodes/gap.php');
include('shortcodes/img.php');
include('shortcodes/project_team_member_shortcode.php');
include('shortcodes/recent_posts.php');
