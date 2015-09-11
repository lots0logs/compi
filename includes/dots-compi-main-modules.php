<?php
/*
 * dots-compi-main-modules.php
 *
 * Copyright © 2015 wpdots
 * Copyright © 2015 Elegant Themes
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
 * Class Dots_ET_Builder_Module_Helper
 */
trait Dots_ET_Builder_Module_Helper {

	/**
	 * This is a utility class to reduce code duplication amongst the builder module objects
	 * since PHP doesn't support multiple inher
	 *
	 * @param $features
	 * @param $caller
	 */
	function __construct( $features ) {

		parent::__construct();
		$this->features                  = $features;
		$this->filter_fields             = 'et_builder_module_fields_' . $this->slug;
		$this->filter_whitelisted_fields = 'et_builder_module_whitelisted_fields_' . $this->slug;
		$this->dots_whitelisted_fields   = array( 'use_regular_posts', 'include_regular_categories' );
		$this->dots_fields_defaults      = array( 'use_regular_posts' => array( 'off', 'add_default_setting' ) );
		$this->maybe_enable_overrides();
	}

	public function maybe_enable_overrides() {

		if ( isset( $this->features['module_enhancements'] ) && $this->features['module_enhancements'] > 0 ) {
			add_filter( $this->filter_fields, array( $this, 'dots_fields', ) );
			add_filter( $this->filter_whitelisted_fields, array( $this, 'dots_whitelisted_fields', ) );
			add_action( 'wp', array( $this, 'do_override_shortcode' ), 99 );
		}

	}

	public function dots_whitelisted_fields( $fields ) {

		return array_merge( $this->dots_whitelisted_fields, $fields );
	}

	public function do_override_shortcode() {

		remove_shortcode( $this->slug );
		add_shortcode( $this->slug, array( $this, '_shortcode_callback' ) );
	}

	/**
	 * @param $fields
	 *
	 * @return array
	 */
	function process_fields( $fields ) {

		$extra_fields = $this->dots_extra_fields( $fields );

		return $extra_fields;
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
}

trait Dots_ET_Builder_Module_Helper_Portfolio {
	/**
	 * @param $fields
	 *
	 * @return array
	 */
	public function dots_extra_fields( $fields ) {

		$extra_fields = array(
			'use_regular_posts'          => array(
				'label'             => __( 'Use Regular Posts', 'dots_compi' ),
				'type'              => 'yes_no_button',
				'options'           => array(
					'off' => __( 'No', 'et_builder' ),
					'on'  => __( 'Yes', 'et_builder' ),
				),
				'option_category'   => 'configuration',
				'description'       => __( 'Display regular posts instead of project posts.', 'dots_compi' ),
				'affects'           => array(
					'[for=et_pb_include_regular_categories]',
					'[for=et_pb_include_categories]',
				),
				'default'           => 'off',
				'shortcode_default' => 'off',
			),
			'include_regular_categories' => array(
				'label'            => __( 'Include Categories', 'et_builder' ),
				'renderer'         => 'et_builder_include_categories_option',
				'renderer_options' => array(
					'use_terms' => false,
				),
				'option_category'  => 'configuration',
				'depends_show_if'  => 'on',
				'description'      => __( 'Select the categories that you would like to include in the feed.', 'et_builder' ),
			),
		);

		$all_fields = array_slice( $fields, 0, 2, true ) +
		              $extra_fields +
		              array_slice( $fields, 2, null, true );

		$all_fields['include_categories']['depends_show_if'] = 'off';

		return $all_fields;
	}
}


/**
 * Class Dots_ET_Builder_Module_Portfolio
 */
class Dots_ET_Builder_Module_Portfolio extends ET_Builder_Module_Portfolio {

	use Dots_ET_Builder_Module_Helper, Dots_ET_Builder_Module_Helper_Portfolio;

	/**
	 * @param $atts
	 * @param null $content
	 * @param $function_name
	 *
	 * @return string
	 */
	function shortcode_callback( $atts, $content = null, $function_name ) {

		$module_id                  = $this->shortcode_atts['module_id'];
		$module_class               = $this->shortcode_atts['module_class'];
		$fullwidth                  = $this->shortcode_atts['fullwidth'];
		$posts_number               = $this->shortcode_atts['posts_number'];
		$use_regular_posts          = $this->shortcode_atts['use_regular_posts'];
		$include_categories         = $this->shortcode_atts['include_categories'];
		$include_regular_categories = $this->shortcode_atts['include_regular_categories'];
		$show_title                 = $this->shortcode_atts['show_title'];
		$show_categories            = $this->shortcode_atts['show_categories'];
		$show_pagination            = $this->shortcode_atts['show_pagination'];
		$background_layout          = $this->shortcode_atts['background_layout'];
		$zoom_icon_color            = $this->shortcode_atts['zoom_icon_color'];
		$hover_overlay_color        = $this->shortcode_atts['hover_overlay_color'];
		$hover_icon                 = $this->shortcode_atts['hover_icon'];
		global $paged;
		$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );
		if ( '' !== $zoom_icon_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_overlay:before',
				'declaration' => sprintf(
					'color: %1$s !important;',
					esc_html( $zoom_icon_color )
				),
			) );
		}
		if ( '' !== $hover_overlay_color ) {
			ET_Builder_Element::set_style( $function_name, array(
				'selector'    => '%%order_class%% .et_overlay',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $hover_overlay_color )
				),
			) );
		}
		$container_is_closed = false;
		$args                = array(
			'posts_per_page' => (int) $posts_number,
			'post_type'      => 'on' === $use_regular_posts ? 'post' : 'project',
		);
		$et_paged            = is_front_page() ? get_query_var( 'page' ) : get_query_var( 'paged' );
		if ( is_front_page() ) {
			$paged = $et_paged;
		}
		if ( '' !== $include_categories && 'off' === $use_regular_posts ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'project_category',
					'field'    => 'id',
					'terms'    => explode( ',', $include_categories ),
					'operator' => 'IN',
				),
			);
		} elseif ( 'on' === $use_regular_posts && '' !== $include_regular_categories ) {
			$args['cat'] = $include_categories;
		}
		if ( ! is_search() ) {
			$args['paged'] = $et_paged;
		}
		$main_post_class = sprintf(
			'et_pb_portfolio_item%1$s',
			( 'on' !== $fullwidth ? ' et_pb_grid_item' : '' )
		);
		ob_start();
		query_posts( $args );
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class( $main_post_class ); ?>>

					<?php
					$thumb     = '';
					$width     = 'on' === $fullwidth ? 1080 : 400;
					$width     = (int) apply_filters( 'et_pb_portfolio_image_width', $width );
					$height    = 'on' === $fullwidth ? 9999 : 284;
					$height    = (int) apply_filters( 'et_pb_portfolio_image_height', $height );
					$classtext = 'on' === $fullwidth ? 'et_pb_post_main_image' : '';
					$titletext = get_the_title();
					$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
					$thumb     = $thumbnail["thumb"];
					$term_name = 'on' === $use_regular_posts ? 'category' : 'project_category';
					if ( '' !== $thumb ) : ?>
						<a href="<?php the_permalink(); ?>">
							<?php if ( 'on' !== $fullwidth ) : ?>
							<span class="et_portfolio_image">
					<?php endif; ?>
					<?php print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height ); ?>
					<?php if ( 'on' !== $fullwidth ) :
					$data_icon = '' !== $hover_icon
						? sprintf(
							' data-icon="%1$s"',
							esc_attr( et_pb_process_font_icon( $hover_icon ) )
						)
						: '';
					printf( '<span class="et_overlay%1$s"%2$s></span>',
						( '' !== $hover_icon ? ' et_pb_inline_icon' : '' ),
						$data_icon
					);
					?>
						</span>
						<?php endif; ?>
						</a>
						<?php
					endif;
					?>

					<?php if ( 'on' === $show_title ) : ?>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php endif; ?>

					<?php if ( 'on' === $show_categories ) : ?>
						<p class="post-meta"><?php echo get_the_term_list( get_the_ID(), $term_name, '', ', ' ); ?></p>
					<?php endif; ?>

				</div> <!-- .et_pb_portfolio_item -->
			<?php }
			if ( 'on' === $show_pagination && ! is_search() ) {
				echo '</div> <!-- .et_pb_portfolio -->';
				$container_is_closed = true;
				if ( function_exists( 'wp_pagenavi' ) ) {
					wp_pagenavi();
				} else {
					get_template_part( 'includes/navigation', 'index' );
				}
			}
			wp_reset_query();
		} else {
			get_template_part( 'includes/no-results', 'index' );
		}
		$posts = ob_get_contents();
		ob_end_clean();
		$class  = " et_pb_module et_pb_bg_layout_{$background_layout}";
		$output = sprintf(
			'<div%5$s class="%1$s%3$s%6$s">
				%2$s
			%4$s',
			( 'on' === $fullwidth ? 'et_pb_portfolio' : 'et_pb_portfolio_grid clearfix' ),
			$posts,
			esc_attr( $class ),
			( ! $container_is_closed ? '</div> <!-- .et_pb_portfolio -->' : '' ),
			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
		);

		return $output;
	}
}

///**
// * Class Dots_ET_Builder_Module_Filterable_Portfolio
// */
//class Dots_ET_Builder_Module_Filterable_Portfolio extends ET_Builder_Module_Filterable_Portfolio {
//	/**
//	 * @param $features
//	 */
//	function __construct( $features ) {
//
//		parent::__construct();
//		$this->features                                  = $features;
//		$this->helper                                    = null;
//		$this->dots_whitelisted_fields                   = array( 'use_regular_posts', 'include_regular_categories' );
//		$this->dots_fields_defaults['use_regular_posts'] = array( 'off', 'add_default_setting' );
//		$this->init_helper_class();
//
//	}
//
//	public function init_helper_class() {
//
//		$this->whitelisted_fields = array_merge( $this->dots_whitelisted_fields, $this->whitelisted_fields );
//		$this->fields_defaults    = array_merge( $this->dots_fields_defaults, $this->fields_defaults );
//
//		$this->helper = new Dots_ET_Builder_Module_Helper( $this->features, $this );
//		$this->helper->maybe_enable_overrides();
//	}
//
//
//	/**
//	 * @param $fields
//	 *
//	 * @return array
//	 */
//	public function dots_extra_fields( $fields ) {
//
//		$extra_fields = array(
//			'use_regular_posts'          => array(
//				'label'             => __( 'Use Regular Posts', 'dots_compi' ),
//				'type'              => 'yes_no_button',
//				'options'           => array(
//					'off' => __( 'No', 'et_builder' ),
//					'on'  => __( 'Yes', 'et_builder' ),
//				),
//				'option_category'   => 'configuration',
//				'description'       => __( 'Display regular posts instead of project posts.', 'dots_compi' ),
//				'affects'           => array(
//					'[for=et_pb_include_regular_categories]',
//					'[for=et_pb_include_categories]',
//				),
//				'default'           => 'off',
//				'shortcode_default' => 'off',
//			),
//			'include_regular_categories' => array(
//				'label'            => __( 'Include Categories', 'et_builder' ),
//				'renderer'         => 'et_builder_include_categories_option',
//				'renderer_options' => array(
//					'use_terms' => false,
//				),
//				'option_category'  => 'configuration',
//				'depends_show_if'  => 'on',
//				'description'      => __( 'Select the categories that you would like to include in the feed.', 'et_builder' ),
//			),
//		);
//
//		$all_fields = array_slice( $fields, 0, 2, true ) +
//		              $extra_fields +
//		              array_slice( $fields, 2, null, true );
//
//		$all_fields['include_categories']['depends_show_if'] = 'off';
//
//		return $all_fields;
//	}
//
//	/**
//	 * @param $fields
//	 *
//	 * @return array
//	 */
//	function process_fields( $fields ) {
//
//		$extra_fields = $this->dots_extra_fields( $fields );
//
//		return $extra_fields;
//	}
//
//	/**
//	 * @param $atts
//	 * @param null $content
//	 * @param $function_name
//	 *
//	 * @return string
//	 */
//	function shortcode_callback( $atts, $content = null, $function_name ) {
//
//		$module_id                  = $this->shortcode_atts['module_id'];
//		$module_class               = $this->shortcode_atts['module_class'];
//		$fullwidth                  = $this->shortcode_atts['fullwidth'];
//		$posts_number               = $this->shortcode_atts['posts_number'];
//		$use_regular_posts          = $this->shortcode_atts['use_regular_posts'];
//		$include_categories         = $this->shortcode_atts['include_categories'];
//		$include_regular_categories = $this->shortcode_atts['include_regular_categories'];
//		$show_title                 = $this->shortcode_atts['show_title'];
//		$show_categories            = $this->shortcode_atts['show_categories'];
//		$show_pagination            = $this->shortcode_atts['show_pagination'];
//		$background_layout          = $this->shortcode_atts['background_layout'];
//		$hover_icon                 = $this->shortcode_atts['hover_icon'];
//		$zoom_icon_color            = $this->shortcode_atts['zoom_icon_color'];
//		$hover_overlay_color        = $this->shortcode_atts['hover_overlay_color'];
//		$module_class               = ET_Builder_Element::add_module_order_class( $module_class, $function_name );
//		wp_enqueue_script( 'hashchange' );
//		$args = array();
//		if ( '' !== $zoom_icon_color ) {
//			ET_Builder_Element::set_style( $function_name, array(
//				'selector'    => '%%order_class%% .et_overlay:before',
//				'declaration' => sprintf(
//					'color: %1$s !important;',
//					esc_html( $zoom_icon_color )
//				),
//			) );
//		}
//		if ( '' !== $hover_overlay_color ) {
//			ET_Builder_Element::set_style( $function_name, array(
//				'selector'    => '%%order_class%% .et_overlay',
//				'declaration' => sprintf(
//					'background-color: %1$s;',
//					esc_html( $hover_overlay_color )
//				),
//			) );
//		}
//		if ( 'on' === $show_pagination ) {
//			$args['nopaging'] = true;
//		} else {
//			$args['posts_per_page'] = (int) $posts_number;
//		}
//		if ( '' !== $include_categories && 'off' === $use_regular_posts ) {
//			$args['tax_query'] = array(
//				array(
//					'taxonomy' => 'project_category',
//					'field'    => 'id',
//					'terms'    => explode( ',', $include_categories ),
//					'operator' => 'IN',
//				),
//			);
//		} elseif ( 'on' === $use_regular_posts && '' !== $include_regular_categories ) {
//			$args['cat'] = explode( ',', $include_categories );
//		}
//		if ( 'on' === $use_regular_posts ) {
//			$projects  = new WP_Query( $args );
//			$term_slug = 'category';
//			$cat_class = 'category_';
//		} else {
//			$projects  = et_divi_get_projects( $args );
//			$term_slug = 'project_category';
//			$cat_class = 'project_category_';
//		}
//
//		$categories_included = array();
//		ob_start();
//		if ( $projects->post_count > 0 ) {
//			while ( $projects->have_posts() ) {
//				$projects->the_post();
//				$category_classes = array();
//				$categories       = get_the_terms( get_the_ID(), $term_slug );
//				if ( $categories ) {
//					foreach ( $categories as $category ) {
//						$category_classes[]    = $cat_class . urldecode( $category->slug );
//						$categories_included[] = $category->term_id;
//					}
//				}
//				$category_classes = implode( ' ', $category_classes );
//				$main_post_class  = sprintf(
//					'et_pb_portfolio_item%1$s %2$s',
//					( 'on' !== $fullwidth ? ' et_pb_grid_item' : '' ),
//					$category_classes
//				);
//				?>
<!--				<div id="post---><?php //the_ID(); ?><!--" --><?php //post_class( $main_post_class ); ?><!-->-->
<!--					--><?php
//					$thumb     = '';
//					$width     = 'on' === $fullwidth ? 1080 : 400;
//					$width     = (int) apply_filters( 'et_pb_portfolio_image_width', $width );
//					$height    = 'on' === $fullwidth ? 9999 : 284;
//					$height    = (int) apply_filters( 'et_pb_portfolio_image_height', $height );
//					$classtext = 'on' === $fullwidth ? 'et_pb_post_main_image' : '';
//					$titletext = get_the_title();
//					$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
//					$thumb     = $thumbnail["thumb"];
//					if ( '' !== $thumb ) : ?>
<!--						<a href="--><?php //the_permalink(); ?><!--">-->
<!--							--><?php //if ( 'on' !== $fullwidth ) : ?>
<!--							<span class="et_portfolio_image">-->
<!--						--><?php //endif; ?>
<!--						--><?php //print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height ); ?>
<!--						--><?php //if ( 'on' !== $fullwidth ) :
//						$data_icon = '' !== $hover_icon
//							? sprintf(
//								' data-icon="%1$s"',
//								esc_attr( et_pb_process_font_icon( $hover_icon ) )
//							)
//							: '';
//						printf( '<span class="et_overlay%1$s"%2$s></span>',
//							( '' !== $hover_icon ? ' et_pb_inline_icon' : '' ),
//							$data_icon
//						);
//						?>
<!--							</span>-->
<!--						--><?php //endif; ?>
<!--						</a>-->
<!--						--><?php
//					endif;
//					?>
<!---->
<!--					--><?php //if ( 'on' === $show_title ) : ?>
<!--						<h2><a href="--><?php //the_permalink(); ?><!--">--><?php //the_title(); ?><!--</a></h2>-->
<!--					--><?php //endif; ?>
<!---->
<!--					--><?php //if ( 'on' === $show_categories ) : ?>
<!--						<p class="post-meta">--><?php //echo get_the_term_list( get_the_ID(), $term_slug, '', ', ' ); ?><!--</p>-->
<!--					--><?php //endif; ?>
<!---->
<!--				</div><!-- .et_pb_portfolio_item -->-->
<!--				--><?php
//			}
//		}
//		wp_reset_postdata();
//		$posts               = ob_get_clean();
//		$categories_included = explode( ',', $include_categories );
//		$terms_args          = array(
//			'include' => $categories_included,
//			'orderby' => 'name',
//			'order'   => 'ASC',
//		);
//		$terms               = get_terms( $term_slug, $terms_args );
//		$category_filters    = '<ul class="clearfix">';
//		$category_filters .= sprintf( '<li class="et_pb_portfolio_filter et_pb_portfolio_filter_all"><a href="#" class="active" data-category-slug="all">%1$s</a></li>',
//			esc_html__( 'All', 'et_builder' )
//		);
//		foreach ( $terms as $term ) {
//			$category_filters .= sprintf( '<li class="et_pb_portfolio_filter"><a href="#" data-category-slug="%1$s">%2$s</a></li>',
//				esc_attr( urldecode( $term->slug ) ),
//				esc_html( $term->name )
//			);
//		}
//		$category_filters .= '</ul>';
//		$class  = " et_pb_module et_pb_bg_layout_{$background_layout}";
//		$output = sprintf(
//			'<div%5$s class="et_pb_filterable_portfolio %1$s%4$s%6$s" data-posts-number="%7$d"%10$s>
//				<div class="et_pb_portfolio_filters clearfix">%2$s</div><!-- .et_pb_portfolio_filters -->
//				<div class="et_pb_portfolio_items_wrapper %8$s">
//					<div class="et_pb_portfolio_items">%3$s</div><!-- .et_pb_portfolio_items -->
//				</div>
//				%9$s
//			</div> <!-- .et_pb_filterable_portfolio -->',
//			( 'on' === $fullwidth ? 'et_pb_filterable_portfolio_fullwidth' : 'et_pb_filterable_portfolio_grid clearfix' ),
//			$category_filters,
//			$posts,
//			esc_attr( $class ),
//			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
//			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
//			esc_attr( $posts_number ),
//			( 'on' === $show_pagination ? '' : 'no_pagination' ),
//			( 'on' === $show_pagination ? '<div class="et_pb_portofolio_pagination"></div>' : '' ),
//			is_rtl() ? ' data-rtl="true"' : ''
//		);
//
//		return $output;
//	}
//}
//
///**
// * Class Dots_ET_Builder_Module_Fullwidth_Portfolio
// */
//class Dots_ET_Builder_Module_Fullwidth_Portfolio extends ET_Builder_Module_Fullwidth_Portfolio {
//	/**
//	 * @param $features
//	 */
//	function __construct( $features ) {
//
//		parent::__construct();
//		$this->features                                  = $features;
//		$this->helper                                    = null;
//		$this->dots_whitelisted_fields                   = array( 'use_regular_posts', 'include_regular_categories' );
//		$this->dots_fields_defaults['use_regular_posts'] = array( 'off', 'add_default_setting' );
//		$this->init_helper_class();
//
//	}
//
//	public function init_helper_class() {
//
//		$this->whitelisted_fields = array_merge( $this->dots_whitelisted_fields, $this->whitelisted_fields );
//		$this->fields_defaults    = array_merge( $this->dots_fields_defaults, $this->fields_defaults );
//
//		$this->helper = new Dots_ET_Builder_Module_Helper( $this->features, $this );
//		$this->helper->maybe_enable_overrides();
//	}
//
//
//	/**
//	 * @param $fields
//	 *
//	 * @return array
//	 */
//	public function dots_extra_fields( $fields ) {
//
//		$extra_fields = array(
//			'use_regular_posts'          => array(
//				'label'             => __( 'Use Regular Posts', 'dots_compi' ),
//				'type'              => 'yes_no_button',
//				'options'           => array(
//					'off' => __( 'No', 'et_builder' ),
//					'on'  => __( 'Yes', 'et_builder' ),
//				),
//				'option_category'   => 'configuration',
//				'description'       => __( 'Display regular posts instead of project posts.', 'dots_compi' ),
//				'affects'           => array(
//					'[for=et_pb_include_regular_categories]',
//					'[for=et_pb_include_categories]',
//				),
//				'default'           => 'off',
//				'shortcode_default' => 'off',
//			),
//			'include_regular_categories' => array(
//				'label'            => __( 'Include Categories', 'et_builder' ),
//				'renderer'         => 'et_builder_include_categories_option',
//				'renderer_options' => array(
//					'use_terms' => false,
//				),
//				'option_category'  => 'configuration',
//				'depends_show_if'  => 'on',
//				'description'      => __( 'Select the categories that you would like to include in the feed.', 'et_builder' ),
//			),
//		);
//
//		$all_fields = array_slice( $fields, 0, 2, true ) +
//		              $extra_fields +
//		              array_slice( $fields, 2, null, true );
//
//		$all_fields['include_categories']['depends_show_if'] = 'off';
//
//		return $all_fields;
//	}
//
//	/**
//	 * @param $fields
//	 *
//	 * @return array
//	 */
//	function process_fields( $fields ) {
//
//		$extra_fields = $this->dots_extra_fields( $fields );
//
//		return $extra_fields;
//	}
//
//	/**
//	 * @param $atts
//	 * @param null $content
//	 * @param $function_name
//	 *
//	 * @return string
//	 */
//	function shortcode_callback( $atts, $content = null, $function_name ) {
//
//		$title                      = $this->shortcode_atts['title'];
//		$module_id                  = $this->shortcode_atts['module_id'];
//		$module_class               = $this->shortcode_atts['module_class'];
//		$fullwidth                  = $this->shortcode_atts['fullwidth'];
//		$include_categories         = $this->shortcode_atts['include_categories'];
//		$include_regular_categories = $this->shortcode_atts['include_regular_categories'];
//		$posts_number               = $this->shortcode_atts['posts_number'];
//		$use_regular_posts          = $this->shortcode_atts['use_regular_posts'];
//		$show_title                 = $this->shortcode_atts['show_title'];
//		$show_date                  = $this->shortcode_atts['show_date'];
//		$background_layout          = $this->shortcode_atts['background_layout'];
//		$auto                       = $this->shortcode_atts['auto'];
//		$auto_speed                 = $this->shortcode_atts['auto_speed'];
//		$args                       = array();
//		if ( is_numeric( $posts_number ) && $posts_number > 0 ) {
//			$args['posts_per_page'] = $posts_number;
//		} else {
//			$args['nopaging'] = true;
//		}
//		if ( '' !== $include_categories && 'off' === $use_regular_posts ) {
//			$args['tax_query'] = array(
//				array(
//					'taxonomy' => 'project_category',
//					'field'    => 'id',
//					'terms'    => explode( ',', $include_categories ),
//					'operator' => 'IN',
//				),
//			);
//		} elseif ( 'on' === $use_regular_posts && '' !== $include_regular_categories ) {
//			$args['cat'] = $include_categories;
//		}
//		if ( 'on' === $use_regular_posts ) {
//			$projects = new WP_Query( $args );
//		} else {
//			$projects = et_divi_get_projects( $args );
//		}
//		ob_start();
//		if ( $projects->post_count > 0 ) {
//			while ( $projects->have_posts() ) {
//				$projects->the_post();
//				?>
<!--				<div id="post---><?php //the_ID(); ?><!--" --><?php //post_class( 'et_pb_portfolio_item et_pb_grid_item ' ); ?><!-->-->
<!--					--><?php
//					$thumb  = '';
//					$width  = 320;
//					$width  = (int) apply_filters( 'et_pb_portfolio_image_width', $width );
//					$height = 241;
//					$height = (int) apply_filters( 'et_pb_portfolio_image_height', $height );
//					list( $thumb_src, $thumb_width, $thumb_height ) = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), array(
//						$width,
//						$height,
//					) );
//					$orientation = ( $thumb_height > $thumb_width ) ? 'portrait' : 'landscape';
//					if ( '' !== $thumb_src ) : ?>
<!--						<div class="et_pb_portfolio_image --><?php //echo esc_attr( $orientation ); ?><!--">-->
<!--							<a href="--><?php //the_permalink(); ?><!--">-->
<!--								<img src="--><?php //echo esc_attr( $thumb_src ); ?><!--" alt="--><?php //echo esc_attr( get_the_title() ); ?><!--"/>-->
<!---->
<!--								<div class="meta">-->
<!--									<span class="et_overlay"></span>-->
<!---->
<!--									--><?php //if ( 'on' === $show_title ) : ?>
<!--										<h3>--><?php //the_title(); ?><!--</h3>-->
<!--									--><?php //endif; ?>
<!---->
<!--									--><?php //if ( 'on' === $show_date ) : ?>
<!--										<p class="post-meta">--><?php //echo get_the_date(); ?><!--</p>-->
<!--									--><?php //endif; ?>
<!--								</div>-->
<!--							</a>-->
<!--						</div>-->
<!--					--><?php //endif; ?>
<!--				</div>-->
<!--				--><?php
//			}
//		}
//		wp_reset_postdata();
//		$posts  = ob_get_clean();
//		$class  = " et_pb_module et_pb_bg_layout_{$background_layout}";
//		$output = sprintf(
//			'<div%4$s class="et_pb_fullwidth_portfolio %1$s%3$s%5$s" data-auto-rotate="%6$s" data-auto-rotate-speed="%7$s">
//				%8$s
//				<div class="et_pb_portfolio_items clearfix" data-portfolio-columns="">
//					%2$s
//				</div><!-- .et_pb_portfolio_items -->
//			</div> <!-- .et_pb_fullwidth_portfolio -->',
//			( 'on' === $fullwidth ? 'et_pb_fullwidth_portfolio_carousel' : 'et_pb_fullwidth_portfolio_grid clearfix' ),
//			$posts,
//			esc_attr( $class ),
//			( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
//			( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' ),
//			( '' !== $auto && in_array( $auto, array( 'on', 'off' ) ) ? esc_attr( $auto ) : 'off' ),
//			( '' !== $auto_speed && is_numeric( $auto_speed ) ? esc_attr( $auto_speed ) : '7000' ),
//			( '' !== $title ? sprintf( '<h2>%s</h2>', esc_html( $title ) ) : '' )
//		);
//
//		return $output;
//	}
//}
