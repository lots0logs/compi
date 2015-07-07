<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wpdots.com
 * @since      1.0.0
 *
 * @package    Compi
 * @subpackage Compi/dashboard
 */


/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Compi
 * @subpackage Compi/dashboard
 * @author     wpdots <dev@wpdots.com>
 */
class Compi_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version     The version of this plugin.
	 * @param             $loader
	 */
	public function __construct( $plugin_name, $version, $loader ) {

		$this->plugin_name   = $plugin_name;
		$this->version       = $version;
		$this->dashboard_dir = plugin_dir_path( __FILE__ );
		$this->template_dir  = $this->dashboard_dir . 'dashboard/templates';
		$this->css_styleheet = $this->dashboard_dir . 'dashboard/css/compi-dashboard.css';
		$this->admin_script  = $this->dashboard_dir . 'dashboard/js/compi-dashboard.js';
		$this->loader        = $loader;

	}

	/**
	 * Register our admin page and menu link
	 *
	 * @since    1.0.0
	 */
	public function setup_dashboard() {

		$menu_page = add_submenu_page( 'et_divi_options', __( 'Compi Settings', 'Compi' ), __( 'Compi Settings', 'Compi' ), 'manage_options', 'dots_compi_options', array(
			$this,
			'options_page',
		) );

		$this->loader->add_action( "admin_print_scripts-{$menu_page}", $this, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_styles' );

	}


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, $this->css_styleheet, array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, $this->admin_script, array( 'jquery' ), $this->version, false );

	}

	/**
	 * Include options from options file.
	 *
	 * @since    1.0.0
	 */
	public function include_options() {

		require_once( $this->dashboard_dir . 'class-compi-options.php' );

		$include_options = new Compi_Options_Table();

		$this->dash_sections         = $include_options->dash_sections;
		$this->builder_options       = $include_options->builder_options;
		$this->theme_options         = $include_options->theme_options;
		$this->plugin_options        = $include_options->plugin_options;
		$this->header_import_options = $include_options->header_import_options;
		$this->header_export_options = $include_options->header_export_options;


	}

	/**
	 * Output the dashboard HTML
	 *
	 * @since    1.0.0
	 */
	public function options_page() {

		$dash_sections         = $this->dash_sections;
		$builder_options       = $this->builder_options;
		$theme_options         = $this->theme_options;
		$plugin_options        = $this->plugin_options;
		$header_import_options = $this->header_import_options;
		$header_export_options = $this->header_export_options;

		require_once( $this->template_dir . '/compi-dashboard-view.php' );


	}

}