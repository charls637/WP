<?php
/*
Plugin Name: SMS Text Plugin
Plugin URI: #
Description: SMS Text
Version: The Plugin's Version Number v1.0
Author: Charley Birondo
Author URI: https://github.com/charls637
License: GPL2
*/

defined('ABSPATH') or die('No script kiddies please!');
function sms_activation_hook(){
	// Activation code here
	// Create database tables or entries etc
}
//Register activation hook
register_activation_hook(__FILE__, 'sms_activation_hook');

function sms_deactivation_hook(){
	// Deactivation code here
	//  Remove databsae entries or other settings if necessary 
}
//Register activation hook
register_deactivation_hook(__FILE__, 'sms_deactivation_hook');

function prefix_add_my_stylesheet() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'wp_sms_text_css', plugins_url('style.css', __FILE__), array(), null, 'all' );    
    wp_enqueue_style( 'wp_sms_text_css' );
}
add_action( 'wp_enqueue_scripts', 'prefix_add_my_stylesheet' );

function prefix_add_my_js() {
    // Respects SSL, JS is relative to the current file
	
	wp_enqueue_script('wp_sms_text_js_2', 'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js');
	wp_enqueue_script('wp_sms_text_js_2');
	
    wp_enqueue_script( 'wp_sms_text_js', plugins_url('js/index.js', __FILE__));    
    wp_enqueue_script( 'wp_sms_text_js' );
	
}

add_action( 'wp_enqueue_scripts', 'prefix_add_my_js' );

function admin_my_css() {
	wp_register_style( 'wp_sms_text_css_2', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', array(), null, 'all' );
	wp_enqueue_style( 'wp_sms_text_css_2' );
}
//add_action( 'admin_enqueue_scripts', 'admin_my_css' );

function admin_my_js() {
	wp_enqueue_script('wp_sms_text_js_2', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js');
	wp_enqueue_script('wp_sms_text_js_2');
}
//add_action( 'admin_enqueue_scripts', 'admin_my_js' );


function sms_get_content($atts, $content = null){
	extract( shortcode_atts( array(
      'keywords' => 'keywords',
      'phonenumber' => 'phonenumber'
      ), $atts ) );
	  
	$string = '';
	$string .= '<div class="sms-text-block ">';
	$string .= '	<div class="row ">';
    $string .= '      <div class="col card col-md-12">';
    $string .= '        <form class="form-inline hidden-xs">';
    $string .= '          <div class="form-group">';
    $string .= '            <label class="sr-only">Phone</label>';
    $string .= '            <p class="form-control-static"></p>';
    $string .= '          </div>';
    $string .= '          <div class="form-group mx-sm-3">';
    $string .= '            <label class="sr-only" for="phoneNumber">Phone</label>';
    $string .= '            <input class="form-control" id="phoneNumber" placeholder="(_ _ _) -_ _ _ _" type="phone">';
    $string .= '            <input class="form-control" id="keywords" type="hidden" value="'.esc_attr($keywords).'">';
    $string .= '          </div>';
    $string .= '          <button class="btn btn-primary submit-button" type="submit">Get Started</button>';
    $string .= '        </form>';
    $string .= '        <div class="hidden-md hidden-lg hidden-sm text-center ">';
    $string .= '        	<a class="btn btn-primary  " href="sms:'.esc_attr($phonenumber).'?body='.esc_attr($keywords).'">Text '.esc_attr($keywords).' to '.esc_attr($phonenumber).'</a>';
    $string .= '      	</div>';
    $string .= '      </div>';
    $string .= '    </div>';
    $string .= '</div>';
	
	return $string ;
}
// Register shorcode
add_shortcode('sms_show_content', 'sms_get_content');


?>