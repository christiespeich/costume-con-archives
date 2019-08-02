<?php
	

abstract class MOOBD_Database {
	protected $primary_key;
	protected $table;
	protected $version;
	protected $flush_on_update = false;



	public function __construct( $table_name ) {
		$this->table = $table_name;
	}


	protected function columns_with_html() {
		return array();
	}


	protected function allows_html( $column ) {
		return ( in_array( $column, $this->columns_with_html() ) );
	}

	final public function table_name() {
		//global $wpdb;
		//return $wpdb->prefix . $this->table;
		return $this->table;
	}


	protected function get_column_defaults() {
		$columns  = $this->get_columns();
		$defaults = array();
		foreach ( $columns as $column => $format ) {
			$defaults[ $column ] = '';
		}

		return $defaults;
	}

	protected function column_exists( $column ) {
		$columns = $this->get_columns();

		return array_key_exists( $column, $columns );
	}

	protected function validate_orderby( $orderby ) {
		if ( $orderby == '' || $orderby == null || ! $this->column_exists( $orderby ) ) {
			$orderby = $this->primary_key;
		}

		return esc_sql( $orderby );
	}

	protected function validate_order( $order ) {
		if ( $order == null || ( $order != 'ASC' && $order != 'DESC' ) ) {
			$order = 'ASC';
		}

		return esc_sql( $order );
	}

	protected function run_sql( $sql, $cache_results = true ) {
		global $wpdb;

		$data = $wpdb->get_results( $sql );

		return $data;
	}

	public function get_rows( $starting_at = 0, $how_many = 10, $orderby = null, $order = null, $cache_results = false ) {
		global $wpdb;

		$orderby = $this->validate_orderby( $orderby );

		$order = $this->validate_order( $order );

		$starting_at = intval( $starting_at );

		$how_many = intval( $how_many );


		$table = $this->table_name();
		$sql   = $wpdb->prepare( "SELECT * 
						FROM $table AS t
						ORDER BY %s %s
						LIMIT %d, %d;",
			$orderby,
			$order,
			$starting_at,
			$how_many);

		return $this->run_sql( $sql, $cache_results );


	}

	public function get( $value, $cache_results = true ) {
		global $wpdb;

		$table = $this->table_name();

		$sql  = $wpdb->prepare( "SELECT * FROM $table WHERE $this->primary_key = %s ", $value );
		$data = $wpdb->get_row( $sql );

		return $data;
	}

	protected function get_by( $column, $row_id, $cache_results = true ) {
		global $wpdb;

		$column = esc_sql( $column );
		$table  = $this->table_name();
		$sql    = $wpdb->prepare( "SELECT * FROM $table WHERE $column = %s LIMIT 1;", $row_id );

		$data = $wpdb->get_row( $sql );


		return $data;

	}

	protected function get_multiple( $values, $orderby = null, $order = null, $cache_results = false ) {

		$orderby = $this->validate_orderby( $orderby );

		$order = $this->validate_order( $order );

		if ( ! is_array( $values ) ) {
			$values = array( $values );
		}
		$values = array_map( 'esc_sql', $values );
		$values = array_map( 'sanitize_title_for_query', $values );

		$table = $this->table_name();
		global $wpdb;
		$sql = $wpdb->prepare( "SELECT * FROM $table WHERE $this->primary_key IN (%s)  ORDER BY %s %s;", implode( ',', $values ), $orderby, $order );

		return $this->run_sql( $sql, $cache_results );

	}

	public function get_all( $orderby = null, $order = null, $cache_results = true ) {
		global $wpdb;
		$orderby = $this->validate_orderby( $orderby );

		$order = $this->validate_order( $order );

		$table = $this->table_name();
		$sql   = $wpdb->prepare( "SELECT * 
						FROM $table AS t
						ORDER BY %s %s;",
			$orderby,
			$order );

		return $this->run_sql( $sql, $cache_results );
	}

	public function get_count() {
		global $wpdb;

		$table   = $this->table_name();
		$sql     = "SELECT count(*) AS number FROM $table ";
		$results = $wpdb->get_col( $sql );
		if ( count( $results ) > 0 ) {
			return $results[0]->number;
		} else {
			return null;
		}

	}

	public function save( $data, $id, $auto_increment = true, $type = '' ) {

		if ( $type != '' ) {
			$type = '_' . $type;
		}

		$results = $this->get( $id );

		if ( $results == null || empty( $results ) ) {

			// if the K is not auto-incrememnt, add it to the data array
			if ( ! $auto_increment ) {
				$data[ $this->primary_key ] = $id;
			}

			return $this->insert( $data, $type );

		} else {
			return $this->update( $id, $data, $type );
		}
	}

	protected function insert( $data, $type = '' ) {

		global $wpdb;

		// Set default values
		$data = wp_parse_args( $data, $this->get_column_defaults() );


		// Initialise column format array
		$column_formats = $this->get_columns();

		// Force fields to lower case
		$data = array_change_key_case( $data );

		// White list columns
		$data = array_intersect_key( $data, $column_formats );

		// Reorder $column_formats to match the order of columns given in $data
		$data_keys      = array_keys( $data );
		$column_formats = array_merge( array_flip( $data_keys ), $column_formats );

		$table   = $this->table_name();
		$success = $wpdb->insert( $table, $data, $column_formats );

		return $success;
	}

	protected function update( $row_id, $data = array(), $type = '' ) {

		global $wpdb;

		// Row ID must be positive integer
		$row_id = absint( $row_id );

		if ( empty( $row_id ) ) {
			return false;
		}

		// Initialise column format array
		$column_formats = $this->get_columns();

		// Force fields to lower case
		$data = array_change_key_case( $data );

		// White list columns
		$data = array_intersect_key( $data, $column_formats );

		// Reorder $column_formats to match the order of columns given in $data
		$data_keys      = array_keys( $data );
		$column_formats = array_merge( array_flip( $data_keys ), $column_formats );

		$pk = array( $this->primary_key => $row_id );

		$table   = $this->table_name();
		$success = $wpdb->update( $table, $data, $pk, $column_formats );


		if ( false === $success ) {
			return false;
		}

		return true;

	}

	public function delete( $value ) {
		global $wpdb;

		// Row ID must be positive integer
		$value = absint( $value );
		if ( empty( $value ) ) {
			return false;
		}

		$table = $this->table_name();

		$success = $wpdb->query( $wpdb->prepare( "DELETE FROM $table WHERE $this->primary_key = %d ", $value ) );

		if ( false === $success ) {
			return false;
		}

		return true;
	}

	public function empty_table() {
		global $wpdb;

		$table = $this->table_name();

		$success = $wpdb->query( "TRUNCATE TABLE $table" );
		if ( false === $success ) {
			return false;
		}

		return true;
	}

	protected function insert_id() {
		global $wpdb;

		return $wpdb->insert_id;
	}

	protected function get_in_format( $list, $format ) {

		// prepare the right amount of placeholders
		// if you're looing for strings, use '%s' instead
		$placeholders = array_fill( 0, count( $list ), $format );

		// glue together all the placeholders...
		// $format = '%d, %d, %d, %d, %d, [...]'
		return implode( ', ', $placeholders );
	}


	protected function time_to_date( $time ) {
		return gmdate( 'Y-m-d H:i:s', $time );
	}

	protected function now() {
		return $this->time_to_date( time() );
	}

	protected function date_to_time( $date ) {
		return strtotime( $date . ' GMT' );
	}

	protected function sanitize_field( $column, $value, $context = null ) {

		// same data should be sanitized and some should retain HTML
		if ( $this->allows_html( $column ) ) {
			if ( $context == null ) {
				$value = wp_kses_post( $value );
			} else {
				$value = wp_kses( stripslashes_deep( $value ), $context );
			}
		} else {
			$value = strip_tags( stripslashes( $value ) );
		}

		// values should be entered into the database as nulls not blanks
		// this affects fields such as published date and series order
		// this became a problem after adding in the override_remove hook
		/*if ($value == '') {
			$value = null;
		}	*/

		return $value;
	}
}
