<?php
/*
 * class-dots-compi-dashboard.php
 *
 * Copyright © 2015 wpdots
 *
 * This file is part of Compi.
 *
 * Portions of the code in this file are based on code from
 * other open source products. Where applicable, the following applies:
 *
 * Copyright © 2015 Elegant Themes
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
 * The admin-specific functionality of the plugin.
 *
 * @property array dash_options_all
 * @package    Compi
 * @subpackage Compi/dashboard
 * @author     wpdots <dev@wpdots.com>
 */
class Dots_Compi_Dashboard {

	private static $_this;
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
	 * @param $options
	 */
	public function __construct( $plugin_name, $version, $options, $util ) {

		// Don't allow more than one instance of the class
		if ( isset( self::$_this ) ) {
			wp_die( sprintf( __( '%s is a singleton class and you cannot create a second instance.', 'dots_compi' ),
					get_class( $this ) )
			);
		}

		self::$_this = $this;

		$this->plugin_name        = $plugin_name;
		$this->version            = $version;
		$this->dashboard_dir      = plugin_dir_path( __FILE__ );
		$this->template_dir       = $this->dashboard_dir . 'templates/';
		$this->css_stylesheet     = plugins_url( '/css/compi-dashboard.css', __FILE__ );
		$this->css_mdl_stylesheet = plugins_url( '/css/material.prefixed.min.css', __FILE__ );
		//$this->css_mdl_stylesheet = '//storage.googleapis.com/code.getmdl.io/1.0.0/material.indigo-pink.min.css';
		$this->css_mdl_icons       = '//fonts.googleapis.com/icon?family=Material+Icons';
		$this->admin_mdl_script    = '//storage.googleapis.com/code.getmdl.io/1.0.0/material.min.js';
		$this->admin_script        = plugins_url( '/js/dashboard.js', __FILE__ );
		$this->post_actions_script = plugins_url( '/js/post-actions.js', __FILE__ );
		$this->compi_options       = $options;
		$this->protocol            = is_ssl() ? 'https' : 'http';
		$this->dash_options_all    = array();
		$this->features            = array();
		$this->conversion_util     = $util;
		$this->post_column_script  = false;


		$this->include_dash_options();
		$this->maybe_activate_features();

		/**
		 * ------------
		 * AJAX ACTIONS
		 * ------------
		 */
		add_action( 'wp_ajax_dots_compi_save_settings', array( $this, 'dots_compi_save_settings' ) );
		add_action( 'wp_ajax_dots_compi_generate_modal_warning', array( $this, 'generate_modal_warning' ) );
	}


	/**
	 * Update option in database.
	 *
	 * @since    1.0.0
	 *
	 * @param $update_array
	 */
	private function update_option( $update_array ) {

		$updated_options = array_merge( $this->compi_options, $update_array );
		update_option( 'dots_compi_options', $updated_options );
		$this->compi_options = $updated_options;
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
	 * Include options from options file. (Used to render dashboard).
	 *
	 * @since    1.0.0
	 */
	public function include_dash_options() {

		require_once( $this->dashboard_dir . 'class-dots-compi-options.php' );

		$this->dash_options_all = Compi_Options_Table::get_dash_options();

	}

	/**
	 * Check features that are enabled in our options array. (stored in database)
	 *
	 * @since    1.0.0
	 *
	 */
	public function check_for_enabled_features() {

		$options        = $this->compi_options;
		$this->features = array(
			'builder_conversion' => false,
		);

		if ( isset( $options['tools_builder_conversion'] ) && $options['tools_builder_conversion'] == 1 ) {
			$this->features['builder_conversion'] = true;
		}
	}

	/**
	 * Activate features that are enabled in our features array.
	 *
	 * @since    1.0.0
	 *
	 */
	public function maybe_activate_features() {

		$this->check_for_enabled_features();

		if ( true === $this->features['builder_conversion'] ) {
			add_action( 'wp_ajax_dots_compi_do_builder_conversion', array( $this->conversion_util, 'do_builder_conversion', ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_filter( 'manage_post_posts_columns', array( $this->conversion_util, 'add_conversion_utility_post_columns', ) );
			add_filter( 'manage_pages_columns', array( $this->conversion_util, 'add_conversion_utility_post_columns', ) );
			add_action( 'manage_post_posts_custom_column', array( $this->conversion_util, 'maybe_display_et_builder_status', ), 10, 2 );
			add_action( 'manage_page_posts_custom_column', array( $this->conversion_util, 'maybe_display_et_builder_status', ), 10, 2 );
		}
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

		$screen = get_current_screen();

		wp_enqueue_script( 'compi-mdl-js', $this->admin_mdl_script, array(), $this->version, false );
		wp_localize_script( 'compi-dashboard', 'compiSettings', array(
			'compi_nonce'      => wp_create_nonce( 'dots_compi_nonce' ),
			'ajaxurl'          => admin_url( 'admin-ajax.php', $this->protocol ),
			'shortcode_nonce'  => wp_create_nonce( 'dots_compi_generate_shortcode' ),
			'save_settings'    => wp_create_nonce( 'dots_compi_save_settings' ),
			'generate_warning' => wp_create_nonce( 'dots_compi_generate_warning' ),
		) );

		if ( 'admin.php' === $screen->parent_file ) {
			wp_enqueue_script( 'compi-dashboard', $this->admin_script, array( 'jquery', 'compi-mdl-js' ), $this->version, true );

		} elseif ( function_exists( 'et_pb_is_allowed' ) && ! $this->post_column_script && et_pb_is_allowed( 'divi_builder_control' ) ) {
			wp_enqueue_script( 'compi-post-actions', $this->post_actions_script, array(), $this->version, false );
			add_action( 'admin_action_compi_post_actions', array( $this->conversion_util, 'do_builder_conversion', ) );

			wp_localize_script( 'compi-post-actions', 'dots_compi', array(
			'nonce'      => wp_create_nonce( 'dots_compi_do_builder_conversion-nonce' ),
			'ajaxurl'          => admin_url( 'admin-ajax.php', $this->protocol ) ) );
		}

	}

	/**
	 * Output the dashboard HTML
	 *
	 * @since    1.0.0
	 */
	public function options_page() {

		$this->include_dash_options();
		$compi_options    = $this->compi_options;
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

		if ( wp_verify_nonce( $_REQUEST['save_settings_nonce'], 'dots_compi_save_settings' ) && $ajax_request ) {
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

		$dash_options_all = $this->dash_options_all;

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
											false,
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

		$this->write_log( $compi_options_temp );

		return $compi_options_temp;

	}


}