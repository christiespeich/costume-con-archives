<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/6/2018
 * Time: 12:22 PM
 */


if ( !class_exists( 'MOOBD_Metabox_Field' ) ) {
	class MOOBD_Metabox_Field implements MOOBD_IMetabox_Field {
		private $key;
		private	$multiple;
		private	$sanitize_callback;
		private	$save_callback;

		public function __construct( $key ) {
			$this->key = $key;
			$this->multiple = false;
			$this->sanitize_callback = '';
			$this->save_callback = '';
		}

		public function set_multiple( $value ) {
			$this->multiple = $value;
		}

		public function set_sanitize_callback( $value ) {
			$this->sanitize_callback = $value;
		}

		public function set_save_callback( $value ) {
			$this->save_callback = $value;
		}

		public function get_key() {
			return $this->key;
		}

		public function get_value( $post_id ) {
			return get_post_meta( $post_id, $this->key, !$this->multiple );
		}

		final public function save( $post_id, $value ) {

			if ( isset($this->sanitize_callback) && function_exists( $this->sanitize_callback ) ) {
				$sanitized_value = call_user_func( $this->sanitize_callback, $value );
			} else {
				if ( is_array( $value ) ) {
					$sanitized_value = array();
					foreach ( $value as $single_value ) {
						$sanitized_value[] = sanitize_text_field( $single_value );
					}
				} else {
					$sanitized_value = sanitize_text_field( $value );
				}
			}

			if ( isset ( $this->save_callback ) && function_exists( $this->save_callback ) ) {
				call_user_func( $this->save_callback, $sanitized_value,  $this );
			} else {
				$this->save_field( $post_id, $sanitized_value );
			}
		}

		protected function save_field( $post_id, $value ) {

			if ( $this->multiple ) {
				if ( ! is_array( $value ) ) {
					$value = array( $value );
				}
				delete_post_meta( $post_id, $this->key );
				foreach ( $value as $single_value ) {
					add_post_meta( $post_id, $this->key, $single_value );
				}
			} else {
				update_post_meta( $post_id, $this->key, $value );
			}
		}

	}
}