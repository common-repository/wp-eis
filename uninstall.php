<?php  
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

function wp_eis_delete()
{
	global $wpdb;

	delete_option( 'wp_eis_version' );
	delete_option( 'wp_eis_settings' );
	
	$eis_tables = array( $wpdb->prefix.'eis_name', $wpdb->prefix.'eis_items' );
	foreach ($eis_tables as $eis_table) {
		$wpdb->query( "DROP TABLE IF EXISTS $eis_table" );
	}
}
wp_eis_delete();
?>