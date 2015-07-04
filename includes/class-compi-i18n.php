<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for Compi
 * so that it is ready for translation.
 *
 * @link       http://wpdots.com
 * @since      1.0.0
 *
 * @package    Compi
 * @subpackage Compi/includes
 */


/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for Compi
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Compi
 * @subpackage Compi/includes
 * @author     wpdots <dev@wpdots.com>
 */
class Compi_i18n {

	/**
	 * The domain specified for Compi.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $domain The domain identifier for Compi.
	 */
	private $domain;

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			$this->domain,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

	/**
	 * Set the domain equal to that of the specified domain.
	 *
	 * @since    1.0.0
	 *
	 * @param    string $domain The domain that represents the locale of Compi.
	 */
	public function set_domain( $domain ) {

		$this->domain = $domain;
	}

}