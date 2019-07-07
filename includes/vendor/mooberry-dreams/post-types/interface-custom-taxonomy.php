<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/6/2018
 * Time: 2:15 PM
 */

if ( !interface_exists( 'MOOBD_ICustom_Taxonomy' ) ) {
	interface MOOBD_ICustom_Taxonomy {

		public function __construct( );

		public function set_up( $taxonomy, $objects, $singular_name, $plural_name );

		public function set_arg( $key, $value );

		public function set_label( $key, $value );

		public function get_taxonomy();

		public function register();

	}

}
