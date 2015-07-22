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
	 * @var      string $dashboard The admin dashboard.
	 */
	public $dashboard;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $version ) {

		// Set plugin's name (and slug).
		$this->plugin_name = 'compi';

		// Set plugin's version.
		$this->version     = $version;

		$this->compi_options = $this->get_options_array();

		// Register activation hook.
		register_activation_hook( __FILE__, array( 'Compi', 'activation_hook' ) );

		// Register deactivation hook.
		register_deactivation_hook( __FILE__, array( 'Compi', 'deactivation_hook' ) );

		// Load all plugin dependencies.
		$this->load_dependencies();

		// Set localization.
		$this->set_locale();

		// Define dashboard hooks.
		$this->define_dashboard_hooks();

		// Define public hooks.
		$this->define_public_hooks();

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
		$plugin_i18n->set_domain( $this->plugin_name );

		add_action( 'plugins_loaded', array( $plugin_i18n, 'load_plugin_textdomain' ) );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_dashboard_hooks() {

		$this->dashboard = new Compi_Admin( $this->plugin_name, $this->version, $this->compi_options );

		add_action( 'admin_init', array( $this->dashboard, 'register_settings' ) );
		add_action( 'admin_menu', array( $this->dashboard, 'setup_dashboard' ), 90 );
		add_action( 'wp_ajax_ajax_save_settings', array( $this->dashboard, 'ajax_save_settings' ) );
		// Generates warning messages
		add_action( 'wp_ajax_generate_modal_warning', array( $this->dashboard, 'generate_modal_warning' ) );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Compi_Public( $this->plugin_name, $this->version, $this->compi_options );

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