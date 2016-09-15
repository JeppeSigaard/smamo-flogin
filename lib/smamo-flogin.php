<?php

add_action('init', 'smamo_flogin');
function smamo_flogin($fb_login_button = false, $button_text = false){

    // Standard button text
    if(!$button_text){$button_text = __('Log ind med facebook', 'smamo_flogin');}

    //Call Facebook API
    $fb = new Facebook(array(
      'appId'  => get_theme_mod('smamo_flogin_app_id'),
      'secret' => get_theme_mod('smamo_flogin_app_secret'),
    ));

    $fb_usr = $fb->getUser();
    if($fb_usr){
        $user_profile = $fb->api('/me?fields=id,first_name,last_name,email,gender,locale,picture');

        if (isset($_GET['code']) && !$fb_login_button) {

            $users = get_user_by( 'email', $user_profile['email'] );
            $usr_id = $users->ID;
            $usr_name = $user_profile['first_name'];
            $usr_fname = sanitize_user( $user_profile['first_name'] );
            $usr_lname = sanitize_user( $user_profile['last_name'] );
            $usr_name = sanitize_user( $usr_name );
            $usr_name = str_replace(array(" ","."), "", $usr_name);
            $usr_name_check = username_exists( $usr_name );

            if($usr_id){

                    $users = get_user_by( 'id', $usr_id );
                    $users = get_user_by('login',$users->user_login);

                    update_user_meta( $usr_id, 'facebook_profile_id', $user_profile['id'] );
                    update_user_meta( $usr_id, 'facebook_profile_img', $user_profile['picture']['data']['url'] );
                    update_user_meta( $usr_id, 'smamo_display_name', $usr_fname . ' ' . $usr_lname);
                    update_user_meta( $usr_id, 'first_name', $usr_fname );
                    update_user_meta( $usr_id, 'last_name', $usr_lname );
            }

            else{

                if ( $usr_name_check ) {

                    $usr_name = $usr_fname;
                    $usr_name = sanitize_user( $usr_name );
                    $usr_name = str_replace(array(" ","."),"",$usr_name);
                    $usr_name_check = username_exists( $usr_name );
                    $usr_name = $usr_lname;
                    $usr_name = sanitize_user( $usr_name );
                    $usr_name = str_replace(array(" ","."),"",$usr_name);
                    $usr_name_check = username_exists( $usr_name );
                    $usr_name = $usr_fname;
                    $usr_name = sanitize_user( $usr_name );
                    $usr_name = str_replace(array(" ","."),"",$usr_name);
                    $usr_name = $usr_name . rand(100, 999);
                    $usr_name_check = username_exists( $usr_name );
                }

                if ( !$usr_name_check and email_exists($user_profile['email']) == false ) {

                    $random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
                    $usr_id = wp_create_user( $usr_name, $random_password, $user_profile['email'] );
                    $users = get_user_by( 'id', $usr_id );

                    update_user_meta( $usr_id, 'facebook_profile_id', $user_profile['id'] );
                    update_user_meta( $usr_id, 'facebook_profile_img', $user_profile['picture']['data']['url'] );
                    update_user_meta( $usr_id, 'smamo_display_name', $usr_fname . ' ' . $usr_lname);
                    update_user_meta( $usr_id, 'first_name', $usr_fname );
                    update_user_meta( $usr_id, 'last_name', $usr_lname );
                }
            }
            //login user and redirect
            wp_set_current_user( $usr_id, $users->user_login );
            wp_set_auth_cookie( $usr_id, false, is_ssl() );
            wp_safe_redirect( site_url() );
        }
    }

    //display Facebook Login button
    if ($fb_login_button == true && !$fb_usr) {

        $fb_usr = null;
        $login_url = $fb->getLoginUrl(array('redirect_uri' => get_theme_mod('smamo_flogin_redirect_uri'), 'scope' => 'email'));
        $output = '<a class="smamo-flogin-btn" href='.$login_url.'>' . $button_text . '</a>';
        echo $output;
    }
}
