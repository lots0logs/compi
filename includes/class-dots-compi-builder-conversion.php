<?php
/*
 * class-dots-compi-builder-conversion.php
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
 * Utility class for converting Elegant Builder layouts to the Divi Builder.
 *
 * @package    Compi
 * @subpackage Compi/Conversion_Util
 * @author     wpdots <dev@wpdots.com>
 */
class Dots_Compi_Conversion_Util {

	public function __construct() {

		$this->map              = $this->get_eb_to_divi_builder_mapping();
		$this->eb_settings      = get_option( 'et_lb_main_settings' );
		$this->eb_post_types    = isset( $this->eb_settings['post_types'] )
			? (array) $this->eb_settings['post_types']
			: apply_filters( 'et_builder_default_post_types', array( 'post', 'page' ) );
		$this->eb_posts_objects = array();
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

	public function add_conversion_utility_post_columns( $columns ) {
		return array_merge( $columns, array(
			'dots_compi_builder_status' => __( 'Builder Status' )
		));

	}


	public function maybe_display_et_builder_status( $column, $post_id ) {
		$builder_meta = get_post_meta( $post_id, '_et_builder_settings', true);
		$builder_disabled = get_post_meta( $post_id, '_et_builder_disabled', true);
		$this->write_log(array('builder_meta' => $builder_meta, 'builder_disabled' => $builder_disabled));

		if ( 'dots_compi_builder_status' === $column && '' !== $builder_meta && 0 === $builder_disabled ) {
			echo "EB";
		}
	}

	public function get_eb_to_divi_builder_mapping() {

		return array(
			'et_lb_logo'        => array(
				'new_slug' => 'et_pb_image',
				'attrs'    => array(
					'align'   => 'align',
					'content' => 'url',
				),
			),
			'et_lb_paper'       => array(
				'new_slug' => 'et_pb_text',
				'attrs'    => array(),
			),
			'et_lb_video'       => array(
				'new_slug' => 'et_pb_video',
				'attrs'    => array(
					'video_url' => 'src',
					'class'     => 'module_class',
				),
			),
			'et_lb_testimonial' => array(
				'new_slug' => 'et_pb_testimonial',
				'attrs'    => array(
					'image_url'       => 'portrait_url',
					'author_name'     => 'author',
					'author_position' => 'job_title',
					'author_site'     => 'url',
					'content'         => 'content_new',
				),
			),
			'et_lb_1_2'         => array(
				'new_slug'  => 'et_pb_column',
				'attrs_new' => array(
					'type' => '1_2',
				),
			),
			'et_lb_1_3'         => array(
				'new_slug'  => 'et_pb_column',
				'attrs_new' => array(
					'type' => '1_3',
				),
			),
			'et_lb_1_4'         => array(
				'new_slug'  => 'et_pb_column',
				'attrs_new' => array(
					'type' => '1_4',
				),
			),
			'et_lb_2_3'         => array(
				'new_slug'  => 'et_pb_column',
				'attrs_new' => array(
					'type' => '2_3',
				),
			),
			'et_lb_3_4'         => array(
				'new_slug'  => 'et_pb_column',
				'attrs_new' => array(
					'type' => '3_4',
				),
			),
			'et_lb_resizable'   => array(
				'new_slug'  => 'et_pb_row',
				'attrs'     => array(
					'width' => 'custom_width_percent',
				),
				'attrs_new' => array(
					'make_fullwidth'    => 'off',
					'use_custom_width'  => 'on',
					'width_unit'        => 'off',
					'use_custom_gutter' => 'off',
				),
			),
		);
	}

	public function extract_shortcode_opening_tags( $layout ) {

		preg_match( '@\[(et_lb_[a-z1-4_]+)(([a-zA-Z0-9_=":/.\- ]+\])|\])@gm', $layout, $matches );

		return $matches;
	}

	public function get_shortcode_attrs_from_opening_tag( $tag ) {

		preg_match( '@( )([a-z0-9_]+)="([a-zA-Z0-9_:/.\- ]+)"@gm', $tag, $matches );

		return $matches;
	}
}