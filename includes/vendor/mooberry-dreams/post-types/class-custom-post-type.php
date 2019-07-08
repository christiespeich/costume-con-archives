<?php
/**
 * Created by PhpStorm.
 * User: christie
 * Date: 2/6/2018
 * Time: 1:28 PM
 */
require_once('interface-custom-post-type.php');
require_once('interface-custom-taxonomy.php');
require_once('class-custom-taxonomy.php');

if ( !class_exists( 'MOOBD_Custom_Post_Type' ) ) {
	class MOOBD_Custom_Post_Type implements MOOBD_ICustom_Post_Type {

		private $post_type;
		private $singular_name;
		private $plural_name;
		private $args;
		private $taxonomies;
		private $custom_taxonomies;



		public function set_up ( $post_type, $singular_name, $plural_name ) {
			$this->post_type     = $post_type;
			$this->singular_name = ucwords( $singular_name );
			$this->plural_name   = ucwords( $plural_name );
			$this->taxonomies = array();
			$this->custom_taxonomies = array();

			// set defaults
			$labels = array(
				'name'               => $this->plural_name,
				'singular_name'      => $this->singular_name,
				'menu_name'          => $this->plural_name,
				'name_admin_bar'     => $this->singular_name,
				'add_new'            => __( 'Add New', 'mooberry-directory' ),
				'add_new_item'       => __( 'Add New', 'mooberry-directory' ) . ' ' . $this->singular_name,
				'new_item'           => __( 'New', 'mooberry-directory' ) . ' ' . $this->singular_name,
				'edit_item'          => __( 'Edit', 'mooberry-directory' ) . ' ' . $this->singular_name,
				'view_item'          => __( 'View', 'mooberry-directory' ) . ' ' . $this->singular_name,
				'all_items'          => __( 'All ', 'mooberry-directory' ) . $this->plural_name,
				'search_items'       => __( 'Search', 'mooberry-directory' ) . ' ' . $this->plural_name,
				'parent_item_colon'  => sprintf( __( 'Parent %s:', 'mooberry-directory' ), $this->plural_name ),
				'not_found'          => sprintf( __( 'No %s found.', 'mooberry-directory' ), strtolower( $this->plural_name ) ),
				'not_found_in_trash' => sprintf( __( 'No %s found in Trash.', 'mooberry-directory' ), strtolower( $this->plural_name ) ),
			);

			$this->args = array(  'labels'  =>  $labels,
			                      'public'  => true,
				);


		}

		public function set_arg( $key, $value ) {
			$this->args[ $key ] = $value;
		}

		public function set_label( $key, $value ) {
			$this->args['labels'][ $key ] = $value;
		}

		public function register() {
			$this->args['taxonomies'] = $this->taxonomies;
			foreach ( $this->custom_taxonomies as $custom_taxonomy ) {
				$this->args['taxonomies'] = $custom_taxonomy->get_taxonomy();
			}

			register_post_type( $this->post_type, $this->args );
			foreach ( $this->taxonomies as $taxonomy ) {
				register_taxonomy_for_object_type( $taxonomy, $this->post_type );
			}
		}

		public function add_custom_taxonomy( MOOBD_ICustom_Taxonomy $taxonomy ) {
			$this->custom_taxonomies[] = $taxonomy;
		}

		public function add_existing_taxonomy( $taxonomy ) {
			$this->taxonomies[] = $taxonomy;
		}
	}
}
