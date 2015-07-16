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
			'modules'    => array(
				'title'    => __( 'Modules', 'Compi' ),
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
			'compi_opts' => array(
				'title'    => __( 'Compi Options', 'Compi' ),
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
		);

		$this->dash_options_all = array(
			'modules_tab'      => array(
				'section_start'       => array(
					'type' => 'section_start',
					'title' => 'Modules',
					'subtitle' => 'Use the controls below to enable your desired features.'
				),
				'card1_start'                => array(
					'type'    => 'card_start',
					'title'   => 'Fullwidth Header',
					'regular' => false,
					'icon' => 'fullwidth_header'
				),
				'fw_header_cover_slider'   => array(
					'type'        => 'card_option',
					'title'       => '',
					'description' => __( 'Add Cover Slider option when Fullscreen mode is enabled.', 'Compi' ),
					'name'        => 'fw_header_cover_slider',
					'hint_text'   => __( 'This will allow you to display a fullscreen Cover Slider.', 'Compi' ),
					'default'     => 1,
					'conditional' => 'fw_header_cover_slider_enabled',
					'class'       => '',
				),
				'fw_header_some_tweak' => array(
					'type'        => 'card_option',
					'title'       => '',
					'description' => __( 'Add option to use thumbnails with portrait orientation.', 'Compi' ),
					'name'        => 'fw_header_some_tweak',
					'hint_text'   => __( 'By default, portfolio thumbnails have a landscape orientation. This will add an option to use thumbnails that have a portrait orientation.', 'Compi' ),
					'default'     => 1,
					'conditional' => 'fw_header_some_tweak_enabled',
					'class'       => '',
				),
				'card1_end'                  => array(
					'type' => 'card_end',
				),
				'card2_start'                => array(
					'type'    => 'card_start',
					'title'   => 'Gallery',
					'regular' => true,
					'icon' => 'gallery'
				),
				'gallery_portrait_thumbs' => array(
					'type'        => 'card_option',
					'title'       => '',
					'description' => __( 'Option to use thumbnails with portrait orientation.', 'Compi' ),
					'name'        => 'gallery_portrait_images',
					'hint_text'   => __( 'By default, gallery thumbnails have a landscape orientation. This adds an option to use portrait thumbnails.', 'Compi' ),
					'default'     => 1,
					'conditional' => 'gallery_portrait_images_enabled',
					'class'       => '',
				),
				'gallery_description' => array(
					'type'        => 'card_option',
					'title'       => '',
					'description' => __( 'Option to display image description in lightbox.', 'Compi' ),
					'name'        => 'fw_header_description',
					'hint_text'   => __( 'By default, portfolio thumbnails have a landscape orientation. This will add an option to use thumbnails that have a portrait orientation.', 'Compi' ),
					'default'     => 1,
					'conditional' => 'fw_header_description_enabled',
					'class'       => '',
				),
				'card2_end'                  => array(
					'type' => 'card_end',
				),
				'card3_start' => array(
					'type' => 'card_start',
					'title' => 'Portfolio',
					'regular' => true,
					'icon' => 'portfolio'
				),
				'portfolio_regular_posts' => array(
					'type'        => 'card_option',
					'title'       => '',
					'description' => __( 'Make regular posts available in this module.', 'Compi' ),
					'name'        => 'portfolio_regular_posts',
					'hint_text'   => __( 'By default, portfolios can only display project posts. This will allow you to display regular posts in addition to project posts.', 'Compi' ),
					'default'     => 1,
					'conditional' => 'portfolio_regular_posts_enabled',
					'class'       => '',
				),
				'portfolio_portrait_thumbs' => array(
					'type'        => 'card_option',
					'title'       => '',
					'description' => __( 'Add option to use thumbnails with portrait orientation.', 'Compi' ),
					'name'        => 'portfolio_portrait_images',
					'hint_text'   => __( 'By default, portfolio thumbnails have a landscape orientation. This will add an option to use thumbnails that have a portrait orientation.', 'Compi' ),
					'default'     => 1,
					'conditional' => 'portfolio_portrait_images_enabled',
					'class'       => '',
				),
				'card3_end' => array(
					'type' => 'card_end',
				),
				'section_end'         => array(
					'type' => 'section_end',
				),
			),
			'new_modules_tab'       => array(
				'section_start'       => array(
					'type' => 'section_start',
					'title' => 'Add New Modules To The Builder',
					'subtitle' => 'Use the controls below to enable your desired modules.'
				),
				'card1_start'                => array(
					'type'    => 'card_start',
					'title'   => 'Cover Slider',
					'regular' => false,
					'icon' => 'fullwidth_header'
				),
				'cover_slider'   => array(
					'type'        => 'card_option',
					'title'       => '',
					'description' => __( 'Like the Fullwidth Slider, but it\'s fullscreen!', 'Compi' ),
					'name'        => 'cover_slider',
					'hint_text'   => __( 'This will allow you to display a fullscreen Cover Slider.', 'Compi' ),
					'default'     => 1,
					'conditional' => 'cover_slider_enabled',
					'class'       => '',
				),
				'card1_end'                  => array(
					'type' => 'card_end',
				),
				'section_end'         => array(
					'type' => 'section_end',
				)
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
			$this->dash_options_all['enhancements_tab']['card1_start'],
			$this->dash_options_all['enhancements_tab']['fw_header_cover_slider'],
			$this->dash_options_all[ 'enhancements_tab' ]['fw_header_some_tweak'],
			$this->dash_options_all['enhancements_tab']['card1_end'],
			$this->dash_options_all['enhancements_tab']['card2_start'],
			$this->dash_options_all['enhancements_tab']['gallery_description'],
			$this->dash_options_all[ 'enhancements_tab' ]['gallery_portrait_thumbs'],
			$this->dash_options_all['enhancements_tab']['card2_end'],
		    $this->dash_options_all['enhancements_tab']['card3_start'],
			$this->dash_options_all['enhancements_tab']['portfolio_regular_posts'],
			$this->dash_options_all['enhancements_tab']['portfolio_portrait_thumbs'],
			$this->dash_options_all['enhancements_tab']['card3_end'],
			//$this->dash_options_all['enhancements_tab']['section_end'],
		);


		$this->new_modules_section_one_options = array(
			$this->dash_options_all['new_modules_tab']['section_start'],
			$this->dash_options_all['new_modules_tab']['card1_start'],
			$this->dash_options_all['new_modules_tab']['cover_slider'],
			$this->dash_options_all['new_modules_tab']['card1_end'],
			//$this->dash_options_all['new_modules_tab']['section_end'],
		);


		$this->builder_general_section_one_options = array(
			$this->dash_options_all['builder_general_tab']['section_start'],
			//$this->dash_options_all['builder_general_tab']['section_end'],
		);


		$this->theme_general_section_one_options = array(
			$this->dash_options_all['theme_general_tab']['section_start'],
			//$this->dash_options_all['theme_general_tab']['section_end'],
		);

		$this->support_section_one_options = array(
			$this->dash_options_all['support_tab']['section_start'],
			//$this->dash_options_all['support_tab']['section_end'],
		);

		$this->header_import_options = array(
			$this->dash_options_all['import'],
		);
		$this->header_export_options = array(
			$this->dash_options_all['export'],
		);


	}
}