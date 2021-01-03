<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://thepixeltribe.com
 * @since             1.0.0
 * @package           Jsonapi
 *
 * @wordpress-plugin
 * Plugin Name:       jsonAPI
 * Plugin URI:        https://thepixeltribe.com
 * Description:       This plugin will parse the JSON response and will use it to build and display an HTML table that is linked to other user related features such as posts, Todo & albums.
 * Version:           1.0.0
 * Author:            Denis Bosire
 * Author URI:        https://thepixeltribe.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       jsonapi
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
define( 'JSONAPI_VERSION', '1.0.0' );

/**
 * Registers the autoloader for classes
 *
 * @author	Michiel Tramper - https://www.makeitworkpress.com
 * @todo	Make the autoloader comply with the coding standards of WordPress
 */
spl_autoload_register( function($classname) {

    $class     = str_replace( '\\', DIRECTORY_SEPARATOR, str_replace( '_', '-', strtolower($classname) ) );
    
    $file_path = plugin_dir_path( __DIR__ ) . DIRECTORY_SEPARATOR . $class . '.php';
    
    if ( file_exists( $file_path ) )
        require_once $file_path;
  
} );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-jsonapi-activator.php
 */
function activate_jsonapi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-jsonapi-activator.php';
	Jsonapi_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-jsonapi-deactivator.php
 */
function deactivate_jsonapi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-jsonapi-deactivator.php';
	Jsonapi_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_jsonapi' );
register_deactivation_hook( __FILE__, 'deactivate_jsonapi' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-jsonapi.php';
//require_once plugin_dir_url( __FILE__ ) . 'vendor/autoload.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_jsonapi() {

	$plugin = new Jsonapi();
	$plugin->run();

}
run_jsonapi();
