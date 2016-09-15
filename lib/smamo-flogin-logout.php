<?php

add_action('wp_logout','smamo_flogin_logout');
function smamo_flogin_logout(){
	$fb = new Facebook(array(
	  'appId'  => get_theme_mod('smamo_flogin_app_id'),
	  'secret' => get_theme_mod('smamo_flogin_app_secret'),
	));

    $fb->destroySession();
	wp_redirect( home_url() );
}
