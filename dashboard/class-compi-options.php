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
 * @property  builder_options
 * @package    Compi
 * @subpackage Compi/dashboard
 * @author     wpdots <dev@wpdots.com>
 */
class Compi_Options_Table {

	public function __construct() {

		$this->dash_sections = array(
			'builder' => array(
				'title'    => __( 'Divi Builder', 'Compi' ),
				'contents' => array(
					'existing' => __( 'Existing Modules', 'Compi' ),
					'new'      => __( 'New Modules', 'Compi' ),
					'tweaks'   => __( 'Extra Options', 'Compi' ),
				),
			),
			'theme'   => array(
				'title'    => __( 'Divi Theme', 'Compi' ),
				'contents' => array(
					'header'  => __( 'Header', 'Compi' ),
					'widgets' => __( 'Widgets', 'Compi' ),
					'footer'  => __( 'Footer', 'Compi' ),
				),
			),
			'plugin'  => array(
				'title'    => __( 'Compi Settings', 'Compi' ),
				'contents' => array(
					'main' => __( 'Main', 'Compi' ),
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
			'builder'       => array(
				'section_start'       => array(
					'type' => 'section_start',
				),
				'posts_for_portfolio' => array(
					'type'      => 'checkbox',
					'title'     => __( 'Make regular posts available in the Portfolio Module.', 'Compi' ),
					'name'      => 'posts_for_portfolio',
					'hint_text' => __( "By default, portfolios can only display porject posts. This will allow you to display regular posts in addition to project posts.", 'Compi' ),
					'default'   => 'false',
				),
				'reset_postdata'      => array(
					'type'      => 'checkbox',
					'title'     => __( 'Reset WordPress loops', 'Compi' ),
					'name'      => 'reset_postdata',
					'hint_text' => __( 'Enable the option if the plugin does not detect permalinks properly', 'Compi' ),
					'default'   => false,
				),
				'end_of_section'      => array(
					'type' => 'section_end',
				),
				'end_of_sub_section'  => array(
					'type'        => 'section_end',
					'sub_section' => 'true',
				),
			),
			'theme'         => array(
				'section_start'      => array(
					'type' => 'section_start',
				),
				'end_of_section'     => array(
					'type' => 'section_end',
				),
				'end_of_sub_section' => array(
					'type'        => 'section_end',
					'sub_section' => 'true',
				),
			),
			'plugin'        => array(
				'section_start'      => array(
					'type' => 'section_start',
				),
				'end_of_section'     => array(
					'type' => 'section_end',
				),
				'end_of_sub_section' => array(
					'type'        => 'section_end',
					'sub_section' => 'true',
				),
			),
			'builder_title' => array(
				'type'     => 'main_title',
				'title'    => __( 'Builder Enhancements', 'Compi' ),
				'subtitle' => __( 'You can select any combination of the five placements below.', 'Compi' ),
			),
			'theme_title'   => array(
				'type'     => 'main_title',
				'title'    => __( 'Theme Enhancements', 'Compi' ),
				'subtitle' => __( 'Add and rearrange any combination of social networks below. You can define the Network Label and Username to the right of each.', 'Monarch' ),
			),
			'plugin_title'  => array(
				'type'  => 'main_title',
				'title' => __( 'Compi Plugin Settings', 'Compi' ),
			),
			'builder_note'  => array(
				'type' => 'note',
				'text' => __( 'Selected locations will use the display settings defined from the menu on the left.', 'Monarch' ),
			),
			'theme_note'    => array(
				'type' => 'note',
				'text' => __( 'Selected locations will use the display settings defined from the menu on the left.', 'Monarch' ),
			),
			'plugin_note'   => array(
				'type' => 'note',
				'text' => __( 'Selected locations will use the display settings defined from the menu on the left.', 'Monarch' ),
			),
			'import'        => array(
				'type'  => 'import',
				'title' => __( 'Import', 'Compi' ),
			),
			'export'        => array(
				'type'  => 'export',
				'title' => __( 'Export', 'Compi' ),
			),
		);

		$this->builder_options = array(
			$this->dash_options_all['builder_title'],
			$this->dash_options_all['builder'],
			$this->dash_options_all['builder_note'],
		);

		$this->theme_options = array(
			$this->dash_options_all['theme_title'],
			$this->dash_options_all['theme'],
			$this->dash_options_all['theme_note'],
		);

		$this->plugin_options = array(
			$this->dash_options_all['plugin_title'],
			$this->dash_options_all['plugin'],
			$this->dash_options_all['plugin_note'],
		);

		$this->header_import_options = array(
			$this->dash_options_all['import'],
		);
		$this->header_export_options = array(
			$this->dash_options_all['export'],
		);


	}
}