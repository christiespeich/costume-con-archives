<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.mooberrydreams.com
 * @since      1.0.0
 *
 * @package    Costume_Con_Archives
 * @subpackage Costume_Con_Archives/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Costume_Con_Archives
 * @subpackage Costume_Con_Archives/admin
 * @author     Christie <Speich>
 */
class Costume_Con_Archives_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	private $con_cpt;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version, $con_cpt ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		$this->con_cpt = $con_cpt;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Costume_Con_Archives_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Costume_Con_Archives_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/costume-con-archives-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Costume_Con_Archives_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Costume_Con_Archives_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/costume-con-archives-admin.js', array( 'jquery' ), $this->version, false );

	}


	/**
	 * Hook in and register a metabox to handle a theme options page and adds a menu item.
	 */
	public function register_settings_metabox() {

		$main_settings_page       = new CCA_Main_Settings_Page();
		$tax_Settings_pages       = new CCA_Taxonomy_Settings_Page();
		$con_fields_settings_page = new CCA_Con_Fields_Settings_Page();


		$taxonomies = CCA_Taxonomies_Settings::get_taxonomies();
		foreach ( $taxonomies as $taxonomy ) {
			$tax_fields_page = new CCA_Tax_Fields_Settings_Page( $taxonomy );

		}

	}

	// render unique id
	public function render_unique_id( $field_args, $escaped_value, $object_id, $object_type, $field_type_object ) {
		echo $field_type_object->input( array( 'class' => 'cmb2_unique_id', 'type' => 'hidden' ) );
	}

	// sanitize the field
	public function sanitize_unique_id( $override, $new, $object_id ) {
		// Set unique id if it's not already set
		if ( empty( $new ) ) {
			$value = uniqid( $object_id . '_', false );
		} else {
			$value = $new;
		}

		return $value;
	}

	// render numbers
	public function render_text_number( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
		echo $field_type_object->input( array( 'class' => 'cmb2-text-small', 'type' => 'number' ) );
	}

	// sanitize the field
	public function sanitize_text_number( $null, $new ) {
		$new = preg_replace( "/[^0-9]/", "", $new );

		return $new;
	}

	public function con_cpt_add_metaboxes() {
		$this->con_cpt->add_metaboxes();
	}

	public function con_cpt_save_taxonomies( $id ) {
		$this->con_cpt->save_taxonomies( $id );
	}

	public function tax_term_metaboxes() {
		$taxonomies = CCA_Taxonomies_Settings::get_taxonomies();
		foreach ( $taxonomies as $taxonomy ) {

			$cmb = new_cmb2_box( array(
				'id'               => $taxonomy->get_name() . '_term_custom_fields',
				'title'            => __( 'Custom Fields', 'cmb2' ),
				'object_types'     => array( 'term' ), // Post type
				'taxonomies'       => array( $taxonomy->get_name() ),
				'context'          => 'normal',
				'priority'         => 'high',
				'show_names'       => true, // Show field names on the left
				'new_term_section' => true,
			) );

			$fields = CCA_Tax_Fields_Settings::get_fields_for_taxonomy( $taxonomy );
			foreach ( $fields as $field ) {

				$args = array(
					'name' => $field->name,
					'id'   => $field->id,
				);

				$args['type'] = $field->type;

				if ( $field->has_options() ) {
					$args['options'] = $field->options;
				}

				// if it's a self-referencing taxonomy, remove the current term to avoid circular reference
				if ( $field->is_tax_field ) {
					if ( $field->taxonomy == $taxonomy->get_name() ) {
						unset( $args['options'][ intval( $cmb->object_id ) ] );
					}
				}

				$cmb->add_field( $args );
			}
		}
	}
}
