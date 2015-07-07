<?php

class iworks_events_widget_calendar extends WP_Widget
{

    /**
     * Sets up the widgets name etc
     */
    public function __construct()
    {
        parent::__construct(
            __CLASS__, // Base ID
            __('Events Calendar', 'iworks_events'), // Name
            array( 'description' => __( 'Use this widget to see upcoming events as calendar.', 'iworks_events' ), ) // Args
        );
        add_action( 'save_post', array($this, 'delete_get_calendar_cache') );
        add_action( 'delete_post', array($this, 'delete_get_calendar_cache') );
        add_action( 'update_option_start_of_week', array($this, 'delete_get_calendar_cache') );
        add_action( 'update_option_gmt_offset', array($this, 'delete_get_calendar_cache') );
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {

        /** This filter is documented in wp-includes/default-widgets.php */
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

        echo preg_replace( '/class="/', 'class="widget_calendar ', $args['before_widget'] );
        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        echo '<div id="calendar_wrap">';
        $this->get_calendar();
        echo '</div>';
        echo $args['after_widget'];
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form($instance)
    {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
        $title = strip_tags($instance['title']);
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
    }

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     */
    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    /**
     * Get number of days since the start of the week.
     *
     * @since 1.5.0
     *
     * @param int $num Number of day.
     * @return int Days since the start of the week.
     */
    function calendar_week_mod($num)
    {
        $base = 7;
        return ($num - $base*floor($num/$base));
    }

    /**
     * Display calendar with days that have posts as links.
     *
     * The calendar is cached, which will be retrieved, if it exists. If there are
     * no posts for the month, then it will not be displayed.
     *
     * @since 1.0.0
     * @uses calendar_week_mod()
     *
     * @param bool $initial Optional, default is true. Use initial calendar names.
     * @param bool $echo Optional, default is true. Set to false for return.
     * @return string|null String when retrieving, null when displaying.
     */
    function get_calendar($initial = true, $echo = true)
    {
        global $wpdb, $wp_locale, $iworks_events;

        $year= get_query_var($iworks_events->get_post_meta_name('year'));
        $monthnum = get_query_var($iworks_events->get_post_meta_name('monthnum'));

        $key = md5( $monthnum . $year );
        if ( $cache = wp_cache_get( 'iworks_events_get_calendar', 'calendar' ) ) {
            if ( is_array($cache) && isset( $cache[ $key ] ) ) {
                if ( $echo ) {
                    /** This filter is documented in wp-includes/general-template.php */
                    echo apply_filters( 'iworks_events_get_calendar', $cache[$key] );
                    return;
                } else {
                    /** This filter is documented in wp-includes/general-template.php */
                    return apply_filters( 'iworks_events_get_calendar', $cache[$key] );
                }
            }
        }

        if ( !is_array($cache) ) {
            $cache = array();
        }

        $meta_key = $iworks_events->get_post_meta_name('timestamp');
        $post_type = $iworks_events->get_post_type();

        // Quick check. If we have no posts at all, abort!
        $sql = "SELECT 1 as test 
            FROM $wpdb->posts p
            LEFT JOIN $wpdb->postmeta m ON p.ID = m.post_id AND m.meta_key = '".$meta_key."'
            WHERE p.post_type = '".$post_type."' AND p.post_status = 'publish'
            AND m.meta_key = '".$meta_key."' AND m.meta_value > ".time()."
            LIMIT 1";

        $gotsome = $wpdb->get_var($sql);
        if ( !$gotsome ) {
            $cache[ $key ] = '';
            wp_cache_set( 'iworks_events_get_calendar', $cache, 'calendar' );
            return;
        }

        if ( isset($_GET['w']) ) {
            $w = ''.intval($_GET['w']);
        }

        // week_begins = 0 stands for Sunday
        $week_begins = intval(get_option('start_of_week'));

        // Let's figure out when we are
        if ( !empty($monthnum) && !empty($year) ) {
            $thismonth = ''.zeroise(intval($monthnum), 2);
            $thisyear = ''.intval($year);
        } elseif ( !empty($w) ) {
            // We need to get the month from MySQL
            $thisyear = ''.intval(substr($m, 0, 4));
            $d = (($w - 1) * 7) + 6; //it seems MySQL's weeks disagree with PHP's
            $thismonth = $wpdb->get_var("SELECT DATE_FORMAT((DATE_ADD('{$thisyear}0101', INTERVAL $d DAY) ), '%m')");
        } elseif ( !empty($m) ) {
            $thisyear = ''.intval(substr($m, 0, 4));
            if ( strlen($m) < 6 )
                $thismonth = '01';
            else
                $thismonth = ''.zeroise(intval(substr($m, 4, 2)), 2);
        } else {
            $thisyear = gmdate('Y', current_time('timestamp'));
            $thismonth = gmdate('m', current_time('timestamp'));
        }

        $unixmonth = mktime(0, 0 , 0, $thismonth, 1, $thisyear);
        $last_day = date('t', $unixmonth);

        // Get the next and previous month and year with at least one post
        $previous = $wpdb->get_row("SELECT MONTH(FROM_UNIXTIME(m.meta_value)) AS month, YEAR(FROM_UNIXTIME(m.meta_value)) AS year
            FROM $wpdb->posts p
            LEFT JOIN $wpdb->postmeta m ON p.ID = m.post_id AND m.meta_key = '".$meta_key."'
            WHERE FROM_UNIXTIME(m.meta_value) < '$thisyear-$thismonth-01'
            AND p.post_type = '".$post_type."' AND post_status = 'publish'
            AND m.meta_key = '".$meta_key."' AND m.meta_value > ".time()."
            ORDER BY m.meta_value DESC
            LIMIT 1");

        $next = $wpdb->get_row("SELECT MONTH(FROM_UNIXTIME(m.meta_value)) AS month, YEAR(FROM_UNIXTIME(m.meta_value)) AS year
            FROM $wpdb->posts p
            LEFT JOIN $wpdb->postmeta m ON p.ID = m.post_id AND m.meta_key = '".$meta_key."'
            WHERE FROM_UNIXTIME(m.meta_value) > '$thisyear-$thismonth-{$last_day} 23:59:59'
            AND p.post_type = '".$post_type."' AND post_status = 'publish'
            AND m.meta_key = '".$meta_key."' AND m.meta_value > ".time()."
            ORDER BY m.meta_value ASC
            LIMIT 1");

        /* translators: Calendar caption: 1: month name, 2: 4-digit year */
        $calendar_caption = _x('%1$s %2$s', 'calendar caption');
        $calendar_output = '<table id="wp-calendar">
            <caption>' . sprintf($calendar_caption, $wp_locale->get_month($thismonth), date('Y', $unixmonth)) . '</caption>
            <thead>
            <tr>';

        $myweek = array();

        for ( $wdcount=0; $wdcount<=6; $wdcount++ ) {
            $myweek[] = $wp_locale->get_weekday(($wdcount+$week_begins)%7);
        }

        foreach ( $myweek as $wd ) {
            $day_name = (true == $initial) ? $wp_locale->get_weekday_initial($wd) : $wp_locale->get_weekday_abbrev($wd);
            $wd = esc_attr($wd);
            $calendar_output .= "\n\t\t<th scope=\"col\" title=\"$wd\">$day_name</th>";
        }

        $calendar_output .= '
            </tr>
            </thead>

            <tfoot>
            <tr>';

        if ( $previous ) {
            $calendar_output .= "\n\t\t".'<td colspan="3" id="prev"><a href="' . $this->get_month_link($previous->year, $previous->month) . '">&laquo; ' . $wp_locale->get_month_abbrev($wp_locale->get_month($previous->month)) . '</a></td>';
        } else {
            $calendar_output .= "\n\t\t".'<td colspan="3" id="prev" class="pad">&nbsp;</td>';
        }

        $calendar_output .= "\n\t\t".'<td class="pad">&nbsp;</td>';

        if ( $next ) {
            $calendar_output .= "\n\t\t".'<td colspan="3" id="next"><a href="' . $this->get_month_link($next->year, $next->month) . '">' . $wp_locale->get_month_abbrev($wp_locale->get_month($next->month)) . ' &raquo;</a></td>';
        } else {
            $calendar_output .= "\n\t\t".'<td colspan="3" id="next" class="pad">&nbsp;</td>';
        }

        $calendar_output .= '
            </tr>
            </tfoot>

            <tbody>
            <tr>';

        $sql = 
            "SELECT DISTINCT DAYOFMONTH(FROM_UNIXTIME(m.meta_value))
            FROM $wpdb->posts p
            LEFT JOIN $wpdb->postmeta m ON p.ID = m.post_id AND m.meta_key = '".$meta_key."'
            WHERE FROM_UNIXTIME(m.meta_value) >= '{$thisyear}-{$thismonth}-01 00:00:00'
            AND p.post_type = '{$post_type}' AND post_status = 'publish'
            AND FROM_UNIXTIME(m.meta_value) <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59'"
            ;

        // Get days with posts
        $dayswithposts = $wpdb->get_results($sql, ARRAY_N );

        if ( $dayswithposts ) {
            foreach ( (array) $dayswithposts as $daywith ) {
                $daywithpost[] = $daywith[0];
            }
        } else {
            $daywithpost = array();
        }

        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'camino') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'safari') !== false)
            $ak_title_separator = "\n";
        else
            $ak_title_separator = ', ';

        $ak_titles_for_day = array();
        $sql = 
            "SELECT p.ID, p.post_title, DAYOFMONTH(FROM_UNIXTIME(m.meta_value)) as dom
            FROM $wpdb->posts p
            LEFT JOIN $wpdb->postmeta m ON p.ID = m.post_id AND m.meta_key = '".$meta_key."'
            WHERE FROM_UNIXTIME(m.meta_value) >= '{$thisyear}-{$thismonth}-01 00:00:00'
            AND p.post_type = '{$post_type}' AND post_status = 'publish'
            AND FROM_UNIXTIME(m.meta_value) <= '{$thisyear}-{$thismonth}-{$last_day} 23:59:59'"
            ;
        $ak_post_titles = $wpdb->get_results($sql);
        if ( $ak_post_titles ) {
            foreach ( (array) $ak_post_titles as $ak_post_title ) {

                /** This filter is documented in wp-includes/post-template.php */
                $post_title = esc_attr( apply_filters( 'the_title', $ak_post_title->post_title, $ak_post_title->ID ) );

                if ( empty($ak_titles_for_day['day_'.$ak_post_title->dom]) )
                    $ak_titles_for_day['day_'.$ak_post_title->dom] = '';
                if ( empty($ak_titles_for_day["$ak_post_title->dom"]) ) // first one
                    $ak_titles_for_day["$ak_post_title->dom"] = $post_title;
                else
                    $ak_titles_for_day["$ak_post_title->dom"] .= $ak_title_separator . $post_title;
            }
        }

        // See how much we should pad in the beginning
        $pad = calendar_week_mod(date('w', $unixmonth)-$week_begins);
        if ( 0 != $pad )
            $calendar_output .= "\n\t\t".'<td colspan="'. esc_attr($pad) .'" class="pad">&nbsp;</td>';

        $daysinmonth = intval(date('t', $unixmonth));
        for ( $day = 1; $day <= $daysinmonth; ++$day ) {
            if ( isset($newrow) && $newrow )
                $calendar_output .= "\n\t</tr>\n\t<tr>\n\t\t";
            $newrow = false;

            if ( $day == gmdate('j', current_time('timestamp')) && $thismonth == gmdate('m', current_time('timestamp')) && $thisyear == gmdate('Y', current_time('timestamp')) )
                $calendar_output .= '<td id="today">';
            else
                $calendar_output .= '<td>';

            if ( in_array($day, $daywithpost) ) // any posts today?
                $calendar_output .= '<a href="' . $this->get_day_link( $thisyear, $thismonth, $day ) . '" title="' . esc_attr( $ak_titles_for_day[ $day ] ) . "\">$day</a>";
            else
                $calendar_output .= $day;
            $calendar_output .= '</td>';

            if ( 6 == calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins) )
                $newrow = true;
        }

        $pad = 7 - calendar_week_mod(date('w', mktime(0, 0 , 0, $thismonth, $day, $thisyear))-$week_begins);
        if ( $pad != 0 && $pad != 7 )
            $calendar_output .= "\n\t\t".'<td class="pad" colspan="'. esc_attr($pad) .'">&nbsp;</td>';

        $calendar_output .= "\n\t</tr>\n\t</tbody>\n\t</table>";

        $cache[ $key ] = $calendar_output;
        //wp_cache_set( 'iworks_events_get_calendar', $cache, 'calendar' );

        if ( $echo ) {
            /**
             * Filter the HTML calendar output.
             *
             * @since 3.0.0
             *
             * @param string $calendar_output HTML output of the calendar.
             */
            echo apply_filters( 'iworks_events_get_calendar', $calendar_output );
        } else {
            /** This filter is documented in wp-includes/general-template.php */
            return apply_filters( 'iworks_events_get_calendar', $calendar_output );
        }

    }

    /**
     * Purge the cached results of get_calendar.
     *
     * @see get_calendar
     * @since 2.1.0
     */
    function delete_get_calendar_cache()
    {
        wp_cache_delete( 'iworks_events_get_calendar', 'calendar' );
    }

    /**
     * Retrieve the permalink for the month archives with year.
     *
     * @since 1.0.0
     *
     * @param bool|int $year False for current year. Integer of year.
     * @param bool|int $month False for current month. Integer of month.
     * @return string
     */
    private function get_month_link($year, $month) {
        global $wp_rewrite, $iworks_events;
        if ( !$year )
            $year = gmdate('Y', current_time('timestamp'));
        if ( !$month )
            $month = gmdate('m', current_time('timestamp'));
        $monthlink = $wp_rewrite->get_month_permastruct();

        if ( !empty($monthlink) ) {
            $monthlink = str_replace('%year%', $iworks_events->get_permalink().'/'.$year, $monthlink);
            $monthlink = str_replace('%monthnum%', zeroise(intval($month), 2), $monthlink);
            $monthlink = home_url( user_trailingslashit( $monthlink, 'month' ) );
        } else {
            $monthlink = add_query_arg(
                array(
                    'post_type' => $iworks_events->get_post_type(),
                    'year' => $year,
                    'monthnum' => zeroise($month,2),
                ),
                home_url()
            );
        }

        /**
         * Filter the month archive permalink.
         *
         * @since 1.5.0
         *
         * @param string $monthlink Permalink for the month archive.
         * @param int    $year      Year for the archive.
         * @param int    $month     The month for the archive.
         */
        return apply_filters( 'month_link', $monthlink, $year, $month );
    }

    /**
     * Retrieve the permalink for the day archives with year and month.
     *
     * @since 1.0.0
     *
     * @param bool|int $year False for current year. Integer of year.
     * @param bool|int $month False for current month. Integer of month.
     * @param bool|int $day False for current day. Integer of day.
     * @return string
     */
    private function get_day_link($year, $month, $day) {
        global $wp_rewrite, $iworks_events;
        if ( !$year )
            $year = gmdate('Y', current_time('timestamp'));
        if ( !$month )
            $month = gmdate('m', current_time('timestamp'));
        if ( !$day )
            $day = gmdate('j', current_time('timestamp'));

        $daylink = $wp_rewrite->get_day_permastruct();
        if ( !empty($daylink) ) {
            $daylink = str_replace('%year%', $iworks_events->get_permalink().'/'.$year, $daylink);
            $daylink = str_replace('%monthnum%', zeroise(intval($month), 2), $daylink);
            $daylink = str_replace('%day%', zeroise(intval($day), 2), $daylink);
            $daylink = home_url( user_trailingslashit( $daylink, 'day' ) );
        } else {
            $daylink = add_query_arg(
                array(
                    'post_type' => $iworks_events->get_post_type(),
                    'year' => $year,
                    'day' => zeroise($day,2),
                    'monthnum' => zeroise($month,2),
                ),
                home_url()
            );
        }

        /**
         * Filter the day archive permalink.
         *
         * @since 1.5.0
         *
         * @param string $daylink Permalink for the day archive.
         * @param int    $year    Year for the archive.
         * @param int    $month   Month for the archive.
         * @param int    $day     The day for the archive.
         */
        return apply_filters( 'day_link', $daylink, $year, $month, $day );
    }
}
