<?php
function converio_extend_contact_info( $contact_info ) {
  //add Facebook
  $contact_info['facebook'] = 'Facebook';
  //Add Twitter
  $contact_info['twitter'] = 'Twitter';
  //add Google+
  $contact_info['googleplus'] = 'Google+';	
  //add Instagram
  $contact_info['instagram'] = 'Instagram';
  //add RSS
  $contact_info['rss'] = 'RSS';
  return $contact_info;
}
add_filter('user_contactmethods','converio_extend_contact_info',10,1);
?>