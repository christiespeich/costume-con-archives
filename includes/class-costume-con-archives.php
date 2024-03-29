<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.mooberrydreams.com
 * @since      1.0.0
 *
 * @package    Costume_Con_Archives
 * @subpackage Costume_Con_Archives/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Costume_Con_Archives
 * @subpackage Costume_Con_Archives/includes
 * @author     Christie <Speich>
 */
class Costume_Con_Archives {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Costume_Con_Archives_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	protected $con_cpt;
	protected $competition_cpt;
	protected $photos_cpt;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'COSTUME_CON_ARCHIVES_VERSION' ) ) {
			$this->version = COSTUME_CON_ARCHIVES_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'costume-con-archives';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_shared_hooks();
		$this->register_shortcodes();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Costume_Con_Archives_Loader. Orchestrates the hooks of the plugin.
	 * - Costume_Con_Archives_i18n. Defines internationalization functionality.
	 * - Costume_Con_Archives_Admin. Defines all hooks for the admin area.
	 * - Costume_Con_Archives_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		// bootstrap CMB2
		if ( file_exists( COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/vendor/cmb2/init.php' ) ) {
			require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/vendor/cmb2/init.php';
		} elseif ( file_exists( COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/vendor/CMB2/init.php' ) ) {
			require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/vendor/CMB2/init.php';
		}

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-costume-con-archives-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-costume-con-archives-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-costume-con-archives-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-costume-con-archives-public.php';

		/**
		 * The class responsible for defining all actions that occur in both the public-facing and admin
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'shared/class-costume-con-archives-shared.php';

		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'admin/class-admin-notice-manager.php';

		// post types and taxonomies
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/vendor/mooberry-dreams/post-types/class-custom-taxonomy.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/vendor/mooberry-dreams/post-types/class-custom-post-type.php';
		//require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/cons/class-cons-cpt.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/cpts/class-cpt-with-custom-fields.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/cpts/class-competition-cpt.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/cpts/class-con-cpt.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/cpts/class-photo-cpt.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/people/class-people-tax.php';

		// setting pages
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/vendor/mooberry-dreams/settings-pages/class-settings-page.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/vendor/mooberry-dreams/settings-pages/class-settings-tab.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/vendor/mooberry-dreams/settings-pages/class-tabbed-settings-page.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'admin/settings-pages/class-taxonomy-settings-page.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'admin/settings-pages/class-custom-fields-settings-page.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'admin/settings-pages/class-tax-fields-settings-page.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'admin/settings-pages/class-main-settings-page.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'admin/settings-pages/class-con-fields-settings-page.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'admin/settings-pages/class-photo-fields-settings-page.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'admin/settings-pages/class-competition-fields-settings-page.php';

		// settings
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/settings/class-settings.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/settings/class-taxonomy.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/settings/class-main-settings.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/settings/class-taxonomies-settings.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/settings/class-custom-fields-settings.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/settings/class-con-fields-settings.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/settings/class-competition-fields-settings.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/settings/class-photo-fields-settings.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/settings/class-tax-fields-settings.php';

		// custom fields
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/fields/class-custom-field.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/fields/class-taxonomy-field.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/fields/class-state-field.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/fields/class-website-field.php';
		require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/fields/class-email-field.php';

		// migration
		if ( CCA_Main_Settings::get_show_migration_menu() ) {
			require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/vendor/wp-background-processing/wp-background-processing.php';
			require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR .'includes/migration/class-g2-migration.php';
			require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR .'includes/migration/class-g2-original-data-table.php';
			require_once COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/migration/class-migrate-photos-background.php';
		}


		// custom taxonomies



		$this->loader = new Costume_Con_Archives_Loader();
		/*$this->con_cpt = new CCA_Cons_CPT();
		$this->compeition_cpt_cpt = new CCA_Competition_CPT();*/
		$this->con_cpt = new CCA_Con_CPT( COSTUME_CON_ARCHIVES_CON_CPT, CCA_Con_Fields_Settings::class);
		$this->competition_cpt = new CCA_Competition_CPT( COSTUME_CON_ARCHIVES_COMPETITION_CPT, CCA_Competition_Fields_Settings::class);
		$this->photos_cpt = new CCA_Photo_CPT( 'attachment', CCA_Photo_Fields_Settings::class);

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Costume_Con_Archives_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Costume_Con_Archives_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Costume_Con_Archives_Admin( $this->get_plugin_name(), $this->get_version());

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'admin_notices' );

		$this->loader->add_action( 'cmb2_admin_init', $plugin_admin, 'register_settings_metabox');
		$this->loader->add_action( 'cmb2_render_unique_id', $plugin_admin, 'render_unique_id', 10, 5 );
		$this->loader->add_filter( 'cmb2_sanitize_unique_id', $plugin_admin, 'sanitize_unique_id', 10 );
		$this->loader->add_action( 'cmb2_render_text_number', $plugin_admin, 'render_text_number', 10, 5 );
		$this->loader->add_filter( 'cmb2_sanitize_text_number', $plugin_admin, 'sanitize_text_number', 10, 2 );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'remove_taxonomy_metaboxes_on_con' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu', 99 );
		$this->loader->add_action( 'cmb2_admin_init', $plugin_admin, 'tax_term_metaboxes' ) ;
		$this->loader->add_action( 'edited_terms', $plugin_admin, 'tax_save_terms', 99, 2);
		if ( CCA_Main_Settings::get_show_migration_menu() ) {
			$this->loader->add_action( 'wp_ajax_cca_migrate_create_custom_fields', $plugin_admin, 'migrate_create_custom_fields' );
			$this->loader->add_action( 'wp_ajax_cca_import_data', $plugin_admin, 'import_g2_data' );
			$this->loader->add_action( 'wp_ajax_cca_import_photos', $plugin_admin, 'import_g2_photos' );
		}






	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Costume_Con_Archives_Public( $this->get_plugin_name(), $this->get_version());

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'cca_custom_tax_term_meta', $plugin_public, 'content' );
		$this->loader->add_shortcode( 'cca_album', $plugin_public, 'album_shortcode' );
		$this->loader->add_filter( 'foogallery_album_build_gallery_link', $plugin_public, 'get_gallery_link');
		$this->loader->add_filter( 'foogallery_build_attachment_html_caption', $plugin_public, 'foogallery_build_attachment_html_caption', 10, 3);


	}

	/**
	 * Register all of the hooks related to both the public-facing and admin functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_shared_hooks() {

		$plugin_shared = new Costume_Con_Archives_Shared( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_shared, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_shared, 'enqueue_scripts' );

		$this->loader->add_action( 'init', $plugin_shared, 'register_post_types' );
		$this->loader->add_action( 'init', $plugin_shared, 'register_taxonomies' );


	}

	/**
	 * Register all of the shortcodes on the init hook
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function register_shortcodes() {

		$this->loader->add_action( 'init', $this->loader, 'register_shortcodes' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Costume_Con_Archives_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}



//add_action('init','cca_update_galleries');
function cca_update_galleries() {
	$galleries = foogallery_get_all_galleries();
	foreach ( $galleries as $gallery ) {
		update_post_meta( $gallery->ID, FOOGALLERY_META_TEMPLATE, 'simple_portfolio' );
		$settings = get_post_meta( $gallery->ID, FOOGALLERY_META_SETTINGS, true );
		if ( is_array( $settings)) {
			$settings['simple_portfolio_thumbnail_link'] = 'image' ;
			$settings['simple_portfolio_lightbox'] = 'foobox' ;
			$settings['simple_portfolio_thumbnail_dimensions'] = array(
				'height' => 200,
				'width'  => 250,
				'crop'   => 1
			);
			update_post_meta( $gallery->ID, FOOGALLERY_META_SETTINGS, $settings );
		}



	}
}
