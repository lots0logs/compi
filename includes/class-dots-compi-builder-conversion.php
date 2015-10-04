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
		$this->new_content      = array();
		$this->row_open         = false;
		$this->rows             = array();
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
			$this->write_log( 'request action empty' );

			return;
		}
		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'dots_compi_do_builder_conversion-nonce' ) ) {
			$this->write_log( 'couldnt verify nonce' );

			return;
		}
		$this->write_log( 'do_builder_conversion fired!!' );

		ignore_user_abort( true );
		if ( ! ini_get( 'safe_mode' ) ) {
			@set_time_limit( 0 );
		}

		parse_str( $_POST['form'], $form );
		$form = (array) $form;
		$this->write_log( $form );
		$step       = absint( $_POST['step'] );
		$queue      = is_array( $form['post'] ) ? $form['post'] : array();
		$successful = is_array( $form['successful'] ) ? $form['successful'] : array();
		$failed     = is_array( $form['failed'] ) ? $form['failed'] : array();
		$this->write_log( array(
							  'queue'      => $queue,
							  'successful' => $successful,
							  'failed'     => $failed,
						  ) );

		$ret = $this->process_step( $queue, $successful, $failed );

		$percentage = $this->get_percentage_complete( $step, $ret['queue'], $ret['successful'], $ret['failed'] );

		if ( $ret && false === $ret['done'] ) {

			$step += 1;
			echo json_encode( array(
								  'step'       => $step,
								  'percentage' => $percentage,
								  'post'       => $ret['queue'],
								  'successful' => $ret['successful'],
								  'failed'     => $ret['failed'],
								  '_wpnonce'   => wp_create_nonce( 'dots_compi_do_builder_conversion-nonce' ),
							  ) );

		} else {

			echo json_encode( array( 'step' => 'done' ) );

		}

		wp_die();
	}

	public function extract_shortcode_opening_tags( $layout ) {

		preg_match_all( '@\[(et_lb_[a-z1-4_]+)(([a-zA-Z0-9_=":/.\- ]+\])|\])@m', $layout, $matches );

		return $matches;
	}

	public function get_shortcode_attrs_from_opening_tag( $tag ) {

		preg_match_all( '@( )([a-z0-9_]+)="([a-zA-Z0-9_:/.\- ]+)"@m', $tag, $matches );

		return $matches;
	}

	public function extract_shortcodes( $layout ) {

		$pattern = get_shortcode_regex();

		if ( preg_match_all( '@' . $pattern . '@m', $layout, $matches ) ) {
			return $matches;
		}

		return array();
	}

	private function process_step( $queue, $successful, $failed ) {

		$total = count( $queue );
		$done  = true;

		if ( $total > 0 ) {
			$batch = $total >= 5 ? 5 : $total;
			$done  = false;

			foreach ( range( 0, $batch ) as $i ) {
				$src_post  = array_pop( $queue );
				$converted = $this->convert_post( $src_post );
				if ( false !== $converted ) {
					$this_post = array(
						'ID' => $src_post,
						'post_content' => implode($this->new_content)
					);
					wp_update_post( $this_post );
					update_post_meta( $src_post, '_et_pb_use_builder', 'on' );
					array_push( $successful, $src_post );
				} else {
					array_push( $failed, $src_post );
				}
			}
		}

		return array(
			'queue'      => $queue,
			'successful' => $successful,
			'failed'     => $failed,
			'done'       => $done,
		);
	}

	private function get_percentage_complete( $step, $queue, $successful, $failed ) {

		$total_posts = count( $queue ) + count( $successful ) + count( $failed );
		$total_steps = $total_posts > 5 ? ceil( $total_posts % 5 ) : 1;
		$percent     = $step / $total_steps;

		return 1 === $total_steps ? 100 : number_format( $percent * 100, 2 );

	}

	private function convert_post( $src_post ) {

		$result = false;
		try {
			$this->write_log( 'convert_post() called with post_id: ' . $src_post );
			$builder_layout = get_post_meta( $src_post, '_et_builder_settings', true );
			$layout         = is_array( $builder_layout ) && '' !== $builder_layout['layout_shortcode'] ? $builder_layout['layout_shortcode'] : false;
			if ( false !== $layout ) {
				$tags = $this->extract_shortcodes( $layout );

				if ( is_array( $tags ) && ! empty( $tags ) ) {
					$this->new_content = array();
					$this->sort_into_rows( $tags );
					$row_index = 0;
					$this->new_content[] = '[et_pb_section]';
					foreach ( $this->rows as $row => $this_row ) {
						$this->new_content[] = '[et_pb_row make_fullwidth="off" width_unit="off" use_custom_gutter="off"]';

						foreach ( $this_row as $row_contents => $content ) {
							$columns = count( $row_contents );
							$nested_tags = $this->extract_shortcodes( $content );
							if ( is_array( $nested_tags ) && ! empty( $nested_tags ) ) {
								$this->process_nested( $nested_tags );
							}
						}
						$this->new_content[] = '[/et_pb_row]';
						$row_index += 1;
					}
					$this->new_content[] = '[/et_pb_section]';
					$result = true;
					$this->write_log(implode($this->new_content));
				}
			}
		} catch ( Exception $err ) {
			$this->write_log( $err->getMessage() );
		}

		return $result;

	}

	public function process_matches( $index, $tags, $tag ) {

		$old_slug = $tags[2][ $index ];
		$new_slug = isset( $this->map[ $old_slug ]['new_slug'] ) ? $this->map[ $old_slug ]['new_slug'] : '';

		$this->new_content[] = ( '' !== $new_slug ) ? '[' . $new_slug : '[' . $old_slug;

		$attrs     = $tags[3][ $index ];
		$new_attrs = isset( $this->map[ $old_slug ]['new_attrs'] ) ? $this->map[ $old_slug ]['new_attrs'] : array();

		if ( '' === $new_slug ) {
			$this->new_content[] = $attrs;
			$attrs               = '';
		}

		if ( '' !== $attrs ) {
			$attrs = str_replace( array( ' ', '"' ), array( '&', '' ), $attrs );
			$attrs = wp_parse_args( $attrs );
			foreach ( $attrs as $attr => $value ) {
				$old_attr = $attr;
				$new_attr = isset( $this->map[ $old_slug ]['attrs'][ $old_attr ] ) ? $this->map[ $old_slug ]['attrs'][ $old_attr ] : '';

				if ( '' !== $new_attr ) {
					$this->new_content[] = ' ' . $new_attr . '="' . $value . '"';
				}

			}
		}
		if ( ! empty( $new_attrs ) ) {
			foreach ( $new_attrs as $new_attr => $value ) {
				$this->new_content[] = ' ' . $new_attr . '="' . $value . '"';
			}
		}
		$this->new_content[] = ']';

	}

	public function sort_into_rows( $tags ) {

		$this->rows = array();
		$new_row    = array();
		$index      = 0;

		foreach ( $tags[3] as $attrs ) {
			if ( false !== strpos( $attrs, 'first_class="1"' ) && 0 !== $index ) {
				array_push( $this->rows, $new_row );
				$new_row = array();
			}
			array_push( $new_row, $tags[0][ $index ] );
			$index += 1;
		}
		array_push( $this->rows, $new_row );
	}

	public function process_nested( $nested_tags ) {

		$nested_index = 0;
		$nested_total = count( $nested_tags );
		$column_open = false;

		foreach ( $nested_tags[0] as $nested_tag ) {

			$this->process_matches( $nested_index, $nested_tags, $nested_tag );

			if ( is_array( $nested_tags[5] ) && '' !== $nested_tags[5][ $nested_index ] ) {
				$more_nested_tags = $this->extract_shortcodes( $nested_tags[5][ $nested_index ] );
				if ( is_array( $more_nested_tags ) && ! empty( $more_nested_tags ) ) {
					$this->process_nested( $more_nested_tags );
				}
				$this->new_content[] = $nested_tags[5][ $nested_index ];
			}

			$old_slug = $nested_tags[2][ $nested_index ];
			$new_slug = isset( $this->map[ $old_slug ]['new_slug'] ) ? $this->map[ $old_slug ]['new_slug'] : '';
			$this->new_content[] = '[/';
			$this->new_content[] = ( '' !== $new_slug ) ? $new_slug : $old_slug;
			$this->new_content[] = ']';

			$nested_index += 1;
		}

	}

	public function get_eb_to_divi_builder_mapping() {

		return array(
			'et_lb_logo'          => array(
				'new_slug' => 'et_pb_image',
				'attrs'    => array(
					'align'   => 'align',
					'content' => 'url',
				),
			),
			'et_lb_paper'         => array(
				'new_slug' => 'et_pb_text',
				'attrs'    => array(
					'text'      => 'content_new',
					'css_class' => 'module_class',
				),
			),
			'et_lb_video'         => array(
				'new_slug' => 'et_pb_text',
				'attrs'    => array(
					'video_url' => 'content_new',
					'class'     => 'module_class',
				),
			),
			'et_lb_testimonial'   => array(
				'new_slug' => 'et_pb_testimonial',
				'attrs'    => array(
					'image_url'       => 'portrait_url',
					'author_name'     => 'author',
					'author_position' => 'job_title',
					'author_site'     => 'url',
					'content'         => 'content_new',
				),
			),
			'et_lb_slogan'        => array(
				'new_slug' => 'et_pb_text',
				'attrs'    => array(
					'class'   => 'module_class',
					'content' => 'content_new',
				),
			),
			'et_lb_slider'        => array(
				'new_slug'  => 'et_pb_gallery',
				'attrs'     => array(
					'animation_duration' => 'auto_speed',
					'auto_animation'     => 'auto',
					'class'              => 'module_class',
					'images'             => 'src',
				),
				'attrs_new' => array(
					'fullwidth' => 'no',
				),
			),
			'et_lb_button'        => array(),
			'et_lb_bar'           => array(
				'new_slug' => 'et_pb_divider',
				'attrs'    => array(
					'class' => 'module_class',
				),
			),
			'et_lb_list'          => array(
				'new_slug' => 'et_pb_text',
				'attrs'    => array(
					'class'   => 'module_class',
					'content' => 'content_new',
				),
			),
			'et_lb_toggle'        => array(
				'new_slug' => 'et_pb_toggle',
				'attrs'    => array(
					'heading' => 'title',
					'state'   => 'open',
					'class'   => 'module_class',
					'content' => 'content_new',
				),
			),
			'et_lb_simple_slider' => array(
				'new_slug' => 'et_pb_slider',
				'attrs'    => array(
					'class' => 'module_class',
				),
			),
			'et_lb_simple_slide'  => array(
				'new_slug' => 'et_pb_slide',
				'attrs'    => array(
					'class' => 'module_class',
				),
			),
			'et_lb_tabs'          => array(
				'new_slug' => 'et_pb_tabs',
			),
			'et_lb_tab'           => array(
				'new_slug' => 'et_pb_tab',
			),
			'et_lb_pricing_table' => array(
				'new_slug' => 'et_pb_pricing',
			),
			'et_lb_box'           => array(
				'new_slug' => 'et_pb_cta',
				'attrs'    => array(),
			),
			'et_lb_text_block'    => array(
				'new_slug' => 'et_pb_text',
				'attrs'    => array(),
			),
			'et_lb_widget_area'   => array(
				'new_slug' => 'et_pb_slider',
				'attrs'    => array(),
			),
			'et_lb_image'         => array(
				'new_slug' => 'et_pb_image',
				'attrs'    => array(),
			),
			'et_lb_1_2'           => array(
				'new_slug'  => 'et_pb_column',
				'attrs'     => array(),
				'attrs_new' => array(
					'type' => '1_2',
				),
			),
			'et_lb_1_3'           => array(
				'new_slug'  => 'et_pb_column',
				'attrs'     => array(),
				'attrs_new' => array(
					'type' => '1_3',
				),
			),
			'et_lb_1_4'           => array(
				'new_slug'  => 'et_pb_column',
				'attrs'     => array(),
				'attrs_new' => array(
					'type' => '1_4',
				),
			),
			'et_lb_2_3'           => array(
				'new_slug'  => 'et_pb_column',
				'attrs'     => array(),
				'attrs_new' => array(
					'type' => '2_3',
				),
			),
			'et_lb_3_4'           => array(
				'new_slug'  => 'et_pb_column',
				'attrs'     => array(),
				'attrs_new' => array(
					'type' => '3_4',
				),
			),
			'et_lb_resizable'     => array(
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