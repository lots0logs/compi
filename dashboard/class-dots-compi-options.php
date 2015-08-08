<?php

/**
 * Compi's settings/options that will be stored in database.
 *
 * @link       http://wpdots.com
 * @since      1.0.0
 *
 * @package    Compi
 * @subpackage Compi/dashboard
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


/**
 * Options array that contains all of the options available in Compi's Control Panel
 *
 * @package    Compi
 * @subpackage Compi/dashboard
 * @author     wpdots <dev@wpdots.com>
 */
class Compi_Options_Table {

	public static function get_dash_options() {

		return array(
				'general' => array(
						'title'    => __( 'General', 'Compi' ),
						'contents' => array(
								'option1_heading' => array(
										'type'     => 'option_heading',
										'title'    => __( 'Module Enhancements' ),
										'subtitle' => __( 'Various tweaks for the Builder\'s default modules.' )
								),
								'option1_switch'  => array(
										'type' => 'switch',
										'name' => 'module_enhancements'
								),
								'option2_heading' => array(
										'type'     => 'option_heading',
										'title'    => __( 'New Modules' ),
										'subtitle' => __( 'Exclusive new modules for the Builder.' )
								),
								'option2_switch'  => array(
										'type' => 'switch',
										'name' => 'new_modules'
								),
								'option_end'      => array(
										'type' => 'option_end'
								),
						),

				),
				'tweaks'  => array(
						'title'    => __( 'Theme Tweaks', 'Compi' ),
						'contents' => array(
								'option1_heading' => array(
										'type'     => 'option_heading',
										'title'    => __( 'Global Masonry Grid' ),
										'subtitle' => __( 'Use the Masonry grid layout on all category, archive, & index pages.' )
								),
								'option1_switch'  => array(
										'type' => 'switch',
										'name' => 'global_masonry'
								),
								'option1_end'      => array(
										'type' => 'option_end'
								),
								'option2_heading' => array(
										'type'     => 'option_heading',
										'title'    => __( 'Global Fullwidth' ),
										'subtitle' => __( 'Don\'t display the sidebar on category, archive, & index pages.' )
								),
								'option2_switch'  => array(
										'type' => 'switch',
										'name' => 'global_fullwidth'
								),
								'option2_end'      => array(
										'type' => 'option_end'
								),
						),
				),
				'support' => array(
						'title'    => __( 'Support', 'Compi' ),
						'contents' => array(),
				),
				'tools'   => array(
						'title'    => __( 'Tools', 'Compi' ),
						'contents' => array(),
				),
		);
	}
}