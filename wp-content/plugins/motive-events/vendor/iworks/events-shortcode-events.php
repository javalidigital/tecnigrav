<?php

if ( !defined( 'WPINC' ) ) {
    die;
}

class IworksEventsShortcodeEvents
{
    private $events;

    public function __construct($events)
    {
        $this->events = $events;
        add_shortcode( 'iworks_events', array(&$this, 'shortcode') );
    }

    public function shortcode($atts)
    {
        return $this->events->get_events();
    }

}
