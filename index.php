<?php
/*
  Plugin Name: SmartMonkey's Flogin
  Plugin URI:
  Description: Opret login med Facebook. Felter til App ID, App Secret og Redirect URI dannes med <a href="https://aristath.github.io/kirki/">Kirki</a>
  Version: 1.0
  Author: SmartMonkey
  Author URI: http://smartmonkey.dk
*/


session_start();
$libs = plugin_dir_path(__FILE__) . 'lib/';
include_once($libs . "facebook.php");
include_once($libs . "kirki.php");
include_once($libs . "smamo-flogin-logout.php");
include_once($libs . "smamo-flogin.php");
include_once($libs . "action.php");




