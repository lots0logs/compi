<?php
/**
 * Plugin Name First Tab Settings
 *
 * @since    1.0.0
 * @author wpdots
 * @category Admin
 * @package  Plugin Name
 * @license  GPL-2.0+
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Compi_Settings_First_Tab' ) ) {

/**
 * Compi_Settings_First_Tab
 */
class Compi_Settings_First_Tab extends Compi_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct() {
		$this->id    = 'tab_one';
		$this->label = __( 'First Tab', COMPI_TEXT_DOMAIN );

		add_filter( 'compi_settings_submenu_array',           array( $this, 'add_menu_page' ),     20 );
		add_filter( 'compi_settings_tabs_array',              array( $this, 'add_settings_page' ), 20 );
		add_action( 'compi_settings_' . $this->id,            array( $this, 'output' ) );
		add_action( 'compi_settings_save_' . $this->id,       array( $this, 'save' ) );
		add_action( 'compi_settings_start',                   array( $this, 'settings_top' ) );
		add_action( 'compi_settings_start_tab_' . $this->id,  array( $this, 'settings_top_this_tab_only' ) );
		add_action( 'compi_settings_finish',                  array( $this, 'settings_bottom' ) );
		add_action( 'compi_settings_finish_tab_' . $this->id, array( $this, 'settings_bottom_this_tab_only' ) );
	} // END __construct()

	/**
	 * Save settings
	 *
	 * @since  1.0.0
	 * @access public
	 * @global $current_tab
	 */
	public function save() {
		global $current_tab;

		$settings = $this->get_settings();

		Compi_Admin_Settings::save_fields( $settings, $current_tab );
	}

	/**
	 * Get settings array
	 *
	 * @since  1.0.0
	 * @access public
	 * @return array
	 */
	public function get_settings() {

		return apply_filters( 'compi_' . $this->id . '_settings', array(

			array(
				'title' 	=> __( 'Settings Title', COMPI_TEXT_DOMAIN ),
				'type' 		=> 'title',
				'desc' 		=> '',
				'id' 		=> $this->id . '_options'
			),

			array(
				'title' 	=> __( 'Subscriber Access', COMPI_TEXT_DOMAIN ),
				'desc' 		=> __( 'Prevent users from accessing WordPress admin.', COMPI_TEXT_DOMAIN ),
				'id' 		=> 'compi_lock_down_admin',
				'default'	=> 'no',
				'type' 		=> 'checkbox',
			),

			array(
				'title' 	=> __( 'Secure Content', COMPI_TEXT_DOMAIN ),
				'desc' 		=> __( 'Keep your site secure by forcing SSL (HTTPS) on site (an SSL Certificate is required).', COMPI_TEXT_DOMAIN ),
				'id' 		=> 'compi_force_ssl',
				'default'	=> 'no',
				'type' 		=> 'checkbox'
			),

			array(
				'title' 	=> __( 'Select Country', COMPI_TEXT_DOMAIN ),
				'desc' 		=> __( 'This gives you a list of countries. ', COMPI_TEXT_DOMAIN ),
				'id' 		=> 'compi_country_list',
				'css' 		=> 'min-width:350px;',
				'default'	=> 'GB',
				'type' 		=> 'single_select_country',
				'desc_tip'	=> true,
			),

			array(
				'title' 	=> __( 'Multi Select Countries', COMPI_TEXT_DOMAIN ),
				'desc' 		=> '',
				'id' 		=> 'compi_multi_countries',
				'css' 		=> 'min-width: 350px;',
				'default'	=> '',
				'type' 		=> 'multi_select_countries'
			),

			array(
				'title' 	=> __( 'Example Page', COMPI_TEXT_DOMAIN ),
				'desc' 		=> __( 'You can set pages that the plugin requires by having them installed and selected automatically when the plugin is installed.', COMPI_TEXT_DOMAIN ),
				'id' 		=> 'compi_example_page_id',
				'type' 		=> 'single_select_page',
				'default'	=> '',
				'class'		=> 'chosen_select_nostd',
				'css' 		=> 'min-width:300px;',
				'desc_tip'	=> true,
			),

			array(
				'title' 	=> __( 'Shortcode Example Page', COMPI_TEXT_DOMAIN ),
				'desc' 		=> __( 'This page has a shortcode applied when created by the plugin.', COMPI_TEXT_DOMAIN ),
				'id' 		=> 'compi_shortcode_page_id',
				'type' 		=> 'single_select_page',
				'default'	=> '',
				'class'		=> 'chosen_select_nostd',
				'css' 		=> 'min-width:300px;',
				'desc_tip'	=> true,
			),

			array(
				'title' 	=> __( 'Single Checkbox', COMPI_TEXT_DOMAIN ),
				'desc' 		=> __( 'Can come in handy to display more options.', COMPI_TEXT_DOMAIN ),
				'id' 		=> 'compi_checkbox',
				'default'	=> 'no',
				'type' 		=> 'checkbox'
			),

			array(
				'title' 	=> __( 'Single Input (Text) ', COMPI_TEXT_DOMAIN ),
				'desc' 		=> '',
				'id' 		=> 'compi_input_text',
				'default'	=> __( 'This admin setting can be hidden via the checkbox above.', COMPI_TEXT_DOMAIN ),
				'type' 		=> 'text',
				'css' 		=> 'min-width:300px;',
				'autoload' 	=> false
			),

			array(
				'title' 	=> __( 'Single Textarea ', COMPI_TEXT_DOMAIN ),
				'desc' 		=> '',
				'id' 		=> 'compi_input_textarea',
				'default'	=> __( 'You can allow the user to use this field to enter their own CSS or HTML code.', COMPI_TEXT_DOMAIN ),
				'type' 		=> 'textarea',
				'css' 		=> 'min-width:300px;',
				'autoload' 	=> false
			),

			array( 'type' => 'sectionend', 'id' => $this->id . '_options'),

		)); // End general settings
	}

}

} // end if class exists

return new Compi_Settings_First_Tab();

?>
