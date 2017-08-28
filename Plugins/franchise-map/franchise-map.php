<?php
/*
Plugin Name: Franchise Map
Plugin URI: #
Description: Franchise Map
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
		
		$path = str_replace('\\', '/', plugin_dir_path( __FILE__ ));
		
		$zip_name = $_FILES["zip"]["name"];
		$zip_path = $path . 'uploads/' . $zip_name;
		
		$loc_name = $_FILES["location"]["name"];
		$loc_path = $path . 'uploads/' . $loc_name;

		if ( move_uploaded_file($_FILES["zip"]["tmp_name"],$zip_path) && move_uploaded_file($_FILES["location"]["tmp_name"],$loc_path)) {
			echo "The file ". ($_FILES['zip']['name'])." AND ". ($_FILES['location']['name']) ." has been uploaded";			
			update_option( 'sp_zip', $zip_path);
			update_option( 'sp_loc', $loc_path);
		}
		else {
			echo "ERROR";
		}
		
		//update_option( 'sp_location', $dir.''. $_POST['location'] );
		
		
		
	}
?>
	 <script>
	  </script>
	  
	<div class="wrap">
		<h2>Franchise Map</h2>
		<form method="post" id="wp_franchise_map_form" enctype="multipart/form-data">
			<div>
				<?php
				$zip = strrpos(get_option('sp_zip') , '/');
				$zip_name = $zip === false ? get_option('sp_zip') : substr(get_option('sp_zip'), $zip + 1);
				
				$loc = strrpos(get_option('sp_loc') , '/');
				$loc_name = $loc === false ? get_option('sp_loc') : substr(get_option('sp_loc'), $loc + 1);
				?>
				<p>Zip (active): <a href="<?php echo get_option('sp_zip') ?>"> <?php echo $zip_name; ?> </a></p>
				<p>Location (active): <a href="<?php echo get_option('sp_loc') ?>"> <?php echo $loc_name; ?> </a></p>
			</div>
			<div>
				<label class="text-reverse">Zip Code to country code file:</label>
				<input type="file" name="zip" id="zip" value="">
				<br style="clear:both;">
			</div>
			<div>
				<label class="text-reverse">Locations file:</label>
				<input type="file" name="location" id="location" value="<?php echo get_option('sp_loc') ?>">
				<br style="clear:both;">
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
	add_menu_page( 'Franchise Map', 'Franchise Map', 'manage_options', 'wp_franchise_map', 'sp_create_content_page');
}
// Add action to admin_menu for page
add_action('admin_menu', 'sp_register_menu');



function prefix_add_my_stylesheet() {
    // Respects SSL, Style.css is relative to the current file
    wp_register_style( 'wp_franchise_map_css', plugins_url('style.css', __FILE__), array(), null, 'all' );    
    wp_enqueue_style( 'wp_franchise_map_css' );
	
}
add_action( 'wp_enqueue_scripts', 'prefix_add_my_stylesheet' );

function admin_my_css() {
	wp_register_style( 'wp_franchise_map_css_2', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', array(), null, 'all' );
	wp_enqueue_style( 'wp_franchise_map_css_2' );
}
add_action( 'admin_enqueue_scripts', 'admin_my_css' );

function admin_my_js() {
	wp_enqueue_script('wp_franchise_map_js', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js');
	wp_enqueue_script('wp_franchise_map_js');
}
add_action( 'admin_enqueue_scripts', 'admin_my_js' );

// Shortcode function
function sp_get_content(){
		$search_zip = $_GET['search_zip'];

		$string = '<div class="map-block" >';
		$string .= '<div class="limit-wrapper" style="">';
		
		$string .= '<div class="wpv-grid grid-1-1 " style="margin-bottom: 10px;">';
		$string .= '<div class="vc_align_center">';
		$string .= '<form action="#" method="GET">';
		$string .= '<input type="submit" name="submit_zip" id="submit_zip" style="float:right" value="Search">';
		$string .= '<input type="text" name="search_zip" id= "search_zip" placeholder="Search Zip Code" style="height: 20px;max-width:300px; float:right;">';
		$string .= '</form></div></div>';
		$string .= '</div>';
		$string .= '</div>';
		
	
		$csvData_zip = file_get_contents(get_option('sp_zip'));
		$lines_zip = explode(PHP_EOL, $csvData_zip);
		$zip = array();
		foreach ($lines_zip as $line) {
			$zip[] = str_getcsv($line);
		}
		
		$csvData_loc = file_get_contents(get_option('sp_loc'));
		$lines_loc = explode(PHP_EOL, $csvData_loc);
		$loc = array();
		foreach ($lines_loc as $line) {
			$loc[] = str_getcsv($line);
		}
		
		$results = array();		
		$c_code;
		$once =0;
		if (isset($_GET['search_zip']) && $_GET['search_zip'] !=null ) {
			
			
			
			foreach ($zip as $zip_data) {
				if($_GET['search_zip'] == $zip_data[0]){
					$c_code = $zip_data[1];
					break;
				}			
			}
			
			foreach ($loc as $loc_data) {
				$pos = strpos($loc_data[5], $c_code);
				
				if ($pos !== false) {
					if($once < 1){
						$actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
						$link = explode('?', $actual_link);
						$string .= '<div id="result-zip" class="modal" >';
						$string .= '<div class="wpv-grid grid-1-1 bg-white" style="margin-bottom: 10px;">';
						$string .= '<a href="'.$link[0].'" class="strong" style=";color:red;">Close</p>';
						$string .= '<div class="vc_align_center">';
						$string .= '<br />';
						$string .= '<div class="wpv-grid grid-1-6 ">';
						$string .= '<p class="strong">Franchise ID</p>';
						$string .= '</div>';
						$string .= '<div class="wpv-grid grid-1-6 ">';
						$string .= '<p class="strong">Franchise Name</p>';
						$string .= '</div>';
						$string .= '<div class="wpv-grid grid-1-6 ">';
						$string .= '<p class="strong">Phone</p>';
						$string .= '</div>';
						$string .= '<div class="wpv-grid grid-1-6 ">';
						$string .= '<p class="strong">Website</p>';
						$string .= '</div>';
						$string .= '<div class="wpv-grid grid-1-6 ">';
						$string .= '<p class="strong">Email</p>';
						$string .= '</div>';
						$string .= '<div class="wpv-grid grid-1-6 ">';
						$string .= '<p class="strong">Country Codes</p>';
						$string .= '</div>';
						$once++;
					}
					$string .= '<div class="wpv-grid grid-1-6 ">';
					$string .= '<p>'.$loc_data[0].'</p>';
					$string .= '</div>';
					$string .= '<div class="wpv-grid grid-1-6 ">';
					$string .= '<p>'.$loc_data[1].'</p>';
					$string .= '</div>';
					$string .= '<div class="wpv-grid grid-1-6 ">';
					$string .= '<p>'.$loc_data[2].'</p>';
					$string .= '</div>';
					$string .= '<div class="wpv-grid grid-1-6 ">';
					$string .= '<p>'.$loc_data[3].'</p>';
					$string .= '</div>';
					$string .= '<div class="wpv-grid grid-1-6 ">';
					$string .= '<p>'.$loc_data[4].'</p>';
					$string .= '</div>';
					$string .= '<div class="wpv-grid grid-1-6 ">';
					$string .= '<p>'.$loc_data[5].'</p>';
					$string .= '</div>';
				} 
			}
			
			$string .= '</div></div></div>';
				
		}else{
			
		}
		
	return $string ;
}
// Register shorcode
add_shortcode('sp_show_content', 'sp_get_content');


function sp_display() {

echo do_shortcode('[sp_show_content]');

}

add_action('wp_head', 'sp_display');
?>

