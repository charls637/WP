<?php
/*
Plugin Name: Event Button Plugin
Plugin URI: #
Description: Register Event Button
Version: The Plugin's Version Number v1.0
Author: Charley Birondo
Author URI: https://github.com/charls637
License: GPL2
*/

defined('ABSPATH') or die('No script kiddies please!');
function sp_activation_hook(){
	// Activation code here
	// Create database tables or entries etc
}
//Register activation hook
register_activation_hook(__FILE__, 'sp_activation_hook');
function sp_deactivation_hook(){
	// Deactivation code here
	//  Remove databsae entries or other settings if necessary 
}
//Register activation hook
register_deactivation_hook(__FILE__, 'sp_deactivation_hook');
// SP admin page
function sp_create_content_page(){
	// if user have submitted form
	if(isset($_POST['submit'])){
		update_option( 'sp_content', $_POST['content'] );
		update_option( 'sp_link', $_POST['link'] );
		update_option( 'sp_date', $_POST['datepicker'] );
	}
?>
	 <script>
	  jQuery( function() {
		jQuery( "#datepicker" ).datepicker();
	  } );
	  </script>
	<div class="wrap">
		<h2>Event Button Plugin Page</h2>
		<form method="post" id="wp_event_button-form">
			<div>
				<label>Title: </label>
				<input style="width: 500px;" type="text" id="content" name="content" value="<?php echo get_option('sp_content') ?>">
			</div>
			<div>
				<label>Link of the registration: </label>
				<input style="width: 500px;" type="text" id="link" name="link" value="<?php echo get_option('sp_link') ?>">
			</div>
			<div>
				<label>Date of the last registration: </label>
				<input style="width: 500px;" type="text" id="datepicker" name="datepicker" value="<?php echo get_option('sp_date') ?>">
			</div>
			<div>
				<input type="submit" class="button button-primary button-large" name="submit" value="Save">
			</div>
		</form>
	</div>
<?php
}
// Register a Menu
function sp_register_menu(){
	add_menu_page( 'Event Button', 'Event Button', 'manage_options', 'wp_event_button', 'sp_create_content_page');
}
// Add action to admin_menu for page
add_action('admin_menu', 'sp_register_menu');



function prefix_add_my_stylesheet() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'wp_event_button_css', plugins_url('style.css', __FILE__), array(), null, 'all' );    
    wp_enqueue_style( 'wp_event_button_css' );
	
}
add_action( 'wp_enqueue_scripts', 'prefix_add_my_stylesheet' );

function admin_my_css() {
	wp_register_style( 'wp_event_button_css_2', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', array(), null, 'all' );
	wp_enqueue_style( 'wp_event_button_css_2' );
}
add_action( 'admin_enqueue_scripts', 'admin_my_css' );

function admin_my_js() {
	wp_enqueue_script('wp_event_button_js', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js');
	wp_enqueue_script('wp_event_button_js');
}
add_action( 'admin_enqueue_scripts', 'admin_my_js' );

// Shortcode function
function sp_get_content(){
	date_default_timezone_set('America/New_York');
	$date_now=date('d/m/y');
	$date_reg=date('d/m/y', strtotime(get_option('sp_date')));
	
	
	if($date_reg < $date_now || get_option('sp_date') == null){
		$string = '';
	}else{
		$string = '<div class="event-block" style="left: 0px; display: block; padding: 10px 0; position: fixed; background-color: #262626; bottom: 0px; width: 100%; z-index: 9999; min-height: 55px;">';
		$string .= '<div class="container_inner" style="">';
		
		$string .= '<div class="wpb_column vc_column_container vc_col-sm-6" style="margin-bottom: 10px;">';
		$string .= '<div class="vc_align_center">';
		$string .= '<p class="vc_align_center" style="color: #ffffff; text-align: center; font-weight: bold; padding: 7px 0px;">'.get_option('sp_content').'</p>';
		$string .= '</div></div>';
		
		$string .= '<div class="wpb_column vc_column_container vc_col-sm-6" style="margin-bottom: 10px;">';
		$string .= '<div class="vc_align_center">';
		$string .= '<a class="vc_align_center" style="margin: 0 auto; max-width: 200px; text-align: center; color: #fff; background-color: #dba600; display: block; padding: 7px 50px; border-radius: 7px;" href="'. get_option('sp_link') .'" target="_blank" rel="noopener">REGISTER</a>';
		$string .= '</div></div>';
		
		$string .= '</div>';
		$string .= '</div>';
		$string .= '<style>footer{margin-bottom: 130px;}</style>';
	}
	return $string ;
}
// Register shorcode
add_shortcode('sp_show_content', 'sp_get_content');


function sp_display() {

echo do_shortcode('[sp_show_content]');

}

add_action('wp_footer', 'sp_display');
?>

