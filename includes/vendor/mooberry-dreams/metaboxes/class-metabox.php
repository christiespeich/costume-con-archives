<?php

/**
 * Handles creating, displaying, and saving a metabox
 *
 * @link       http://www.mooberrydreams.com/
 * @since      1.0.0
 *
 * @package    Mooberry_Dreams
*/

/**
 * Handles creating, displaying, and saving a metabox
 *
 * @package    Mooberry_Dreams
 * @author     Mooberry Dreams <mooberrydreams@mooberrydreams.com>
 */
require_once('interface-metabox-field.php');
require_once('class-metabox-field.php');
if ( !class_exists( 'MOOBD_Metabox' ) ) {
	class MOOBD_Metabox {

		protected $name;
		protected $title;
		protected $display_callback;
		protected $fields;
		protected $nonce_field;
		protected $nonce_value;
		protected $objects;
		protected $context;
		protected $priority;

		public function __construct( $name, $title, $display_callback ) {

			$this->name             = sanitize_title($name);
			$this->title            = $title;
			$this->display_callback = $display_callback;
			$this->fields           = array();
			$this->nonce_field      = sanitize_title($name);
			$this->nonce_value      = $name;
			$this->objects          = null;
			$this->context          = 'advanced';
			$this->priority         = 'default';

			add_action( 'add_meta_boxes', array( $this, 'create' ), 1 );
			add_action( 'save_post', array( $this, 'save' ) );

		}

		public function count_fields() {
			return count( $this->fields );
		}

		public function add_field( MOOBD_IMetabox_Field $field ) {
			$key = $field->get_key();
			$this->fields[ $key ] = $field;
		}

		public function set_nonce_field( $value ) {
			$this->nonce_field = $value;
		}

		public function set_nonce_value( $value ) {
			$this->nonce_value = $value;
		}

		public function set_objects( $value ) {
			$this->objects = $value;
		}

		public function set_context( $value ) {
			$this->context = $value;
		}

		public function set_priority( $value ) {
			$this->priority = $value;
		}

		public function create() {
			add_meta_box( $this->name,
					$this->title,
					array( $this, 'display' ),
					$this->objects,
					$this->context,
					$this->priority );
		}

		public function display( $post ) {

			$field_values = array();
			foreach ( $this->fields as $field_key => $field ) {
				$field_values[ $field_key ] = $field->get_value( $post->ID );
			}
			wp_nonce_field( $this->nonce_value, $this->nonce_field );
			call_user_func( $this->display_callback, $field_values, $post );
		}

		public function save( $post_id ) {
			if ( ! isset( $_POST[ $this->nonce_field ] ) ) {
				return;
			}

			if ( ! wp_verify_nonce( $_POST[ $this->nonce_field ], $this->nonce_value ) ) {
				return;
			}

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			foreach ( $this->fields as $field_key => $field ) {
				// Check if $_POST field(s) are available
				if ( array_key_exists( $field_key, $_POST ) ) {
					$field->save( $post_id, $_POST[ $field_key ] );
				}
			}


		}

	}
}

