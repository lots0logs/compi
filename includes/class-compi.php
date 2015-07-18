<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://wpdots.com
 * @since      1.0.0
 *
 * @package    Compi
 * @subpackage Compi/includes
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
class Compi {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * The plugin's dashboard.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $dashboard The admin dashboard.
	 */
	protected $dashboard;

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

		$this->plugin_name = 'compi';
		$this->version     = '1.0.0';

		// Register activation hook.
		register_activation_hook( __FILE__, array( 'Compi', 'activation_hook' ) );

		// Register deactivation hook.
		register_deactivation_hook( __FILE__, array( 'Compi', 'deactivation_hook' ) );

		$this->load_dependencies();
		$this->set_locale();
		$this->define_dashboard_hooks();
		$this->define_public_hooks();

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
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-compi-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'dashboard/class-compi-dashboard.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-compi-public.php';

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Compi_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Compi_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		add_action( 'plugins_loaded', array( $plugin_i18n, 'load_plugin_textdomain' ) );

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
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {

		return $this->version;
	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_dashboard_hooks() {

		$this->dashboard = new Compi_Admin( $this->get_plugin_name(), $this->get_version() );
		
		add_action( 'admin_menu', array( $this->dashboard, 'setup_dashboard' ), 90 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Compi_Public( $this->get_plugin_name(), $this->get_version() );

		add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_scripts' ) );

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