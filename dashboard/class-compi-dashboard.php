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
	 * @param      string $version The version of this plugin.
	 * @param $options
	 */
	public function __construct( $plugin_name, $version, $options ) {

		$this->plugin_name        = $plugin_name;
		$this->version            = $version;
		$this->dashboard_dir      = plugin_dir_path( __FILE__ );
		$this->template_dir       = $this->dashboard_dir . 'templates';
		$this->css_stylesheet     = plugins_url( '/css/compi-dashboard.css', __FILE__ );
		$this->css_mdl_stylesheet = plugins_url( '/css/material.prefixed.min.css', __FILE__ );
		//$this->css_mdl_stylesheet = '//storage.googleapis.com/code.getmdl.io/1.0.0/material.indigo-pink.min.css';
		$this->css_mdl_icons    = '//fonts.googleapis.com/icon?family=Material+Icons';
		$this->admin_mdl_script = '//storage.googleapis.com/code.getmdl.io/1.0.0/material.min.js';
		$this->admin_script     = plugins_url( '/js/compi-dashboard.js', __FILE__ );
		$this->compi_options    = $options;
		$this->protocol = is_ssl() ? 'https' : 'http';

		$this->include_options();

	}

	/**
	 * Update option in database.
	 *
	 * @since    1.0.0
	 *
	 * @param $update_array
	 */
	private function update_option( $update_array ) {

		$compi_options = $this->compi_options;
		$updated_options = array_merge( $compi_options, $update_array );
		update_option( 'dots_compi_options', $updated_options );
	}


	/**
	 * Include options from options file.
	 *
	 * @since    1.0.0
	 */
	public function include_options() {

		require_once( $this->dashboard_dir . 'class-compi-options.php' );

		$include_options = new Compi_Options_Table();

		$this->dash_tabs             = $include_options->dash_tabs;
		$this->general_first_options = $include_options->general_first_options;
		$this->tweaks_first_options  = $include_options->tweaks_first_options;
		$this->support_first_options = $include_options->support_first_options;
		$this->tools_first_options   = $include_options->tools_first_options;


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
		add_action( 'wp_ajax_ajax_save_settings', array( $this, 'ajax_save_settings' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		// Generates warning messages
		add_action( 'wp_ajax_generate_modal_warning', array( $this, 'generate_modal_warning' ) );
	}



	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( 'compi-mdl', $this->css_mdl_stylesheet, array(), $this->version, 'all' );
		wp_enqueue_style( 'compi-mdl-icons', $this->css_mdl_icons, array( 'compi-mdl' ), $this->version, 'all' );
		wp_enqueue_style( 'compi-styles', $this->css_stylesheet, array( 'compi-mdl' ), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'compi-mdl-js', $this->admin_mdl_script, array(), $this->version, false );
		wp_enqueue_script( 'compi-dashboard', $this->admin_script, array( 'jquery', 'compi-mdl-js' ), $this->version, false );

		wp_localize_script( 'compi-dashboard', 'compiSettings', array(
			'compi_nonce' => wp_create_nonce( 'compi_nonce' ),
			'ajaxurl'       => admin_url( 'admin-ajax.php', $this->protocol ),
			'shortcode_nonce' => wp_create_nonce( 'generate_shortcode' ),
			'save_settings' => wp_create_nonce( 'save_settings' ),
			'generate_warning' => wp_create_nonce( 'generate_warning' ),
		) );

	}

	/**
	 * Output the dashboard HTML
	 *
	 * @since    1.0.0
	 */
	public function options_page() {

		$this->include_options();

		$dash_tabs             = $this->dash_tabs;
		$general_first_options = $this->general_first_options;
		$tweaks_first_options  = $this->tweaks_first_options;
		$support_first_options = $this->support_first_options;
		$tools_first_options   = $this->tools_first_options;

		require_once( $this->template_dir . '/compi-dashboard-view.php' );


	}

	/**
	 * Register options group with Settings API
	 *
	 * @since    1.0.0
	 *
	 * @internal param array $options
	 */
	public function register_settings() {
		register_setting( 'dots_compi_settings_group', 'compi_settings' );
	}

	/**
	 * Receive settings from ajax request
	 *
	 * @since    1.0.0
	 *
	 * @param array $options
	 */
	public function ajax_save_settings( array $options ) {

		wp_verify_nonce( $_POST['save_settings_nonce'], 'save_settings' );
		$options = $_POST['options'];

		$error_message = $this->process_and_update_options( $options );

		die( $error_message );
	}

	/**
	 * Generates modal warning window for internal messages. Works via php or via Ajax
	 * Ok_link could be a link to particular tab in dashboard, external link or empty
	 *
	 * @since    1.0.0
	 *
	 * @internal param array $options
	 *
	 * @param string $message
	 * @param string $ok_link
	 * @param bool $hide_close
	 *
	 * @return string
	 */
	public function generate_modal_warning( $message = '', $ok_link = '#', $hide_close = false ) {
		$ajax_request = isset( $_POST[ 'message' ] ) ? true : false;
		if ( true === $ajax_request ){
			wp_verify_nonce( $_POST['generate_warning_nonce'] , 'generate_warning' );
		}
		$message = isset( $_POST[ 'message' ] ) ? sanitize_text_field( $_POST[ 'message' ] ) : sanitize_text_field( $message );
		$ok_link = isset( $_POST[ 'ok_link' ] ) ? $_POST[ 'ok_link' ] : $ok_link;
		$hide_close = isset( $_POST[ 'hide_close' ] ) ? (bool) $_POST[ 'hide_close' ] : (bool) $hide_close;
		$result = sprintf(
			'<div class="et_social_networks_modal et_social_warning">
				<div class="et_social_inner_container">
					<div class="et_social_modal_header">%4$s</div>
					<div class="social_icons_container">
						%1$s
					</div>
					<div class="et_social_modal_footer"><a href="%3$s" class="et_social_ok">%2$s</a></div>
				</div>
			</div>',
			esc_html( $message ),
			esc_html__( 'Ok', 'Monarch' ),
			esc_url( $ok_link ),
			false === $hide_close ? '<span class="et_social_close"></span>' : ''
		);
		if ( $ajax_request ){
			echo $result;
			die;
		} else {
			return $result;
		}
	}

	/**
	 * Process and update options
	 *
	 * @since    1.0.0
	 *
	 * @param array $options
	 *
	 * @return string
	 */
	private function process_and_update_options( array $options ) {

		$compi_options         = $this->compi_options;
		$dash_tabs             = $this->dash_tabs;
		$general_first_options = $this->general_first_options;
		$tweaks_first_options  = $this->tweaks_first_options;
		$support_first_options = $this->support_first_options;
		$tools_first_options   = $this->tools_first_options;

		$error_message = '';
		if ( ! is_array( $options ) ) {
			$processed_array = str_replace( array( '%5B', '%5D' ), array( '[', ']' ), $options );
			parse_str( $processed_array, $output );
		}
		if ( isset( $dots_tabs ) ) {
			foreach ( $dots_tabs as $tab => $value ) {
				$current_section = $tab;

				if ( isset( $value['contents'] ) ) {
					foreach ( $value['contents'] as $key => $value ) {
						$options_prefix = $current_section . '_' . $key;
						$options_array  = ${$current_section . '_' . $key . '_options'};

						if ( isset( $options_array ) ) {
							foreach ( $options_array as $option ) {

								if ( isset( $option['name'] ) ) {
									$current_option_name = $options_prefix . '_' . $option['name'];
								}

								switch ( $option['type'] ) {

									case 'switch':
										if ( isset( $output['dots_'][ $current_option_name ] ) ) {
											$compi_options_temp[ $current_option_name ] = in_array( $output['dots_'][ $current_option_name ], array(
												'1',
												false
											) )
												? sanitize_text_field( $output['dots_'][ $current_option_name ] )
												: false;
										} else {
											$compi_options_temp[ $current_option_name ] = false;
										}
										break;

								} // end switch
							} // end foreach( $options_array as $option )
						} //if ( isset( $options_array ) )
					} // end foreach( $value[ 'contents' ] as $key => $value )
				} // end if ( isset( $value[ 'contents' ] ) )
			} // end foreach ( $dots_tabs as $tab => $value )
		} //end if ( isset( $dots_tabs ) )

		$this->update_option( $compi_options_temp );

		return $error_message;

	}
}