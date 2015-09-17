<?php
/*
 * class-dots-compi-options.php
 *
 * Copyright © 2015 wpdots
 *
 * This file is part of Compi.
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

	/**
	 * @return array
	 */
	public static function get_dash_options() {

		return array(
			'general' => array(
				'title'    => __( 'General', 'Compi' ),
				'contents' => array(
					'option1_heading' => array(
						'type'     => 'option_heading',
						'title'    => __( 'Module Enhancements' ),
						'subtitle' => __( 'Various tweaks for the Builder\'s default modules.' ),
					),
					'option1_switch'  => array(
						'type' => 'switch',
						'name' => 'module_enhancements',
					),
					'option2_heading' => array(
						'type'     => 'option_heading',
						'title'    => __( 'New Modules' ),
						'subtitle' => __( 'Exclusive new modules for the Builder.' ),
					),
					'option2_switch'  => array(
						'type' => 'switch',
						'name' => 'new_modules',
					),
					'option_end'      => array(
						'type' => 'option_end',
					),
				),

			),
			'tweaks'  => array(
				'title'    => __( 'Theme Tweaks', 'Compi' ),
				'contents' => array(
					'option1_heading' => array(
						'type'     => 'option_heading',
						'title'    => __( 'Global Masonry Grid' ),
						'subtitle' => __( 'Use the Masonry grid layout on all category, archive, & index pages.' ),
					),
					'option1_switch'  => array(
						'type' => 'switch',
						'name' => 'global_masonry',
					),
					'option1_end'     => array(
						'type' => 'option_end',
					),
					'option2_heading' => array(
						'type'     => 'option_heading',
						'title'    => __( 'Global Fullwidth' ),
						'subtitle' => __( 'Don\'t display the sidebar on category, archive, & index pages.' ),
					),
					'option2_switch'  => array(
						'type' => 'switch',
						'name' => 'global_fullwidth',
					),
					'option2_end'     => array(
						'type' => 'option_end',
					),
				),
			),
			'support' => array(
				'title'    => __( 'Support', 'Compi' ),
				'contents' => array(),
			),
			'tools'   => array(
				'title'    => __( 'Tools', 'Compi' ),
				'contents' => array(
					'option1_heading'     => array(
						'type'     => 'option_heading',
						'title'    => __( 'Elegant Builder -> Divi Builder' ),
						'subtitle' => __( 'Utility that allows you to convert post and page layouts from Elegant Builder to the Divi Builder' ),
					),
					'option1_switch'      => array(
						'type' => 'switch',
						'name' => 'builder_conversion',
					),
					'option1_table_start' => array(
						'type' => 'table_start',
					),
					'option1_table_row'   => array(
						'type' => 'table_row',
					),
					'option1_table_end'   => array(
						'type' => 'table_end',
					),
				),
			),
		);
	}
}