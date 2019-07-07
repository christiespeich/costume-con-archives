<?php

/**
 * The functionality of the plugin that applies to both public and admin
 *
 * @link       http://www.mooberrydreams.com
 * @since      1.0.0
 *
 * @package    Costume_Con_Archives
 * @subpackage Costume_Con_Archives/admin
 */

/**
 * The functionality of the plugin that applies to both public and admin
 *
 * Defines the plugin name, version, enqueues the stylesheet and JavaScript.
 * Registers post types
 *
 * @package    Costume_Con_Archives
 * @subpackage Costume_Con_Archives/admin
 * @author     Christie <Speich>
 */
class Costume_Con_Archives_Shared {

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


		wp_enqueue_style( $this->plugin_name . '-shared', plugin_dir_url( __FILE__ ) . 'css/costume-con-archives-shared.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript used on both admin and public areas.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {


		wp_enqueue_script( $this->plugin_name. '-shared', plugin_dir_url( __FILE__ ) . 'js/costume-con-archives-shared.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function register_post_types() {
		$con_cpt = new CCA_Cons_CPT();
		$person_tax = new CCA_People_Tax();


	}

}
