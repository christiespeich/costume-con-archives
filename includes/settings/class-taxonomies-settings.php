<?php



class CCA_Taxonomies_Settings {

	private static $key = 'cca_taxonomies';
	private static $field = 'taxonomies';
	private static $taxonomies;

	public static function get_taxonomies() {
		self::maybe_load_taxonomies();
		return self::$taxonomies;
	}

	public static function get_taxonomy( $taxonomy_name ) {
		self::maybe_load_taxonomies();
		if ( array_key_exists( $taxonomy_name, self::$taxonomies ) ) {
			return self::$taxonomies[ $taxonomy_name ];
		}
		return array();
	}

	public static function set_taxonomy( $taxonomy_name, $tax_obj ) {
		self::maybe_load_taxonomies();
		self::$taxonomies[ $taxonomy_name ] = $tax_obj;
	}

	public static function update() {
		$taxonomies = array();
		foreach ( self::$taxonomies as $taxonomy ) {
			$taxonomies[] = $taxonomy->to_array();
		}
		CCA_Settings::update( self::$key, array( self::$field => $taxonomies ) );
	}

	private static function maybe_load_taxonomies() {
		if ( ! is_array( self::$taxonomies ) ) {
			self::load_taxonomies();
		}
	}

	private static function load_taxonomies( ) {
		$taxonomies = CCA_Settings::get( self::$key, self::$field, array() );
		self::$taxonomies = array();
		foreach ( $taxonomies as $taxonomy ) {
			$tax_obj = new CCA_Taxonomy( $taxonomy );
			self::$taxonomies[ $tax_obj->get_name() ] = $tax_obj;
		}
	}


	/*private static function set_field( $field, $value ) {
		Mooberry_Directory_Settings::set( self::$key, $field, $value );
	}

	public static function set( $value ) {
		Mooberry_Directory_Settings::update( self::$key, $value );
	}*/
/*
	public static function get_single_name() {
		return self::get( 'singular_name', '' );
	}

	public static function get_plural_name() {
		return self::get( 'plural_name', '' );
	}

	public static function get_slug() {
		return self::get( 'slug', '' );
	}

	public static function get_hierarchical() {
		return self::get( 'hierarchical', 'no' );
	}*/




}
