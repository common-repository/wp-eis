<?php  

/*
Plugin Name: WP EIS
Plugin URI: http://blog.mehral.com/projects/eis-wordpress-plugin/
Description:  The slideshow will adjust automatically to its surrounding container and we can navigate through the slides by using the thumbnail previewer or the autoplay slideshow option. 
Version: 1.3.3
Author: Mehrdad Farahani
Author URI: http://www.mehral.com/
License: GPL2

	Copyright 2011  Mehrdad Farahani  (email : contact@mehral.com)
	This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('WP_EIS_DIR', trailingslashit( WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__ ),"",plugin_basename( __FILE__ ) ) ) );
define('WP_EIS_URL', trailingslashit( WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__ ),"",plugin_basename( __FILE__ ) ) ) );
define( 'WP_EIS_VERSION', '1.3.3' );
// define( 'WP_EIS_REQUIRED_WP_VERSION', '3.5.1' );
load_plugin_textdomain('wp-eis', false, basename( dirname( __FILE__ ) ) . '/langs' );

require ( WP_EIS_DIR.'inc/db-class.php' );
require ( WP_EIS_DIR.'inc/wp-eis-options.php' );
require ( WP_EIS_DIR.'wp-eis-shortcode.php' );

add_action('admin_menu', 'wp_eis_page');
function wp_eis_page() {
	$eis_o = new WP_EIS_Opt();
	$eis_o->wp_eis_page();
}
add_action('admin_init', 'wp_eis_page_init');
function wp_eis_page_init() {
	new WP_EIS_Opt();
}


register_activation_hook(__FILE__,'wp_eis_install');
function wp_eis_install() {
	$wp_eis_db = new WP_EIS_DB();
	$wp_eis_db->wp_eis_name_install();
	$wp_eis_db->wp_eis_items_install();
}


$opt = get_option( 'wp_eis_version' );
$opt_v2 = "1.2.0";
$opt_v1 = "1.1.0";
$opt_v = "1.0.0";
if( $opt ) {
	if ( $opt == $opt_v2 || $opt == $opt_v1 || $opt == $opt_v) {
		global $wpdb;
		update_option('wp_eis_version', WP_EIS_VERSION );
		$table_item = $wpdb->prefix.'eis_items';
		$table_name = $wpdb->prefix.'eis_name';
		$wpdb->query( "ALTER TABLE $table_item DROP COLUMN thumbnail" );
		$wpdb->query( "ALTER TABLE $table_item ADD COLUMN attach_ID int(11) NOT NULL AFTER ID_name");
		$wpdb->query( "ALTER TABLE $table_item CHANGE title_h2 title_h2 text NULL" );	
		$wpdb->query( "ALTER TABLE $table_item CHANGE title_h3 title_h3 text NULL" );
		$wpdb->query( "ALTER TABLE $table_item CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci" );
		$wpdb->query( "ALTER TABLE $table_name CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci" );	
	} else if( WP_EIS_VERSION > $opt ) {
		update_option('wp_eis_version', WP_EIS_VERSION );
		$table_item = $wpdb->prefix.'eis_items';
		$table_name = $wpdb->prefix.'eis_name';
		$wpdb->query( "ALTER TABLE $table_item CHANGE title_h2 title_h2 text NULL" );	
		$wpdb->query( "ALTER TABLE $table_item CHANGE title_h3 title_h3 text NULL" );	
		$wpdb->query( "ALTER TABLE $table_item CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci" );	
		$wpdb->query( "ALTER TABLE $table_name CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci" );	
	}
} else {
	add_option( 'wp_eis_version', WP_EIS_VERSION, '', 'yes' );
}

?>