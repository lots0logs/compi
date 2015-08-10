<?php
/*
 * compi.php
 *
 * Copyright © 2015 wpdots
 *
 * This file is part of Compi.
 *
 * Compi is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License,
 * or any later version.
 *
 * Compi is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * The following additional terms are in effect as per Section 7 of this license:
 *
 * The preservation of all legal notices and author attributions in
 * the material or in the Appropriate Legal Notices displayed
 * by works containing it is required.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


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
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       Compi
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
require plugin_dir_path( __FILE__ ) . '/includes/class-dots-compi.php';

/**
 * Begins execution of the plugin.
 * 
 * @since    1.0.0
 */
new Dots_Compi( '1.0.0' );