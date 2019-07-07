<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/6/2018
 * Time: 1:31 PM
 */
require_once('interface-custom-taxonomy.php');
if ( !class_exists( 'MOOBD_Custom_Taxonomy') ) {
	class MOOBD_Custom_Taxonomy implements MOOBD_ICustom_Taxonomy {

		private $taxonomy;
		private $objects;
		private $singular_name;
		private $plural_name;
		private $args;


		public function __construct( ) {
		}

		public function set_up ($taxonomy, $objects, $singular_name, $plural_name ) {
			$this->taxonomy = $taxonomy;

			if ( ! is_array( $objects ) ) {
				$objects = array( $objects );
			}
			$this->objects = $objects;

			$this->singular_name = ucwords( $singular_name );
			$this->plural_name   = ucwords( $plural_name );

			$this->args['labels'] = array(
				'name'              => $this->plural_name,
				'singular_name'     => $this->singular_name,
				'search_items'      => __( 'Search', 'mooberry-directory' ) . ' ' . $this->plural_name,
				'all_items'         => __( 'All', 'mooberry-directory' ) . ' ' . $this->plural_name,
				'parent_item'       => __( 'Parent', 'mooberry-directory' ) . ' ' . $this->singular_name,
				'parent_item_colon' => __( 'Parent %s:', 'mooberry-directory' ),
				$this->singular_name,
				'edit_item'         => __( 'Edit', 'mooberry-directory' ) . ' ' . $this->singular_name,
				'update_item'       => __( 'Update', 'mooberry-directory' ) . ' ' . $this->singular_name,
				'add_new_item'      => __( 'Add New', 'mooberry-directory' ) . ' ' . $this->singular_name,
				'new_item_name'     => sprintf( __( 'New %s Name', 'mooberry-directory' ), $this->singular_name ),
				'menu_name'         => $this->singular_name,
			);

		}

		public function set_arg( $key, $value ) {
			$this->args[ $key ] = $value;
		}

		public function set_label( $key, $value ) {
			$this->args['labels'][ $key ] = $value;
		}

		public function get_taxonomy() {
			return $this->taxonomy;
		}

		public function register() {
			register_taxonomy( $this->taxonomy, $this->objects, $this->args );

			foreach ( $this->objects as $object ) {
				register_taxonomy_for_object_type( $this->taxonomy, $object );
			}
		}

	}
}
