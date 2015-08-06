<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://wpdots.com
 * @since      1.0.0
 *
 * @package    Compi
 * @subpackage Compi/public
 */


/**
 * The main class for all public-facing functionality.
 *
 * Definition.
 *
 * @package    Compi
 * @subpackage Compi/public
 * @author     wpdots <dev@wpdots.com>
 */
class Compi_Public {

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

	public $global_fullwidth;
	public $global_masonry;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		// Don't allow more than one instance of the class
		if ( isset( self::$_this ) ) {
			wp_die( sprintf( __( '%s is a singleton class and you cannot create a second instance.', 'Compi' ),
					get_class( $this ) )
			);
		}

		self::$_this = $this;

		$this->plugin_name    = $plugin_name;
		$this->version        = $version;
		$this->compi_options  = static::get_options_array();
		$this->public_dir     = plugin_dir_path( __FILE__ );
		$this->template_dir   = $this->public_dir . 'templates';
		$this->css_stylesheet = plugins_url( '/css/compi-style.css', __FILE__ );
		$this->custom_script  = plugins_url( '/js/compi-custom.js', __FILE__ );

		$this->check_for_enabled_features();
		$this->maybe_activate_features();

	}

	/**
	 * Get options array from database.
	 *
	 * @since    1.0.0
	 *
	 */
	private static function get_options_array() {

		return get_option( 'dots_compi_options' ) ? get_option( 'dots_compi_options' ) : array();
	}

	/**
	 * Update options array in database.
	 *
	 * @param $update_array
	 */
	private function update_option( $update_array ) {

		$options         = static::get_options_array();
		$updated_options = array_merge( $options, $update_array );
		update_option( 'dots_compi_options', $updated_options );
	}

	/**
	 * Register the stylesheets for the public-facing features.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {


		wp_enqueue_style( $this->plugin_name . 'style', $this->css_stylesheet, array( 'divi-style' ), $this->version );

	}

	/**
	 * Register the scripts for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name . 'custom', $this->custom_script, array(
			'jquery',
			'divi-custom'
		), $this->version, false );

	}

	/**
	 * Add filter to override a template file.
	 *
	 * @since    1.0.0
	 *
	 * @param $template_type
	 */
	public function add_template_override_filter( $template_type ) {

			$filter_name = $template_type . '_template';

			add_filter( $filter_name, array( $this, 'do_template_override' ) );

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
	 * Override a theme template with our own. This is called by WordPress
	 * during the "do_template_override" action.
	 *
	 * @since    1.0.0
	 *
	 * @param $template
	 *
	 * @return string
	 */
	public function do_template_override( $template ) {

		global $dots_compi_sidebar;

		$this->check_for_enabled_features();
		$dots_compi_sidebar = ! $this->global_fullwidth;
		$current_filter     = current_filter();

		$template_name = str_replace( '_template', '', $current_filter );
		$template_name = ( $current_filter !== $template_name ) ? $template_name : '';

		if ( '' !== $template_name ) {
			return $this->template_dir . '/' . $template_name . '.php';
		}

		return $template;

	}


	/**
	 * Check features that are enabled in our options array.
	 *
	 * @since    1.0.0
	 *
	 */
	public function check_for_enabled_features() {

		$options = static::get_options_array();

		if ( isset( $options['tweaks_global_masonry'] ) && $options['tweaks_global_masonry'] == 1 ) {
			$this->global_masonry = true;
		} else {
			$this->global_masonry = false;
		}

		if ( isset( $options['tweaks_global_fullwidth'] ) && $options['tweaks_global_fullwidth'] == 1 ) {
			$this->global_fullwidth = true;
		} else {
			$this->global_fullwidth = false;
		}

	}

	/**
	 * Activate features that are enabled in our options array.
	 *
	 * @since    1.0.0
	 *
	 */
	public function maybe_activate_features() {

		if ( true === $this->global_masonry ) {
			$this->add_template_override_filter( 'index' );
			}
		if ( true === $this->global_fullwidth && false === $this->global_masonry ) {
			add_filter( 'body_class', array( $this, 'add_body_classes' ) );
		}

	}

	/**
	 * Add classes to body.
	 *
	 * @param $classes
	 *
	 * @return array
	 */
	public function add_body_classes( $classes ) {

		$this->check_for_enabled_features();

		if ( true === $this->global_fullwidth && false === $this->global_masonry ) {
			$classes[] = 'et_full_width_page';

		}
		if ( true === $this->global_fullwidth ) {
			$classes[] = 'dots_compi_archive_grid ';
		}

		return $classes;

	}

}