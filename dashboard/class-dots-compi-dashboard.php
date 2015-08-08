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
 * @property array dash_options_all
 * @package    Compi
 * @subpackage Compi/dashboard
 * @author     wpdots <dev@wpdots.com>
 */
class Compi_Dashboard {

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
	 *
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name        = $plugin_name;
		$this->version            = $version;
		$this->dashboard_dir      = plugin_dir_path( __FILE__ );
		$this->template_dir       = $this->dashboard_dir . 'templates/';
		$this->css_stylesheet     = plugins_url( '/css/compi-dashboard.css', __FILE__ );
		$this->css_mdl_stylesheet = plugins_url( '/css/material.prefixed.min.css', __FILE__ );
		//$this->css_mdl_stylesheet = '//storage.googleapis.com/code.getmdl.io/1.0.0/material.indigo-pink.min.css';
		$this->css_mdl_icons    = '//fonts.googleapis.com/icon?family=Material+Icons';
		$this->admin_mdl_script = '//storage.googleapis.com/code.getmdl.io/1.0.0/material.min.js';
		$this->admin_script     = plugins_url( '/js/compi-dashboard.js', __FILE__ );
		$this->compi_options    = $this->get_options_array();
		$this->protocol         = is_ssl() ? 'https' : 'http';
		$this->dash_options_all = array();

		$this->include_options();

		/**
		 * ------------
		 * AJAX ACTIONS
		 * ------------
		 */		
		add_action( 'wp_ajax_dots_compi_save_settings', array( $this, 'dots_compi_save_settings' ) );
		add_action( 'wp_ajax_dots_compi_generate_modal_warning', array( $this, 'generate_modal_warning' ) );
	}

	/**
	 * Get options array from database.
	 *
	 * @since    1.0.0
	 *
	 */
	public function get_options_array() {

		return get_option( 'dots_compi_options' ) ? get_option( 'dots_compi_options' ) : array();
	}

	/**
	 * Update option in database.
	 *
	 * @since    1.0.0
	 *
	 * @param $update_array
	 */
	private function update_option( $update_array ) {

		$compi_options   = $this->get_options_array();
		$updated_options = array_merge( $compi_options, $update_array );
		update_option( 'dots_compi_options', $updated_options );
		//$this->write_log( array( 'UPDATE OPTION', $compi_options, $updated_options ) );
	}

	/**
	 * Write to the debug log.
	 *
	 * @since    1.0.0
	 *
	 * @param $log
	 *
	 */
	public function write_log( $log ) {


			if ( is_array( $log ) || is_object( $log ) ) {
				error_log( print_r( $log, true ) );
			} else {
				error_log( $log );
			}
	}


	/**
	 * Include options from options file.
	 *
	 * @since    1.0.0
	 */
	public function include_options() {

		require_once( $this->dashboard_dir . 'class-dots-compi-options.php' );

		$this->dash_options_all = Compi_Options_Table::get_dash_options();

	}

	/**
	 *
	 * Register our admin page and menu link
	 *
	 * @since    1.0.0
	 */
	public function setup_dashboard() {

		$menu_page = add_submenu_page( 'et_divi_options',
				__( 'Compi Settings', 'Compi' ),
				__( 'Compi Settings', 'Compi' ),
				'manage_options',
				'dots_compi_options',
				array( $this, 'options_page' )
		);

		add_action( "admin_print_scripts-{$menu_page}", array( $this, 'enqueue_scripts' ) );
		add_action( "admin_print_styles-{$menu_page}", array( $this, 'enqueue_styles' ) );

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
		wp_enqueue_script( 'compi-dashboard', $this->admin_script, array( 'jquery', 'compi-mdl-js' ), $this->version, true );

		wp_localize_script( 'compi-dashboard', 'compiSettings', array(
			'compi_nonce'      => wp_create_nonce( 'dots_compi_nonce' ),
			'ajaxurl'          => admin_url( 'admin-ajax.php', $this->protocol ),
			'shortcode_nonce'  => wp_create_nonce( 'dots_compi_generate_shortcode' ),
			'save_settings'    => wp_create_nonce( 'dots_compi_save_settings' ),
			'generate_warning' => wp_create_nonce( 'dots_compi_generate_warning' ),
		) );

	}

	/**
	 * Output the dashboard HTML
	 *
	 * @since    1.0.0
	 */
	public function options_page() {

		$this->include_options();
		$compi_options = $this->get_options_array();
		$dash_options_all = $this->dash_options_all;

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

		register_setting( 'dots_compi_settings_group', 'dots_compi_options' );
	}

	/**
	 * Receive settings from ajax request
	 *
	 * @since    1.0.0
	 *
	 */
	public function dots_compi_save_settings() {

		$ajax_request = isset( $_REQUEST['options'] ) ? true : false;

		if( wp_verify_nonce( $_REQUEST['save_settings_nonce'], 'dots_compi_save_settings' ) && $ajax_request ) {
			parse_str( $_REQUEST['options'], $options );
			$this->process_and_update_options( $options );
		}

		wp_die();
	}

	/**
	 * Generates modal warning window for internal messages. Works via php or via Ajax
	 * Ok_link could be a link to particular tab in dashboard, external link or empty
	 *
	 * @since    1.0.0
	 *
	 * @param string $message
	 * @param string $ok_link
	 * @param bool $hide_close
	 *
	 * @return string
	 */
	public function generate_modal_warning( $message = '', $ok_link = '#', $hide_close = false ) {

		$ajax_request = isset( $_POST['message'] ) ? true : false;
		if ( true === $ajax_request ) {
			wp_verify_nonce( $_POST['generate_warning_nonce'], 'generate_warning' );
		}
		$message    = isset( $_POST['message'] ) ? sanitize_text_field( $_POST['message'] ) : sanitize_text_field( $message );
		$ok_link    = isset( $_POST['ok_link'] ) ? $_POST['ok_link'] : $ok_link;
		$hide_close = isset( $_POST['hide_close'] ) ? (bool) $_POST['hide_close'] : (bool) $hide_close;
		$result     = sprintf(
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
		if ( $ajax_request ) {
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
	private function process_and_update_options( $options ) {

		$dash_options_all = Compi_Options_Table::get_dash_options();

		$error_message = '';

		//$this->write_log( array( 'PROCESS AND UPDATE OPTIONS', $options ) );
		if ( ! is_array( $options ) ) {
			$processed_array = str_replace( array( '%5B', '%5D' ), array( '[', ']' ), $options );
			parse_str( $processed_array, $options );
			//$this->write_log( array( 'PROCESS AND UPDATE OPTIONS', $options ) );
		}

		if ( isset( $dash_options_all ) ) {
			foreach ( $dash_options_all as $tab_name => $tab ) {
				$current_section = $tab_name;

				if ( isset( $tab['contents'] ) ) {
					foreach ( $tab['contents'] as $option_item => $item_properties ) {
						$options_prefix = $current_section;
						$option         = $item_properties;
						//$this->write_log( array( 'PROCESS AND UPDATE OPTIONS', $options_prefix, $option ) );

						if ( isset( $option ) ) {

							$current_option_name = '';
							if ( isset( $option['name'] ) ) {
								$current_option_name = $options_prefix . '_' . $option['name'];
							}

							switch ( $option['type'] ) {

								case 'switch':
									if ( isset( $options['dots_compi'][ $current_option_name ] ) ) {
										$compi_options_temp[ $current_option_name ] = in_array( $options['dots_compi'][ $current_option_name ], array(
												'1',
												false
										) )
												? sanitize_text_field( $options['dots_compi'][ $current_option_name ] )
												: false;
									} else {
										$compi_options_temp[ $current_option_name ] = false;
									}
									break;

							} // end switch
						} //if ( isset( $option ) )
					} // end ( $tab['contents'] as $option_item => $item_properties )
				} // end if ( isset( $tab[ 'contents' ] ) )
			} // end foreach ( $dash_options_all as $tab_name => $tab )
		} //end if ( isset($dash_options_all ) )

		$this->update_option( $compi_options_temp );

		$this->write_log($compi_options_temp);

		return $compi_options_temp;

	}


}