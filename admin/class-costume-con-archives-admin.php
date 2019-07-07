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
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

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

		/*$main_settings = new MOOBD_Settings_Page( 'cca_options_page', 'cca_settings' );
		$main_settings->set_menu_title( __( 'CCA Settings', 'mooberry-directory' ) );
		$main_settings->create_metabox();*/


		$main_settings_page = new CCA_Main_Settings_Page( );
		$con_fields_settings_page = new CCA_Con_Fields_Settings_Page();
		$tax_Settings_pages = new MOOBD_Taxonomy_Settings_Page();

	}

}
