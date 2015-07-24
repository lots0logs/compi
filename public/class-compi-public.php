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
 * The public-facing functionality of the plugin.
 *
 * Definition.
 *
 * @package    Compi
 * @subpackage Compi/public
 * @author     wpdots <dev@wpdots.com>
 */
class Compi_Public {

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
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->compi_options = $this->get_options_array();
		$this->public_dir      = plugin_dir_path( __FILE__ );
		$this->template_dir       = $this->public_dir . 'templates';
		$this->css_stylesheet     = plugins_url( '/css/compi-style.css', __FILE__ );
		$this->custom_script     = plugins_url( '/js/compi-custom.js', __FILE__ );

	}

	/**
	 * Get options array from database.
	 *
	 * @since    1.0.0
	 *
	 */
	private function get_options_array() {

		return get_option( 'dots_compi_options' ) ? get_option( 'dots_compi_options' ) : array();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {


		wp_enqueue_style( $this->plugin_name . 'style', $this->css_stylesheet, array('divi-style'), $this->version );

	}

	/**
	 * Register the scripts for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name . 'custom', $this->custom_script, array( 'jquery', 'divi-custom' ), $this->version, false );

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

		add_filter( $filter_name, array($this, 'do_template_override' ) );

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
	 * Override a theme template with our own.
	 *
	 * @since    1.0.0
	 *
	 * @param $template
	 *
	 * @return string
	 */
	public function do_template_override( $template ) {

		$current_filter =  current_filter();
		$this->write_log($current_filter);
		$template_name = str_replace( '_template', '', $current_filter );
		$template_name = ( $current_filter !== $template_name ) ? $template_name : '';

		if ( '' !== $template_name ) {
			return $this->template_dir . '/' . $template_name . '.php';
		}

		return $template;

	}

	/**
	 * Activate features that are enabled in our options array.
	 *
	 * @since    1.0.0
	 *
	 */
	public function maybe_activate_features() {

		$options = $this->get_options_array();

		if ( isset($options['tweaks_global_masonry']) && $options['tweaks_global_masonry'] == 1) {
			$this->add_template_override_filter('category');
		}

	}

}