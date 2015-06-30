<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wpdots.com
 * @since      0.1.0
 *
 * @package    Compi
 * @subpackage Compi/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the compi, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Compi
 * @subpackage Compi/admin
 * @author     wpdots <dev@wpdots.com>
 */
class Compi_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $compi    The ID of this plugin.
	 */
	private $compi;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 * @param      string    $compi       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $compi, $version ) {

		$this->plugin_name = $compi;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Compi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Compi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/compi-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Compi_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Compi_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'postbox' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/compi-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    0.1.0
	 */
	public function add_plugin_admin_menu() {

		add_menu_page(
			__( 'Compi', $this->plugin_name ),
			__( 'Compi', $this->plugin_name ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_plugin_admin_page' )
			);

		$tabs = Compi_Settings_Definition::get_tabs();

		foreach ( $tabs as $tab_slug => $tab_title ) {

			add_submenu_page(
				$this->plugin_name,
				$tab_title,
				$tab_title,
				'manage_options',
				$this->plugin_name . '&tab=' . $tab_slug,
				array( $this, 'display_plugin_admin_page' )
				);
		}

		remove_submenu_page( $this->plugin_name, $this->plugin_name );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    0.1.0
	 * @param $links
	 * @return array Action links
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>'
				),
			$links
			);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    0.1.0
	 */
	public function display_plugin_admin_page() {

		$tabs = Compi_Settings_Definition::get_tabs();

		$default_tab = Compi_Settings_Definition::get_default_tab_slug();

		$active_tab = isset( $_GET[ 'tab' ] ) && array_key_exists( $_GET['tab'], $tabs ) ? $_GET[ 'tab' ] : $default_tab;

		include_once( 'partials/' . $this->plugin_name . '-admin-display.php' );

	}
}
