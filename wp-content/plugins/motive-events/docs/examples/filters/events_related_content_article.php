<?php

add_filter( 'events_related_content_article', 'my_events_related_content_article', 10, 2 );

function my_events_related_content_article( $article, $post )
{
    $content = '<h4>';
    $content .= get_the_title($post->ID);
    $content .= '</h4>';
    return $content;
}
