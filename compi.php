<?php

/**
 * Compi's bootstrap file
 *
 * This file is read by WordPress to generate Compi's information in the plugin
 * admin area. This file also includes all of the dependencies used by Compi,
 * registers the activation and deactivation functions, and defines a function
 * that starts Compi.
 *
 * @link              http://wpdots.com/
 * @since             1.0.0
 * @package           Compi
 *
 * @wordpress-plugin
 * Plugin Name:       Compi
 * Plugin URI:        http://wpdots.com/plugins/compi
 * Description:       The best WordPress themes can be even better with the right companion.
 * Version:           1.0.0
 * Author:            wpdots
 * Author URI:        http://wpdots.com/
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
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-compi.php';

/**
 * Begins execution of the plugin.
 * 
 * @since    1.0.0
 */
new Compi( '1.0.0' );