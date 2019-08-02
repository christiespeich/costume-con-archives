<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.mooberrydreams.com
 * @since      1.0.0
 *
 * @package    Costume_Con_Archives
 * @subpackage Costume_Con_Archives/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Costume_Con_Archives
 * @subpackage Costume_Con_Archives/public
 * @author     Christie <Speich>
 */
class Costume_Con_Archives_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;


	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/costume-con-archives-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/costume-con-archives-public.js', array( 'jquery' ), $this->version, false );

	}

	/*public function con_cpt_content( $content ) {
		return $this->con_cpt->content( $content );

	}*/

	public function content( $content ) {
		if ( is_main_query() && is_tax() ) {
			$taxonomy = get_query_var( 'taxonomy' );
			$path = COSTUME_CON_ARCHIVES_PLUGIN_DIR . 'includes/taxonomies/partials/';
			$filename =  $taxonomy . '-content.php';
			if ( !file_exists( $path . $filename ) ) {
				$filename = 'content.php';
			}
			ob_start();
			include $path . $filename;
			$content .= ob_get_clean();
		}
		echo $content;

	}

	public function album_shortcode( $atts, $content ) {
		// check for proper inputs
		$album_id = isset( $_GET['album']) ? intval( $_GET['album'] ) : 0;
		$gallery_id = isset( $_GET['gallery']) ? intval( $_GET['gallery'] ) : 0;

		if ( $album_id == 0 && $gallery_id == 0 ) {
			return $content;
		}

		if ( $album_id !== 0 ) {
			$album = FooGalleryAlbum::get_by_id($album_id);
			$name = $album->name;
			$shortcode = foogallery_build_album_shortcode( $album_id);
		}

		if ( $gallery_id !== 0 ) {
			$gallery = FooGallery::get_by_id( $gallery_id );
			$name = $gallery->name;
			$shortcode = foogallery_build_gallery_shortcode( $gallery_id );
		}
		$content .= '<h2>' . $name . '</h2>' . $shortcode;
		return do_shortcode($content);

	}


}
