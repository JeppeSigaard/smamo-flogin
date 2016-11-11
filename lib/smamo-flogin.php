<?php
add_action('init', 'smamo_flogin');
function smamo_flogin($fb_login_button = false, $button_text = false, $redirect_uri = false){

    /**-----------------------------------------------**/
    // Login / Attach / Create user
    /**-----------------------------------------------**/

    if (isset($_GET['code']) && !$fb_login_button) {

        $fb = new Facebook(array(
          'appId'  => get_theme_mod('smamo_flogin_app_id'),
          'secret' => get_theme_mod('smamo_flogin_app_secret'),
        ));

        $fb_usr = $fb->getUser();
        if($fb_usr){
            $fb_profile = $fb->api('/me?fields=id,first_name,last_name,email');

            $fb_id = $fb_profile['id'];

            $user_query = get_users(array(
                'meta_key' => 'facebook_id',
                'meta_value' => $fb_id,
            ));

            // Tilknyt facebook til eksisterende bruger
            if(is_user_logged_in()){
                $u = wp_get_current_user();
                $has_id = get_user_meta($u->ID,'facebook_id',true);
                if('' === $has_id){
                    add_user_meta($u->ID,'facebook_id', $fb_id, true);
                }
            }

            // Log ind med facebook
            if(!is_user_logged_in() && !empty($user_query)){
                $current_user = $user_query[0];

                $user = get_user_by( 'id', $current_user->ID );
                if( $user ) {
                    wp_set_current_user( $user->ID, $user->user_login );
                    wp_set_auth_cookie( $user->ID );
                    do_action( 'wp_login', $user->user_login );
                }

            }

            // Opret bruger med facebook
            if(!is_user_logged_in() && empty($user_query)){


                $pswd = wp_generate_password(32, true, true);

                $login = $fb_profile['first_name'] . ' ' . $fb_profile['last_name'];

                $i = '';
                $found = false;

                while (!$found) {
                    $user = get_user_by('name', $login . $i);
                    if(!$user) {
                        $found = true;
                        $login .= $i;
                    }

                    if($i === ''){$i = 1;}
                    else{$i++;}
                }

                $new_user = wp_insert_user( array(
                    'first_name' => $fb_profile['first_name'],
                    'last_name' => $fb_profile['last_name'],
                    'user_login' => $login,
                    'user_email' => $fb_profile['email'],
                    'user_nicename' => $fb_profile['first_name'] . ' ' . $fb_profile['last_name'],
                    'user_pass' => $pswd,
                ));

                if(!is_wp_error($new_user)){
                    add_user_meta($new_user, 'facebook_id', $fb_profile['id'], true);

                    $user = get_user_by( 'id', $new_user );
                    if( $user ) {
                        wp_set_current_user( $user_id, $user->user_login );
                        wp_set_auth_cookie( $user_id );
                        do_action( 'wp_login', $user->user_login );
                    }
                }
            }
        }
    }

    /**-----------------------------------------------**/
    // Login / Attach / Create user button
    /**-----------------------------------------------**/

    if ($fb_login_button == true) {

        if(!is_user_logged_in() || '' === get_user_meta(get_current_user_id(), 'facebook_id', true)){

            $fb = new Facebook(array(
              'appId'  => get_theme_mod('smamo_flogin_app_id'),
              'secret' => get_theme_mod('smamo_flogin_app_secret'),
            ));

            // Redirect
            if(!$redirect_uri){
                $redirect_uri = get_theme_mod('smamo_flogin_redirect_uri');
            }

            // Standard button text
            if(!$button_text){$button_text = __('Log ind med facebook', 'smamo_flogin');}


            $login_url = $fb->getLoginUrl(array('redirect_uri' => $redirect_uri, 'scope' => 'email'));

            $output = '<a class="smamo-flogin-btn" href='.$login_url.'>' . $button_text . '</a>';
            echo apply_filters('smamo_flogin', $output);
        }
    }
}
