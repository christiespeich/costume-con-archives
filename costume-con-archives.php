<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.mooberrydreams.com
 * @since             1.0.0
 * @package           Costume_Con_Archives
 *
 * @wordpress-plugin
 * Plugin Name:       Costume Con Archives
 * Plugin URI:        http://www.mooberrydreams.com
 * Description:       This plugin is what makes the magic of the CCA!
 * Version:           1.0.0
 * Author:            Christie Speich
 * Author URI:        http://www.mooberrydreams.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       costume-con-archives
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'COSTUME_CON_ARCHIVES_VERSION', '1.0.0' );
define( 'COSTUME_CON_ARCHIVES_VERSION_KEY', 'cca_plugin_version' );
define( 'COSTUME_CON_ARCHIVES_CON_CPT', 'cca_con');
define( 'COSTUME_CON_ARCHIVES_PERSON_TAX', 'cca_person');

if ( ! defined( 'COSTUME_CON_ARCHIVES_PLUGIN_DIR' ) ) {
	define( 'COSTUME_CON_ARCHIVES_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

// Plugin Folder URL
if ( ! defined( 'COSTUME_CON_ARCHIVES_PLUGIN_URL' ) ) {
	define( 'COSTUME_CON_ARCHIVES_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// Plugin Root File
if ( ! defined( 'COSTUME_CON_ARCHIVES_PLUGIN_FILE' ) ) {
	define( 'COSTUME_CON_ARCHIVES_PLUGIN_FILE', __FILE__ );
}


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-costume-con-archives-activator.php
 */
function activate_costume_con_archives() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-costume-con-archives-activator.php';
	Costume_Con_Archives_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-costume-con-archives-deactivator.php
 */
function deactivate_costume_con_archives() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-costume-con-archives-deactivator.php';
	Costume_Con_Archives_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_costume_con_archives' );
register_deactivation_hook( __FILE__, 'deactivate_costume_con_archives' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-costume-con-archives.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_costume_con_archives() {

	$plugin = new Costume_Con_Archives();
	$plugin->run();

}
run_costume_con_archives();
