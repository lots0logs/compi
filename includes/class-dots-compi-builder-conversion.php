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
	private static $_this;

	public function __construct() {

		// Don't allow more than one instance of the class
		if ( isset( self::$_this ) ) {
			wp_die( sprintf( __( '%s is a singleton class and you cannot create a second instance.', 'dots_compi' ),
					get_class( $this ) )
			);
		}


		self::$_this = $this;

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
			'dots_compi_builder_status' => __( 'Builder Status' ),
		) );

	}


	public function maybe_display_et_builder_status( $column, $post_id ) {

		if ( 'dots_compi_builder_status' === $column ) {
			$layout_builder_disabled = get_post_meta( $post_id, '_et_builder_disabled', true );
			$_et_builder_use_builder = get_post_meta( $post_id, '_et_pb_use_builder', true );
			$divi_builder_disabled   = 'on' === $_et_builder_use_builder ? false : true;
			$container_opened        = false;


			if ( ! $layout_builder_disabled ) {
				$layout_builder_meta = get_post_meta( $post_id, '_et_builder_settings', true );
				if ( '' !== $layout_builder_meta && '' !== $layout_builder_meta['layout_shortcode'] ) {
					echo '<div class="dots_post_table_column"><span class="layout_builder_icon">E</span>';
					$container_opened = true;
				}
			}

			if ( ! $divi_builder_disabled ) {
				if ( ! $container_opened ) {
					echo '<div class="dots_post_table_column">';
				}
				echo '<span class="divi_builder_icon"></span></div>';
			}
		}
	}

	public function do_builder_conversion() {

		if ( empty( $_REQUEST['action'] ) || 'dots_compi_do_builder_conversion' != $_REQUEST['action'] ) {
			return;
		}
		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'dots_compi_do_builder_conversion-nonce' ) ) {
			return;
		}

		ignore_user_abort( true );
		if ( ! ini_get( 'safe_mode' ) ) {
			@set_time_limit( 0 );
		}

		parse_str( $_POST['form'], $form );
		$_REQUEST   = $form = (array) $form;
		$step       = absint( $_POST['step'] );
		$queue      = isset( $_REQUEST['posts'] ) ? (array) sanitize_text_field( $_REQUEST['posts'] ) : array();
		$successful = isset( $_REQUEST['success'] ) ? (array) sanitize_text_field( $_REQUEST['success'] ) : array();
		$failed     = isset( $_REQUEST['failed'] ) ? (array) sanitize_text_field( $_REQUEST['failed'] ) : array();

		$ret = $this->process_step( $queue, $successful, $failed );

		$percentage = $this->get_percentage_complete( $step, $ret['queue'], $ret['successful'], $ret['failed'] );

		if ( $ret ) {

			$step += 1;
			echo json_encode( array(
				'step'       => $step,
				'percentage' => $percentage,
				'queue'      => $ret['queue'],
				'successful' => $ret['successful'],
				'failed'     => $ret['failed'],
			) );

		} else {

			echo json_encode( array( 'step' => 'done' ) );

		}

		wp_die();
	}

	public function extract_shortcode_opening_tags( $layout ) {

		preg_match( '@\[(et_lb_[a-z1-4_]+)(([a-zA-Z0-9_=":/.\- ]+\])|\])@gm', $layout, $matches );

		return $matches;
	}

	public function get_shortcode_attrs_from_opening_tag( $tag ) {

		preg_match( '@( )([a-z0-9_]+)="([a-zA-Z0-9_:/.\- ]+)"@gm', $tag, $matches );

		return $matches;
	}

	private function process_step( $queue, $successful, $failed ) {

		$total = count( $queue );

		if ( $total > 0 ) {
			$batch = $total >= 5 ? 10 : $total;

			foreach ( range( 0, $batch ) as $i ) {
				$src_post  = array_pop( $queue );
				$converted = $this->convert_post( $src_post );
				if ( false !== $converted ) {
					array_push( $successful, $src_post );
				} else {
					array_push( $failed, $src_post );
				}
			}

			return array(
				'queue'      => $queue,
				'successful' => $successful,
				'failed'     => $failed,
			);
		}

		return false;
	}

	private function get_percentage_complete( $step, $queue, $successful, $failed ) {

		$total_posts = count( $queue ) + count( $successful ) + count( $failed );
		$total_steps = $total_posts > 5 ? ceil( $total_posts % 5 ) : 1;
		$percent     = $step / $total_steps;

		return 1 === $total_steps ? 100 : number_format( $percent * 100, 2 );

	}

	private function convert_post( $src_post ) {

		try {
			$this->write_log( $src_post );
			$result = true;
		} catch ( Exception $err ) {
			$this->write_log( $err->getMessage() );
			$result = false;
		}

		return $result;

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
}