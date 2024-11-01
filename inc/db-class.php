<?php  

class WP_EIS_DB
{
	private $wpdb;
	//private $table_prefix;
	public function __construct()
	{
		global $wpdb;
		$this->wpdb = &$wpdb;
		//$this->table_prefix = &$table_prefix;
	}

	public function wp_eis_name_install()
	{
		global $wpdb;

		$wp_eis_db_name = $wpdb->prefix.'eis_name';
		if( $wpdb->get_var("show tables like '$wp_eis_db_name'") != $wp_eis_db_name )
		{
			$sql_name = "CREATE TABLE " . $wp_eis_db_name . " (
				`ID_name` int(11) NOT NULL AUTO_INCREMENT,
				`name` varchar(70) NOT NULL,
				`animation` varchar(70)  NULL,
				`autoplay` varchar(10)  NULL,
				`slideshow_interval` int(10)  NULL,
				`speed` int(10)  NULL,
				`easing` varchar(40)  NULL,
				`titles_factor` float(5)  NULL,
				`titles_speed` int(10)  NULL,
				`titles_easing` varchar(40)  NULL,
				`thumb_max_width` int(10)  NULL,
				UNIQUE KEY ID_name (ID_name)
			) CHARACTER SET utf8 COLLATE utf8_general_ci;";
	 
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql_name);
		}
	}

	public function wp_eis_items_install()
	{
		global $wpdb;

		$wp_eis_db_items = $wpdb->prefix.'eis_items';
		if( $wpdb->get_var("show tables like '$wp_eis_db_items'") != $wp_eis_db_items )
		{
			$sql_items = "CREATE TABLE " . $wp_eis_db_items . " (
				`ID` int(11) NOT NULL AUTO_INCREMENT,
				`ID_name` int(11) NOT NULL,
				`attach_ID` int(11) NOT NULL,
				`name` varchar(70) NOT NULL,
				`title_h2` text NULL,
				`title_h3` text NULL,
				`image` text NOT NULL,
				`order` int(11) NULL,
				UNIQUE KEY ID (ID)
			) CHARACTER SET utf8 COLLATE utf8_general_ci;";
	 
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql_items);
		}
	}

	public function insert_data($table, $data, $format)
	{
		global $wpdb;
		$wpdb->insert($table, $data, $format);
	}

	public function update_data($table, $data, $where, $format, $where_format)
	{
		global $wpdb;
		$wpdb->update( $table, $data, $where, $format = null, $where_format = null );
	}

	public function delete_data_by_id($table, $where, $id)
	{
		global $wpdb;
		$sql = $wpdb->prepare("DELETE FROM {$table} WHERE {$where} = %d ", $id);
   		$wpdb->query($sql);
	}
}

?>