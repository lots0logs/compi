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

		$this->map              = self::get_eb_to_divi_builder_mapping();
		$this->eb_settings      = get_option( 'et_lb_main_settings' );
		$this->eb_post_types    = isset( $this->eb_settings['post_types'] )
			? (array) $this->eb_settings['post_types']
			: apply_filters( 'et_builder_default_post_types', array( 'post', 'page' ) );
		$this->eb_posts_objects = array();
		$this->new_content      = array();
		$this->row_open         = false;
		$this->rows             = array();
		$this->column_widths    = array();
		$this->section          = array();
	}

	/**
	 * Write to the debug log.
	 *
	 * @since    1.0.0
	 *
	 * @param $log
	 *
	 */
	public static function write_log( $log ) {


		if ( is_array( $log ) || is_object( $log ) ) {
			error_log( print_r( $log, true ) );
		} else {
			error_log( $log );
		}
	}

	/**
	 * Filter admin post table columns for edit.php to add "Builder Status" column.
	 *
	 * @since    1.0.0
	 *
	 * @param $columns
	 *
	 * @return array
	 */
	public function add_conversion_utility_post_columns( $columns ) {

		return array_merge( $columns, array(
			'dots_compi_builder_status' => __( 'Builder Status' ),
		) );

	}


	/**
	 * Display Builder Status for post on edit.php if feature is enabled in our settings.
	 *
	 * @since    1.0.0
	 *
	 * @param $column
	 * @param $post_id
	 */
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

	/**
	 * Process AJAX request made from edit.php to convert selected posts.
	 *
	 * @since    1.0.0
	 *
	 */
	public function do_builder_conversion() {

		if ( empty( $_REQUEST['action'] ) || 'dots_compi_do_builder_conversion' != $_REQUEST['action'] ) {
			$this->write_log( 'request action empty' );

			return;
		}
		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'dots_compi_do_builder_conversion-nonce' ) ) {
			$this->write_log( 'couldnt verify nonce' );

			return;
		}

		ignore_user_abort( true );
		if ( ! ini_get( 'safe_mode' ) ) {
			@set_time_limit( 0 );
		}

		parse_str( $_POST['form'], $form );
		$form = (array) $form;

		// We break the task up into batches. The following variables are passed to/from the client with each batch.
		$step = absint( $_POST['step'] );
		// List of post IDs to convert
		$queue = is_array( $form['post'] ) ? $form['post'] : array();
		// List of post IDs that were converted successfully.
		$successful = is_array( $form['successful'] ) ? $form['successful'] : array();
		// List of post IDs for which conversion failed.
		$failed = is_array( $form['failed'] ) ? $form['failed'] : array();
		$this->write_log( '[\^/\^/\^/^\^/^\^/] do_builder_conversion fired! (AJAX request received) [\^/\^/\^/^\^/^\^/]' );
		$this->write_log( array(
							  'queue'      => $queue,
							  'successful' => $successful,
							  'failed'     => $failed,
						  ) );
		// Start next batch
		$ret = $this->process_step( $queue, $successful, $failed );
		// Calculate the percentage complete so we can send it to te client.
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

	/**
	 * Uses get_shortcode_regex() to extract shortcodes from given string.
	 *
	 * @param $layout
	 *
	 * @return array
	 */
	public static function extract_shortcodes( $layout ) {

		$pattern = get_shortcode_regex();

		if ( preg_match_all( '@' . $pattern . '@m', $layout, $matches ) ) {
			return $matches;
		}

		return array();
	}

	/**
	 * Process the next batch of posts.
	 *
	 * @param $queue
	 * @param $successful
	 * @param $failed
	 *
	 * @return array
	 */
	private function process_step( $queue, $successful, $failed ) {

		$total = count( $queue );
		$done  = true;
		$this->write_log( '[\^/\^/^\^/^\^/\^/\^/^\^/^\^/] process_step fired! [\^/\^/^\^/^\^/\^/\^/^\^/^\^/]' );
		if ( $total > 0 ) {
			// We convert up to 5 posts per step.
			$batch = $total >= 5 ? 5 : $total;
			$done  = false;

			foreach ( range( 0, $batch ) as $i ) {
				// Pop the next post ID from our queue.
				$src_post = array_pop( $queue );
				// Begin conversion
				$converted = $this->convert_post( $src_post );

				if ( false !== $converted ) {
					// Conversion was successful, save new_content to database as the post_content.
					$this_post = array(
						'ID'           => $src_post,
						'post_content' => $this->new_content,
					);
					wp_update_post( $this_post );
					// Enable Divi Builder for the post.
					update_post_meta( $src_post, '_et_pb_use_builder', 'on' );
					// Add post id to our success array.
					array_push( $successful, $src_post );
				} else {
					// Conversion failed. Add post id to the failed array.
					array_push( $failed, $src_post );
				}
			}
		}

		// All posts in this batch have been processed.
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

	/**
	 * Conversion handler function. Converts given post to Divi Builder
	 *
	 * @param $src_post
	 *
	 * @return bool
	 */
	private function convert_post( $src_post ) {

		$result = false;
		try {
			$this->write_log( '[\^/\^/\^/^\^/^\^/] convert_post() called with post_id: ' . $src_post . '[\^/\^/\^/^\^/^\^/]' );
			$builder_layout = get_post_meta( $src_post, '_et_builder_settings', true );
			$layout         = is_array( $builder_layout ) && '' !== $builder_layout['layout_shortcode'] ? $builder_layout['layout_shortcode'] : false;
			if ( false !== $layout ) {
				// If the post has a elegant builder layout, extract the shortcodes.
				$tags = $this->extract_shortcodes( $layout );
				$this->write_log( '[\^/\^/\^/^\^/^\^/] extract_shortcodes complete (This is the top-most level call) [\^/\^/\^/^\^/^\^/]' );
				$this->write_log( $tags );

				if ( is_array( $tags ) && ! empty( $tags ) ) {
					// Since EB doesn't support the concept of rows we sort the shortcodes into rows ourselves.
					$this->sort_into_rows( $tags );

					// We'll put everything in a single section which I think makes the most sense but that may change.
					$this->section = new Dots_Compi_EBuilder_Section();
					foreach ( $this->rows as $rows => &$row ) {
						$this->section->add_child( $row );
					}

					$this->new_content = $this->section->get_as_string();
					$result            = true;
					$this->write_log( $this->new_content );
				}
			}
		} catch ( Exception $err ) {
			$this->write_log( $err->getMessage() );
		}

		return $result;

	}


	/**
	 * Sort the shortcodes into rows
	 *
	 * @param $tags
	 */
	public function sort_into_rows( $tags ) {

		$this->rows      = array();
		$index           = 0;
		$shortcode_index = 0;
		$new_row         = new Dots_Compi_EBuilder_Row( $index );

		foreach ( $tags[2] as $tag => $slug ) {
			$as_string    = isset( $tags[0][ $index ] ) ? $tags[0][ $index ] : '';
			$attrs_string = isset( $tags[3][ $index ] ) ? $tags[3][ $index ] : '';
			$contents     = isset( $tags[5][ $index ] ) ? $tags[5][ $index ] : '';

			$shortcode = new Dots_Compi_EBuilder_Shortcode( $new_row, $shortcode_index, $as_string, $slug, $attrs_string, $contents );
			if ( 0 !== $index && true === $shortcode->is_first ) {
				$this->rows[]    = $new_row;
				$new_row         = new Dots_Compi_EBuilder_Row( $index );
				$shortcode_index = 0;
			}
			$new_row->add_child( $shortcode );
			$index += 1;
			$shortcode_index += 1;
		}
		$this->rows[] = $new_row;
	}

	public static function get_eb_to_divi_builder_mapping() {

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
			'et_lb_button'        => array(
				'new_slug' => 'et_lb_button',
				'attrs'    => array(
					'class' => 'module_class',
				),
			),
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
				'attrs'    => array(),
			),
			'et_lb_pricing_table' => array(
				'new_slug' => 'et_pb_pricing',
				'attrs'    => array(),
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
				'new_slug'  => 'et_pb_column',
				'attrs'     => array(),
				'attrs_new' => array(),
			),
		);
	}
}


class Dots_Compi_EBuilder_Element {

	protected $_children = array();
	protected $_attrs = array();
	public $as_string = '';
	public $contents = '';
	public $column_wrapper = false;
	public $is_row = false;
	public $slug = '';
	public $new_slug = '';

	public function get_len() {

		return count( $this->_children );
	}

	public function add_child( &$child ) {

		$this->_children[] = $child;
	}

	public function get_child( $index ) {

		$child = '';
		if ( isset( $this->_children[ $index ] ) ) {
			$child = $this->_children[ $index ];
		}

		return $child;
	}

	public function get_as_string() {

		if ( '' === $this->as_string ) {

			$self_string = '[' . $this->new_slug;

			if ( ! empty( $this->_attrs ) ) {
				foreach ( $this->_attrs as $attr => $value ) {
					$self_string .= ' ' . $attr . '="' . $value . '"';
				}
			}
			$self_string .= ']';
			if ( ! empty( $this->_children ) ) {
				foreach ( $this->_children as $child_index => $child ) {
					$self_string .= $child->get_as_string();
				}
			} else {
				$self_string .= $this->contents;
			}

			$self_string .= '[/' . $this->new_slug . ']';

			if ( false !== $this->column_wrapper ) {
				$self_string = '[et_pb_column type="' . $this->column_wrapper . '"]' . $self_string . '[/et_pb_column]';
			}

		} else {
			$self_string = $this->as_string;
		}

		return $self_string;
	}
}

class Dots_Compi_EBuilder_Section extends Dots_Compi_EBuilder_Element {

	public function __construct() {

		$this->slug     = 'et_pb_section';
		$this->new_slug = $this->slug;
	}
}

class Dots_Compi_EBuilder_Row extends Dots_Compi_EBuilder_Element {

	public function __construct( $index ) {

		$this->is_row   = true;
		$this->slug     = 'et_pb_row';
		$this->new_slug = $this->slug;
		$this->index    = $index;
		$this->_attrs   = array(
			'make_fullwidth'    => "off",
			'width_unit'        => "off",
			'use_custom_gutter' => "off",
		);
	}
}

class Dots_Compi_EBuilder_Shortcode extends Dots_Compi_EBuilder_Element {
	public function __construct( &$parent, $index, $as_string, $slug, $attrs_string, $contents ) {

		$this->slug           = $slug;
		$this->index          = $index;
		$this->parent         = &$parent;
		$this->column_wrapper = false;
		$this->map            = Dots_Compi_Conversion_Util::get_eb_to_divi_builder_mapping();
		$this->new_slug       = isset( $this->map[ $slug ]['new_slug'] ) ? $this->map[ $slug ]['new_slug'] : '';
		$this->is_column      = preg_match( '/(et_lb_\d_\d)/', $this->slug );
		$this->is_resizable   = preg_match( '/(resizable)/', $this->slug );
		$this->contents       = $contents;
		$this->old_attrs      = $this->parse_attributes( $attrs_string );
		$this->is_first       = array_key_exists( 'first_class', $this->old_attrs );


		$this->process_children();
		$this->maybe_add_column_wrapper();
	}

	/**
	 * Use our mapping array to replace shortcode attributes with counterpart in Divi Builder.
	 *
	 * @param $attrs_string
	 *
	 * @return array
	 */
	private function parse_attributes( $attrs_string ) {

		$attrs = str_replace( array( ' ', '"' ), array( '&', '' ), $attrs_string );
		$attrs = wp_parse_args( $attrs );
		$res   = array();

		foreach ( $attrs as $old_attr => $value ) {
			$new_attr = isset( $this->map[ $this->slug ]['attrs'][ $old_attr ] ) ? $this->map[ $this->slug ]['attrs'][ $old_attr ] : '';
			if ( '' !== $old_attr && '' !== $value ) {
				$res[ $old_attr ] = $value;
			}
			if ( '' !== $new_attr ) {
				$this->_attrs[ $new_attr ] = $value;
			}
		}

		$other_attrs = isset( $this->map[ $this->slug ]['attrs_new'] ) ? $this->map[ $this->slug ]['attrs_new'] : array();

		if ( is_array( $other_attrs ) && ! empty( $other_attrs ) ) {
			foreach ( $other_attrs as $other_attr => $other_value ) {
				$this->_attrs[ $other_attr ] = $other_value;
			}
		}

		return $res;

	}

	private function process_children() {

		$nested_tags = Dots_Compi_Conversion_Util::extract_shortcodes( $this->contents );
		$index       = 0;
		Dots_Compi_Conversion_Util::write_log( '[\^/\^/\^/^\^/^\^/] PROCESS CHILDREN: extract_shortcodes complete [\^/\^/\^/^\^/^\^/]' );
		Dots_Compi_Conversion_Util::write_log( $nested_tags );
		if ( is_array( $nested_tags ) && ! empty( $nested_tags ) ) {
			foreach ( $nested_tags[2] as $nested_tag => $slug ) {
				$as_string    = isset( $nested_tags[0][ $index ] ) ? $nested_tags[0][ $index ] : '';
				$attrs_string = isset( $nested_tags[3][ $index ] ) ? $nested_tags[3][ $index ] : '';
				$contents     = isset( $nested_tags[5][ $index ] ) ? $nested_tags[5][ $index ] : '';

				$shortcode = new Dots_Compi_EBuilder_Shortcode( $this, $index, $as_string, $slug, $attrs_string, $contents );

				$this->_children[] = $shortcode;

				$index += 1;

			}
		}
	}

	private function maybe_add_column_wrapper() {

		if ( true === $this->parent->is_row && ( false === $this->is_column || true === $this->is_resizable ) ) {
			if ( 2 === $this->parent->get_len() && array_key_exists( $this->old_attrs, 'width' ) ) {
				$width         = (int) $this->old_attrs['width'];
				$sibling_index = ( 0 === $this->index ) ? 1 : 0;
				$sibling       = $this->parent->get_child( $sibling_index );
				$column_string = $sibling->column_wrapper;
				if ( '1_4' === $column_string || $this->in_range( $width, 66, 100 ) ) {
					$this->column_wrapper = '3_4';
				} elseif ( '1_3' === $column_string || $this->in_range( $width, 56, 65 ) ) {
					$this->column_wrapper = '2_3';
				} elseif ( '1_2' === $column_string || $this->in_range( $width, 49, 55 ) ) {
					$this->column_wrapper = '1_2';
				} elseif ( '2_3' === $column_string || $this->in_range( $width, 26, 48 ) ) {
					$this->column_wrapper = '1_3';
				} elseif ( '3_4' === $column_string || $this->in_range( $width, 0, 25 ) ) {
					$this->column_wrapper = '1_4';
				}

			} elseif ( 2 === $this->parent->get_len() ) {
				$this->column_wrapper = '1_2';
			} elseif ( 1 === $this->parent->get_len() ) {
				$this->column_wrapper = '4_4';
			} elseif ( 3 === $this->parent->get_len() ) {
				$this->column_wrapper = '1_3';
			} elseif ( 4 === $this->parent->get_len() ) {
				$this->column_wrapper = '1_4';
			} else {
				Dots_Compi_Conversion_Util::write_log( array( 'COLUMN WRAPPER ERROR' => 'TRUE', '$this->column_wrapper' => $this->column_wrapper ) );
			}

			if ( true === $this->is_resizable ) {
				$this->_attrs['type'] = $this->column_wrapper;
				$this->column_wrapper = false;
			}
		}
	}


	private function in_range( $val, $min, $max ) {

		return ( $val >= $min && $val <= $max );
	}


}