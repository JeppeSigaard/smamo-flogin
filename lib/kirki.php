<?php

if (!class_exists('Kirki')){

    if (!get_theme_mod('smamo_flogin_app_id')){
        add_action('admin_notices',function(){

            $class = 'notice notice-warning';
            $message = __( 'For at bruge SmartMonkey\'s Flogin plugin, installér <a href="https://aristath.github.io/kirki/">Kirki</a>. Kirki giver dig de nødvendige felter i wp-admin. <br> Du kan også indstille "smamo_flogin_app_id", "smamo_flogin_app_secret" og "smamo_flogin_redirect_uri" manuelt, men det anbefales ikke til et kundeprojekt.<br><br> Hey, gør hvad du vil, split systemet ad og byg dit eget, jeg har bare mine præferencer. <br>KH Jeppe <span class="dashicons dashicons-heart"></span>', 'smamo-flogin' );

           printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
        });
    }
}

else{

    Kirki::add_config( 'smamo_flogin', array(
        'capability'    => 'edit_theme_options',
        'option_type'   => 'theme_mod',
    ) );

    Kirki::add_section( 'smamo_flogin', array(
        'title'          => __( 'Flogin indstillinger' ),
        'description'    => __( 'Indstil App id, secret og redirect uri til Flogin' ),
        'panel'          => '', // Not typically needed.
        'priority'       => 160,
        'capability'     => 'edit_theme_options',
        'theme_supports' => '', // Rarely needed.
    ) );

    Kirki::add_field( 'smamo_flogin', array(
        'settings' => 'smamo_flogin_app_id',
        'label'    => __( 'APP ID', 'smamo' ),
        'section'  => 'smamo_flogin',
        'type'     => 'text',
        'priority' => 10,
        'default'  => '',
    ) );

    Kirki::add_field( 'smamo_flogin', array(
        'settings' => 'smamo_flogin_app_secret',
        'label'    => __( 'APP Secret', 'smamo' ),
        'section'  => 'smamo_flogin',
        'type'     => 'text',
        'priority' => 10,
        'default'  => '',
    ) );

    Kirki::add_field( 'smamo_flogin', array(
        'settings' => 'smamo_flogin_redirect_uri',
        'label'    => __( 'Redirect URI', 'smamo' ),
        'section'  => 'smamo_flogin',
        'type'     => 'text',
        'priority' => 10,
        'default'  => '',
    ) );

}
