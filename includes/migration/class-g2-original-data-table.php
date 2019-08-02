<?php

if ( !class_exists('MOOBD_Database')) {
	require_once(COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/vendor/mooberry-dreams/moobd-database.php');
}

class CCA_G2_Original_Data_Table extends MOOBD_Database {

	public function __construct() {
		parent::__construct( 'cca_custom_data' );
		$this->primary_key = 'g_itemId';
	}

	public function get_cons( $orderby = null, $order = null, $cache_results = false ) {
		$orderby = $this->validate_orderby( $orderby );

		$order = $this->validate_order( $order );

		$table = $this->table_name();
		global $wpdb;
		$sql = $wpdb->prepare( "SELECT * FROM $table WHERE g_pathComponent REGEXP '^cc[1234567890]{1,2}?$'  ORDER BY %s %s;", $orderby, $order );

		return $this->run_sql( $sql, $cache_results );

	}

	public function get_photos( $orderby = null, $order = null, $cache_results = false, $start_at = null, $how_many = null ) {
		$orderby = $this->validate_orderby( $orderby );

		$order = $this->validate_order( $order );

		$limit_clause = '';
		$table        = $this->table_name();
		global $wpdb;

		if ( $start_at !== null && $how_many !== null ) {
			$start_at = intval( $start_at );
			$how_many = intval( $how_many );

			$sql = $wpdb->prepare( "SELECT * FROM $table WHERE g_pathComponent REGEXP '.jpg$' ORDER BY %s %s LIMIT %d, %d;", $orderby, $order, $start_at, $how_many );


		} else {
			$sql = $wpdb->prepare( "SELECT * FROM $table WHERE g_pathComponent REGEXP '.jpg$' ORDER BY %s %s;", $orderby, $order );
		}

		return $this->run_sql( $sql, $cache_results );

	}

	public function get_competitions( $orderby = null, $order = null, $cache_results = false ) {
		$orderby = $this->validate_orderby( $orderby );

		$order = $this->validate_order( $order );

		$table = $this->table_name();
		global $wpdb;
		$sql = $wpdb->prepare( "SELECT * FROM `cca_custom_data` WHERE g_pathComponent NOT REGEXP '.jpg$' and g_pathComponent NOT REGEXP '^cc[1234567890]{1,2}?$' ORDER BY %s %s;", $orderby, $order );

		return $this->run_sql( $sql, $cache_results );

	}

}
