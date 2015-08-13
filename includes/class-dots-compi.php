<?php
/*
 * class-dots-compi.php
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
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of Compi as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Compi
 * @subpackage Compi/includes
 * @author     wpdots <dev@wpdots.com>
 */
class Dots_Compi {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	public $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @var      string $version The current version of the plugin.
	 */
	public $version;

	/**
	 * The plugin's dashboard.
	 *
	 * @since    1.0.0
	 * @var      Dots_Compi_Dashboard $plugin_dashboard The admin dashboard.
	 */
	protected $plugin_dashboard;
	protected $plugin_public;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 *
	 * @param $version
	 */
	public function __construct( $version ) {

		// Set plugin's name (and slug).
		$this->plugin_name = 'compi';

		// Set plugin's version.
		$this->version     = $version;

		$this->compi_options = static::get_options_array();

		// Register activation hook.
		register_activation_hook( __FILE__, array( 'Dots_Compi', 'activation_hook' ) );

		// Register deactivation hook.
		register_deactivation_hook( __FILE__, array( 'Dots_Compi', 'deactivation_hook' ) );

		// Call activation hook in case we've been updated manually.
		$this->activation_hook();

		// Load all plugin dependencies.
		$this->load_dependencies();

		// Set localization.
		$this->set_locale();

		// Initialize dashboard.
		add_action( 'plugins_loaded', array( $this, 'init_dashboard' ) );

		// Initialize frontend features.
		$action_hook = is_admin() ? 'wp_loaded' : 'wp';
		add_action( $action_hook, array( $this, 'init_public_facing_features' ), 99 );

	}

	/**
	 *
	 * Get our options array from database
	 *
	 * @since    1.0.0
	 */
	public static function get_options_array() {

		return get_option( 'dots_compi_options' ) ? get_option( 'dots_compi_options' ) : array();

	}

	/**
	 * Load the required dependencies for Compi.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Compi_Loader. Orchestrates the hooks of the plugin.
	 * - Compi_i18n. Defines internationalization functionality.
	 * - Compi_Admin. Defines all hooks for the admin area.
	 * - Compi_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/dashboard/class-dots-compi-dashboard.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . '/public/class-dots-compi-public.php';

	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$domain = 'dots_' . $this->plugin_name;

		load_plugin_textdomain(
			$domain,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function init_dashboard() {

		$this->plugin_dashboard = new Dots_Compi_Dashboard( $this->plugin_name, $this->version, $this->compi_options );

		add_action( 'admin_init', array( $this->plugin_dashboard, 'register_settings' ) );
		add_action( 'admin_menu', array( $this->plugin_dashboard, 'setup_dashboard' ), 90 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function init_public_facing_features() {

		$this->plugin_public = new Dots_Compi_Public( $this->plugin_name, $this->version, $this->compi_options );

		add_action( 'wp_enqueue_scripts', array( $this->plugin_public, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this->plugin_public, 'enqueue_scripts' ) );

	}
	
	/**
	 * Runs on plugin activation.
	 */
	public function activation_hook() {

	}

	/**
	 * Runs on plugin deactivation.
	 */
	public function deactivation_hook() {
		
	}

}