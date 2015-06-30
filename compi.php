<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate Compi's information in the plugin
 * admin area. This file also includes all of the dependencies used by Compi,
 * registers the activation and deactivation functions, and defines a function
 * that starts Compi.
 *
 * @link              http://wpdots.com
 * @since             0.1.0
 * @package           Compi
 *
 * @wordpress-plugin
 * Plugin Name:       Compi
 * Plugin URI:        http://wpdots.com/plugins/compi
 * Description:       The perfect Divi companion.
 * Version:           0.1.0
 * Author:            wpdots
 * Author URI:        http://wpdots.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       compi
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-compi-activator.php
 */
function activate_compi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-compi-activator.php';
	Compi_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_compi' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-compi-deactivator.php
 */
function deactivate_compi() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-compi-deactivator.php';
	Compi_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_compi' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-compi.php';

/**
 * Begins execution of Compi.
 *
 * Since everything within the plugin is registered via hooks,
 * kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1.0
 */
function run_compi() {

	$plugin = new Compi();
	$plugin->run();

}
run_compi();