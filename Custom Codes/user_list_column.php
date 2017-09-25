<?php
/* 
 * Adding the column to the USER LIST TABLE
 */
function rd_user_id_column( $columns ) {
	$columns['site_name'] = 'Site Name';
	$columns['user_id'] = 'Site ID';
	return $columns;
}
add_filter('manage_users_columns', 'rd_user_id_column');
add_filter('wpmu_users_columns', 'rd_user_id_column');
 
/*
 * Column content
 */
function rd_user_id_column_content($value, $column_name, $user_id) {
	if ( 'user_id' == $column_name ){
		return the_author_meta('primary_blog' ,$user_id);
	}
	if ( 'site_name' == $column_name ){
		$site_id = get_the_author_meta('primary_blog' ,$user_id);
		$sitedetails =  get_blog_details($site_id);
		return  $sitedetails->blogname;
	}
	return $value;
}
add_action('manage_users_custom_column',  'rd_user_id_column_content', 10, 3);
add_action('wpmu_users_custom_column',  'rd_user_id_column_content', 10, 3);
 
/*
 * Column style (you can skip this if you want)
 */
function rd_user_id_column_style(){
	echo '<style>.column-user_id{width: 3%}</style>';
	echo '<style>.column-site_name{width: 7%}</style>';
}
add_action('admin_head-users.php',  'rd_user_id_column_style');

?>