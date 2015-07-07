<?php
// shortcode for Counters
add_shortcode('counter', 'converio_counters');

function converio_counters($attr){
	if(isset($attr) && !empty($attr))
		array_walk($attr, 'converio_arrangement_shortcode_arr_value');

    extract(shortcode_atts(array(  
        'style' => '',
		'month' => '',
		'year' => '',
		'day' => '',
		'hour' => '',
		'minute' => '',
		'translate_days' => '',
		'translate_day' => '',
		'translate_hours' => '',
		'translate_hour' => '',
		'translate_minutes' => '',
		'translate_minute' => '',
		'translate_seconds' => '',
		'translate_second' => '',
		'location' => ''
    ), $attr));  


	$output_class = "";
	if ($style == 2) {
		$output_class = 'counter counter-2';
	} else {
		$output_class = 'counter'; // default style 1
	}

	date_default_timezone_set($location); 
	$output_timestamp = mktime((int)$hour, (int)$minute, 0, (int)$month, (int)$day, (int)$year);
	$output = '<ul class="'.$output_class.'" data-time="'.$output_timestamp.'" data-tdays="'.$translate_days.'" data-tday="'.$translate_day.'" data-thours="'.$translate_hours.'" data-thour="'.$translate_hour.'" data-tminutes="'.$translate_minutes.'" data-tminute="'.$translate_minute.'" data-tseconds="'.$translate_seconds.'" data-tsecond="'.$translate_second.'">';
	$output .= '<li class="days"><span class="num">0</span>'.(!empty($attr["translate_days"]) ? '<span class="text">'.$translate_days.'</span>' : '').'</li>';
	$output .= '<li class="hours"><span class="num">0</span>'.(!empty($attr["translate_hours"]) ? '<span class="text">'.$translate_hours.'</span>' : '').'</li>';
	$output .= '<li class="minutes"><span class="num">0</span>'.(!empty($attr["translate_minutes"]) ? '<span class="text">'.$translate_minutes.'</span>' : '').'</li>';
	$output .= '<li class="seconds"><span class="num">0</span>'.(!empty($attr["translate_seconds"]) ? '<span class="text">'.$translate_seconds.'</span>' : '').'</li></ul>';

	return $output;
}
