<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/6/2018
 * Time: 2:13 PM
 */



if ( !interface_exists( 'MOOBD_ICustom_Post_Type' ) ) {
	interface MOOBD_ICustom_Post_Type {

		public function __construct( );

		public function set_up( $id, $singular_name, $plural_name );

		public function set_arg( $key, $value );

		public function set_label( $key, $value );

		public function register();

		public function add_custom_taxonomy( MOOBD_ICustom_Taxonomy $taxonomy );

		public function add_existing_taxonomy( $taxonomy );

	}
}
