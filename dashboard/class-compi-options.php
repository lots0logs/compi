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
			'enhancements'    => array(
				'title'    => __( 'Module Enhancements', 'Compi' ),
				'contents' => array(
					'section_one' => __( 'Section One', 'Compi' ),
				),

			),
			'new_modules'     => array(
				'title'    => __( 'New Modules', 'Compi' ),
				'contents' => array(
					'section_one' => __( 'Section One', 'Compi' ),
				),
			),
			'builder_general' => array(
				'title'    => __( 'Builder Options', 'Compi' ),
				'contents' => array(
					'section_one' => __( 'Section One', 'Compi' ),
				),
			),
			'theme_general'   => array(
				'title'    => __( 'Theme Options', 'Compi' ),
				'contents' => array(
					'section_one' => __( 'Section One', 'Compi' ),
				),
			),
			'support'         => array(
				'title'    => __( 'Support', 'Compi' ),
				'contents' => array(
					'section_one' => __( 'Section One', 'Compi' ),
				),
			),
			'header'          => array(
				'contents' => array(
					'import' => __( 'Import', 'Compi' ),
					'export' => __( 'Export', 'Compi' ),
				),
			),
		);

		$this->dash_options_all = array(
			'enhancements_tab'      => array(
				'section_start'       => array(
					'type' => 'section_start',
					'title' => '',
					'subtitle' => ''
				),
				'card_start' => array(
					'type' => 'card_start',
					'title' => 'Portfolio',
					'regular' => true
				),
				'posts_for_portfolio' => array(
					'type'        => 'card_option',
					'title'       => '',
					'description' => __( 'Make regular posts available in this module.', 'Compi' ),
					'name'        => 'posts_for_portfolio',
					'hint_text'   => __( 'By default, portfolios can only display porject posts. This will allow you to display regular posts in addition to project posts.', 'Compi' ),
					'default'     => 1,
					'conditional' => 'posts_for_portfolios_enabled',
					'class'       => '',
				),
				'card_end' => array(
					'type' => 'card_end',
				),
				'section_end'         => array(
					'type' => 'section_end',
				),
			),
			'new_modules_tab'       => array(
				'section_start' => array(
					'type' => 'section_start',
				),
				'section_end'   => array(
					'type' => 'section_end',
				),
			),
			'builder_general_tab'   => array(
				'section_start' => array(
					'type' => 'section_start',
				),
				'section_end'   => array(
					'type' => 'section_end',
				),
			),
			'theme_general_tab'     => array(
				'section_start' => array(
					'type' => 'section_start',
				),
				'section_end'   => array(
					'type' => 'section_end',
				),
			),
			'support_tab'           => array(
				'section_start' => array(
					'type' => 'section_start',
				),
				'section_end'   => array(
					'type' => 'section_end',
				),
			),
			'enhancements_title'    => array(
				'type'     => 'main_title',
				'title'    => __( 'Module Enhancements', 'Compi' ),
				'subtitle' => __( 'Tweaks and/or new features for existing modules.', 'Compi' ),
			),
			'new_modules_title'     => array(
				'type'     => 'main_title',
				'title'    => __( 'New Modules', 'Compi' ),
				'subtitle' => __( 'New modules only available with Compi.', 'Monarch' ),
			),
			'builder_general_title' => array(
				'type'  => 'main_title',
				'title' => __( 'Builder General Settings', 'Compi' ),
			),
			'theme_general_title'   => array(
				'type'  => 'main_title',
				'title' => __( 'Builder General Settings', 'Compi' ),
			),
			'support_title'         => array(
				'type'  => 'main_title',
				'title' => __( 'Support', 'Compi' ),
			),
			'enhancements_note'     => array(
				'type' => 'note',
				'text' => __( 'Selected locations will use the display settings defined from the menu on the left.', 'Monarch' ),
			),
			'new_modules_note'      => array(
				'type' => 'note',
				'text' => __( 'Selected locations will use the display settings defined from the menu on the left.', 'Monarch' ),
			),
			'builder_general_note'  => array(
				'type' => 'note',
				'text' => __( 'Selected locations will use the display settings defined from the menu on the left.', 'Monarch' ),
			),
			'theme_general_note'    => array(
				'type' => 'note',
				'text' => __( 'Selected locations will use the display settings defined from the menu on the left.', 'Monarch' ),
			),
			'support_note'          => array(
				'type' => 'note',
				'text' => __( 'Selected locations will use the display settings defined from the menu on the left.', 'Monarch' ),
			),
			'import'                => array(
				'type'  => 'import',
				'title' => __( 'Import', 'Compi' ),
			),
			'export'                => array(
				'type'  => 'export',
				'title' => __( 'Export', 'Compi' ),
			),
		);


		$this->enhancements_section_one_options = array(
			$this->dash_options_all['enhancements_tab']['section_start'],
			$this->dash_options_all['enhancements_tab']['card_start'],
			$this->dash_options_all['enhancements_tab']['posts_for_portfolio'],
			$this->dash_options_all['enhancements_tab']['card_end'],
			$this->dash_options_all['enhancements_tab']['section_end'],
		);


		$this->new_modules_section_one_options = array(
			$this->dash_options_all['new_modules_tab']['section_start'],
			$this->dash_options_all['new_modules_tab']['section_end'],
		);


		$this->builder_general_section_one_options = array(
			$this->dash_options_all['builder_general_tab']['section_start'],
			$this->dash_options_all['builder_general_tab']['section_end'],
		);


		$this->theme_general_section_one_options = array(
			$this->dash_options_all['theme_general_tab']['section_start'],
			$this->dash_options_all['theme_general_tab']['section_end'],
		);

		$this->support_section_one_options = array(
			$this->dash_options_all['support_tab']['section_start'],
			$this->dash_options_all['support_tab']['section_end'],
		);

		$this->header_import_options = array(
			$this->dash_options_all['import'],
		);
		$this->header_export_options = array(
			$this->dash_options_all['export'],
		);


	}
}