<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/6/2018
 * Time: 10:09 AM
 */

if ( !interface_exists( 'MOOBD_IMetabox_Field' ) ) {
	interface MOOBD_IMetabox_Field {

		public function __construct( $key );
		public function get_value( $post_id );
		public function get_key();
		public function save( $post_id, $value );
		public function set_multiple( $value );
		public function set_sanitize_callback( $value );
		public function set_save_callback( $value );

	}
}
