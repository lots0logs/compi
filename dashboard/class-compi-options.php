<?php

/**
 * Compi's settings/options that will be stored in databased.
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

	public function __construct() {

		$this->dash_tabs = array(
			'general' => array(
				'title'    => __( 'General', 'Compi' ),
				'contents' => array(
					'section_one' => __( 'Section One', 'Compi' ),
				),

			),
			'tweaks'  => array(
				'title'    => __( 'Theme Tweaks', 'Compi' ),
				'contents' => array(
					'section_one' => __( 'Section One', 'Compi' ),
				),
			),
			'support' => array(
				'title'    => __( 'Support', 'Compi' ),
				'contents' => array(
					'section_one' => __( 'Section One', 'Compi' ),
				),
			),
			'header'  => array(
				'contents' => array(
					'import' => __( 'Import', 'Compi' ),
					'export' => __( 'Export', 'Compi' ),
				),
			),
		);

		$this->dash_options_all = array(
			'general_tab'  => array(
				'option_heading' => array(
					'type'     => 'section_start',
					'title'    => 'Module Enhancements',
					'subtitle' => 'Various tweaks for the Builder\'s default modules.'
				),
				'option_switch'  => array(
					'type' => 'switch',
				    'name' => 'module_enhancements'
				),
				'option2_heading' => array(
					'type'     => 'section_start',
					'title'    => 'New Modules',
					'subtitle' => 'Exclusive new modules for the Builder.'
				),
				'option2_switch'  => array(
					'type' => 'switch',
					'name' => 'new_modules'
				),
				'section_end'  => array(
					'type' => 'section_end'
				),
			),
			'tweaks_tab'   => array(),
			'support_tab'  => array(),
			'support_note' => array(
				'type' => 'note',
				'text' => __( 'Selected locations will use the display settings defined from the menu on the left.', 'Monarch' ),
			),
			'import'       => array(
				'type'  => 'import',
				'title' => __( 'Import', 'Compi' ),
			),
			'export'       => array(
				'type'  => 'export',
				'title' => __( 'Export', 'Compi' ),
			),
		);


		$this->general_section_one_options = array(
			$this->dash_options_all['general_tab']['option_heading'],
			$this->dash_options_all['general_tab']['option_switch'],
			$this->dash_options_all['general_tab']['section_end'],
			$this->dash_options_all['general_tab']['option2_heading'],
			$this->dash_options_all['general_tab']['option2_switch'],
			$this->dash_options_all['general_tab']['section_end'],
		);


		$this->tweaks_section_one_options = array(
		);


		$this->support_section_one_options = array(
		);

		$this->header_import_options = array(
		);
		$this->header_export_options = array(
		);


	}
}