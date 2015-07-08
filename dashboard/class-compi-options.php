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
			'enhancements' => array(
				'title'    => __( 'Module Enhancements', 'Compi' ),
				'contents' => array(
					'section_one' => __( 'Section One', 'Compi' ),
					'section_two'      => __( 'Section Two', 'Compi' ),
					'section_three'   => __( 'Section Three', 'Compi' ),
				),

			),
			'new_modules' => array(
				'title'    => __( 'New Modules', 'Compi' ),
				'contents' => array(
					'section_one'   => __( 'Section One', 'Compi' ),
					'section_two'   => __( 'Section Two', 'Compi' ),
					'section_three' => __( 'Section Three', 'Compi' ),
				),
			),
			'builder_general' => array(
				'title'    => __( 'Builder Options', 'Compi' ),
				'contents' => array(
					'section_one'   => __( 'Section One', 'Compi' ),
					'section_two'   => __( 'Section Two', 'Compi' ),
					'section_three' => __( 'Section Three', 'Compi' ),
				),
			),
			'theme_options'   => array(
				'title'    => __( 'Theme Options', 'Compi' ),
				'contents' => array(
					'section_one'   => __( 'Section One', 'Compi' ),
					'section_two'   => __( 'Section Two', 'Compi' ),
					'section_three' => __( 'Section Three', 'Compi' ),
				),
			),
			'support'  => array(
				'title'    => __( 'Support', 'Compi' ),
				'contents' => array(
					'section_one'   => __( 'Section One', 'Compi' ),
					'section_two'   => __( 'Section Two', 'Compi' ),
					'section_three' => __( 'Section Three', 'Compi' ),
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
			'enhancements'       => array(
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
			'new_modules'         => array(
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
			'builder_general'        => array(
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
			'theme_options' => array(
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
			'support' => array(
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
			'enhancements_note'  => array(
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

		$this->enhancements_options = array(
			$this->dash_options_all['enhancements_title'],
			$this->dash_options_all['enhancements'],
			$this->dash_options_all['enhancements_note'],
		);

		$this->enhancements_section_one_options = array(
			$this->dash_options_all['enhancements']['section_start'],
			$this->dash_options_all['enhancements']['end_of_section'],
		);

		$this->enhancements_section_two_options = array(
			$this->dash_options_all['enhancements']['section_start'],
			$this->dash_options_all['enhancements']['end_of_section'],
		);

		$this->enhancements_section_three_options = array(
			$this->dash_options_all['enhancements']['section_start'],
			$this->dash_options_all['enhancements']['end_of_section'],
		);

		$this->new_modules_options = array(
			$this->dash_options_all['new_modules_title'],
			$this->dash_options_all['new_modules'],
			$this->dash_options_all['new_modules_note'],
		);

		$this->new_modules_section_one_options = array(
			$this->dash_options_all['new_modules']['section_start'],
			$this->dash_options_all['new_modules']['end_of_section'],
		);

		$this->new_modules_section_two_options = array(
			$this->dash_options_all['new_modules']['section_start'],
			$this->dash_options_all['theme']['end_of_section'],
		);

		$this->new_modules_section_three_options = array(
			$this->dash_options_all['new_modules']['section_start'],
			$this->dash_options_all['new_modules']['end_of_section'],
		);

		$this->builder_general_options = array(
			$this->dash_options_all['builder_general_title'],
			$this->dash_options_all['builder_general'],
			$this->dash_options_all['builder_general_note'],
		);

		$this->builder_general_section_one_options = array(
			$this->dash_options_all['builder_general']['section_start'],
			$this->dash_options_all['builder_general']['end_of_section'],
		);

		$this->builder_general_section_two_options = array(
			$this->dash_options_all['builder_general']['section_start'],
			$this->dash_options_all['builder_general']['end_of_section'],
		);

		$this->builder_general_section_three_options = array(
			$this->dash_options_all['builder_general']['section_start'],
			$this->dash_options_all['builder_general']['end_of_section'],
		);

		$this->theme_options = array(
			$this->dash_options_all['theme_title'],
			$this->dash_options_all['theme_options'],
			$this->dash_options_all['theme_options_note'],
		);

		$this->theme_options_section_one_options = array(
			$this->dash_options_all['theme_options']['section_start'],
			$this->dash_options_all['theme_options']['end_of_section'],
		);

		$this->theme_options_section_two_options = array(
			$this->dash_options_all['theme_options']['section_start'],
			$this->dash_options_all['theme_options']['end_of_section'],
		);

		$this->theme_options_section_three_options = array(
			$this->dash_options_all['theme_options']['section_start'],
			$this->dash_options_all['theme_options']['end_of_section'],
		);

		$this->support_options = array(
			$this->dash_options_all['support_title'],
			$this->dash_options_all['support_options'],
			$this->dash_options_all['support_options_note'],
		);

		$this->support_section_one_options = array(
			$this->dash_options_all['support']['section_start'],
			$this->dash_options_all['support']['end_of_section'],
		);

		$this->support_section_two_options = array(
			$this->dash_options_all['support']['section_start'],
			$this->dash_options_all['support']['end_of_section'],
		);

		$this->support_section_three_options = array(
			$this->dash_options_all['support']['section_start'],
			$this->dash_options_all['support']['end_of_section'],
		);



		$this->header_import_options = array(
			$this->dash_options_all['import'],
		);
		$this->header_export_options = array(
			$this->dash_options_all['export'],
		);


	}
}