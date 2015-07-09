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

		$this->plugin_name        = $plugin_name;
		$this->version            = $version;
		$this->dashboard_dir      = plugin_dir_path( __FILE__ );
		$this->template_dir       = $this->dashboard_dir . 'templates';
		$this->css_stylesheet     = plugins_url( '/css/compi-dashboard.css', __FILE__ );
		$this->css_mdl_stylesheet = '//storage.googleapis.com/code.getmdl.io/1.0.0/material.indigo-pink.min.css';
		$this->css_mdl_icons      = '//fonts.googleapis.com/icon?family=Material+Icons';
		$this->admin_mdl_script   = '//storage.googleapis.com/code.getmdl.io/1.0.0/material.min.js';
		$this->admin_script       = plugins_url( '/js/compi-dashboard.js', __FILE__ );
		$this->loader             = $loader;
		$this->compi_options = $this->get_options_array();

		$this->include_options();

	}

	/**
	 * Include options from options file.
	 *
	 * @since    1.0.0
	 */
	public function include_options() {

		require_once( $this->dashboard_dir . 'class-compi-options.php' );

		$include_options = new Compi_Options_Table();

		$this->dash_tabs                           = $include_options->dash_tabs;
		$this->enhancements_section_one_options    = $include_options->enhancements_section_one_options;
		$this->new_modules_section_one_options     = $include_options->new_modules_section_one_options;
		$this->builder_general_section_one_options = $include_options->builder_general_section_one_options;
		$this->theme_general_section_one_options   = $include_options->theme_general_section_one_options;
		$this->support_section_one_options         = $include_options->support_section_one_options;
		$this->header_import_options               = $include_options->header_import_options;
		$this->header_export_options               = $include_options->header_export_options;


	}

	/**
	 *
	 * Register our admin page and menu link
	 *
	 * @since    1.0.0
	 */
	public function setup_dashboard() {

		$menu_page = add_submenu_page( 'et_divi_options', __( 'Compi Settings', 'Compi' ), __( 'Compi Settings', 'Compi' ), 'manage_options', 'dots_compi_options', array(
			$this,
			'options_page',
		) );

		add_action( "admin_print_scripts-{$menu_page}", array( $this, 'enqueue_scripts' ) );
		add_action( "admin_print_styles-{$menu_page}", array( $this, 'enqueue_styles' ) );

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

		wp_enqueue_style( $this->plugin_name . 'mdl', $this->css_mdl_stylesheet, array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . 'mdl-icons', $this->css_mdl_icons, array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . 'styles', $this->css_stylesheet, array( $this->plugin_name . 'mdl' ), $this->version, 'all' );

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

		wp_enqueue_script( $this->plugin_name . 'mdl-js', $this->admin_mdl_script, array(), $this->version, false );
		wp_enqueue_script( $this->plugin_name, $this->admin_script, array( 'jquery' ), $this->version, false );

	}

	/**
	 * Output the dashboard HTML
	 *
	 * @since    1.0.0
	 */
	public function options_page() {

		$this->include_options();

		$dash_tabs                       = $this->dash_tabs;
		$enhancements_section_one_options    = $this->enhancements_section_one_options;
		$new_modules_section_one_options     = $this->new_modules_section_one_options;
		$builder_general_section_one_options = $this->builder_general_section_one_options;
		$theme_general_section_one_options   = $this->theme_general_section_one_options;
		$support_section_one_options         = $this->support_section_one_options;
		$header_import_options   = $this->header_import_options;
		$header_export_options   = $this->header_export_options;

		require_once( $this->template_dir . '/compi-dashboard-view.php' );


	}

}