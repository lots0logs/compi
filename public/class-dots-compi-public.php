<?php
/*
 * class-dots-compi-public.php
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
 * The main class for all public-facing functionality.
 *
 * Definition.
 *
 * @package    Compi
 * @subpackage Compi/public
 * @author     wpdots <dev@wpdots.com>
 */
class Dots_Compi_Public {

	private static $_this;
	/**
	 * Features that can be enabled/disabled by user.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public $features;
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
	 * @param      string $version     The version of this plugin.
	 * @param $options
	 * @param $util
	 */
	public function __construct( $plugin_name, $version, $options, $util ) {

		// Don't allow more than one instance of the class
		if ( isset( self::$_this ) ) {
			wp_die( sprintf( __( '%s is a singleton class and you cannot create a second instance.', 'dots_compi' ),
					get_class( $this ) )
			);
		}

		self::$_this = $this;

		$this->plugin_name    = $plugin_name;
		$this->version        = $version;
		$this->compi_options  = $options;
		$this->public_dir     = plugin_dir_path( __FILE__ );
		$this->includes_dir   = dirname( $this->public_dir ) . '/includes';
		$this->template_dir   = $this->public_dir . 'templates';
		$this->css_stylesheet = plugins_url( '/css/compi-style.css', __FILE__ );
		$this->custom_script  = plugins_url( '/js/compi-custom.js', __FILE__ );
		$this->features       = array();
		$this->conversion_util = $util;

		$this->check_for_enabled_features();
		$this->maybe_activate_features();
		add_filter( 'the_content', array($this, 'shortcode_test' ), 99 );

	}


	/**
	 * @return array
	 */
	public function get_modules() {

		$modules = [ 'Portfolio', 'Fullwidth_Portfolio', 'Filterable_Portfolio' ];

		return $modules;
	}

	/**
	 * Check features that are enabled in our options array.
	 *
	 * @since    1.0.0
	 *
	 */
	public function check_for_enabled_features() {

		$options        = $this->compi_options;
		$this->features = array(
			'global_masonry'      => false,
			'global_fullwidth'    => false,
			'module_enhancements' => false,
			'new_modules'         => false,
		);

		if ( isset( $options['tweaks_global_masonry'] ) && $options['tweaks_global_masonry'] == 1 ) {
			$this->features['global_masonry'] = true;
		}

		if ( isset( $options['tweaks_global_fullwidth'] ) && $options['tweaks_global_fullwidth'] == 1 ) {
			$this->features['global_fullwidth'] = true;
		}

		if ( isset( $options['general_module_enhancements'] ) && $options['general_module_enhancements'] == 1 ) {
			$this->features['module_enhancements'] = true;
		}

		if ( isset( $options['general_new_modules'] ) && $options['general_new_modules'] == 1 ) {
			$this->features['new_modules'] = true;
		}

	}

	/**
	 * Register the stylesheets for the public-facing features.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		if ( in_array( true, $this->features, true ) ) {

			wp_enqueue_style( $this->plugin_name . 'style', $this->css_stylesheet, array( 'divi-style' ), $this->version );
		}

	}

	/**
	 * Register the scripts for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		if ( in_array( true, $this->features, true ) ) {

			wp_enqueue_script( $this->plugin_name . 'custom', $this->custom_script, array(
				'jquery',
				'divi-custom',
			), $this->version, false );
		}
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

		$dots_compi_sidebar = ! $this->features['global_fullwidth'];
		$current_filter     = current_filter();

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

		if ( true === $this->features['global_masonry'] ) {
			$this->add_template_override_filter( 'index' );
		}
		if ( true === $this->features['global_fullwidth'] && false === $this->features['global_masonry'] ) {
			add_filter( 'body_class', array( $this, 'add_body_classes' ) );
		}
		if ( true === $this->features['module_enhancements'] ) {

			//add_action( 'wp', array( $this, 'activate_module_enhancements' ), 99 );
			$this->activate_module_enhancements();

		}

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
	 * Add classes to body.
	 *
	 * @param $classes
	 *
	 * @return array
	 */
	public function add_body_classes( $classes ) {

		$this->check_for_enabled_features();

		if ( true === $this->features['global_fullwidth'] && false === $this->features['global_masonry'] ) {
			$classes[] = 'et_full_width_page';

		}
		if ( true === $this->features['global_fullwidth'] ) {
			$classes[] = 'dots_compi_archive_grid ';
		}

		return $classes;

	}


	public function activate_module_enhancements() {

		require $this->includes_dir . '/dots-compi-main-modules.php';
		$modules = $this->get_modules();

		foreach ( $modules as $module ) {
			$dots_module = 'Dots_ET_Builder_Module_' . $module;
			new $dots_module( $this->features );
			break;
		}

	}

	public function shortcode_test($content) {
		if ( is_page( 768 ) ) {
			$builder_layout = get_post_meta( get_the_ID(), '_et_builder_settings', true );
			$shortcodes = '' !== $builder_layout['layout_shortcode'] ? $builder_layout['layout_shortcode'] : false;
			if ( $shortcodes ) {
				$map = $this->conversion_util->map;
				$tags = $this->conversion_util->extract_shortcode_opening_tags($shortcodes);

				if ( is_array($tags) ) {
					foreach ( $tags as $tag ) {
						$old_slug = $tag[1];
						$new_slug = $map[$old_slug]['new_slug'];
						$attrs = $this->conversion_util->get_shortcode_attrs_from_opening_tag($tag[0]);
						$new_attrs = array();

						if ( is_array( $attrs ) ) {
							foreach ( $attrs as $attr ) {
								$old_attr = $attr[2];
								$new_attr = $map[$old_slug]['attrs'][$old_attr];
								$value = $attr[3];
								$new_attrs[$new_attr] = $value;

							}
						}
					}
				}
			}

			$content = $content . '<div>' . $shortcodes . '</div>';
		}
		return $content;
	}


}