<?php
/*
 * index.php
 *
 * Copyright © 2015 wpdots
 *
 * This file is part of Compi.
 *
 * Portions of the code in this file are based on code from
 * other open source products. Where applicable, the following applies:
 *
 * Copyright © 2015 Elegant Themes
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

get_header();

global $et_theme_options;

if ( ! isset( $et_theme_options ) ) {
	$et_theme_options = get_option( 'et_divi_options' );
}

$meta_date         = $et_theme_options['divi_date_format'];
$show_thumbnail    = $et_theme_options['divi_thumbnails_index'];
$show_content      = $et_theme_options['divi_blog_style'];
$show_author       = in_array( 'author', $et_theme_options['divi_postinfo1'] );
$show_date         = in_array( 'date', $et_theme_options['divi_postinfo1'] );
$show_categories   = in_array( 'categories', $et_theme_options['divi_postinfo1'] );
$show_comments     = in_array( 'comments', $et_theme_options['divi_postinfo1'] );
$background_layout = 'light';


?>

	<div id="main-content">
		<div id="content-area" class="<?php if ( false === $dots_compi_sidebar ) {
			echo 'et_full_width_page ';
		} ?>clearfix">
				<?php
				//$compi_pub->write_log( $et_theme_options );

				$container_is_closed = false;

				wp_enqueue_script( 'salvattore' );

				ob_start();

				if ( have_posts() ) {
					while ( have_posts() ) {
						the_post();
						$post_format    = et_pb_post_format();
						$thumb          = '';
						$width          = 400;
						$width          = (int) apply_filters( 'dots_compi_et_pb_blog_image_width', $width, 'category' );
						$height         = 250;
						$height         = (int) apply_filters( 'dots_compi_et_pb_blog_image_height', $height, 'category' );
						$titletext      = get_the_title();
						$thumbnail      = get_thumbnail( $width, $height, '', $titletext, $titletext, false, 'Blogimage' );
						$thumb          = $thumbnail["thumb"];
						$no_thumb_class = '' === $thumb || 'off' === $show_thumbnail ? ' et_pb_no_thumb' : '';
						if ( in_array( $post_format, array( 'video', 'gallery' ) ) ) {
							$no_thumb_class = '';
						} ?>

						<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' . $no_thumb_class ); ?>>

							<?php
							et_divi_post_format_content();
							if ( ! in_array( $post_format, array( 'link', 'audio', 'quote' ) ) ) {
								if ( 'video' === $post_format && false !== ( $first_video = et_get_first_video() ) ) {
									printf(
										'<div class="et_main_video_container">
												%1$s
											</div>',
										$first_video
									);
								} elseif ( 'gallery' === $post_format ) {
									et_gallery_images();
								} elseif ( '' !== $thumb && 'on' === $show_thumbnail ) { ?>

									<div class="et_pb_image_container">

										<a href="<?php the_permalink(); ?>">
											<?php print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height ); ?>
										</a>
									</div> <!-- .et_pb_image_container -->

								<?php }
							} ?>

							<?php // if ( ! in_array( $post_format, array( 'link', 'audio', 'quote', 'gallery' ) ) ) {
								if ( ! in_array( $post_format, array( 'link', 'audio' ) ) ) { ?>
									<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<?php }


								if ( 'on' === $show_author || 'on' === $show_date || 'on' === $show_categories ) {
									printf( '<p class="post-meta">%1$s %2$s %3$s %4$s %5$s</p>',
										(
										'on' === $show_author
											? sprintf( __( 'by %s', 'et_builder' ), et_pb_get_the_author_posts_link() )
											: ''
										),
										(
										( 'on' === $show_author && 'on' === $show_date )
											? ' | '
											: ''
										),
										(
										'on' === $show_date
											? sprintf( __( '%s', 'et_builder' ), get_the_date( $meta_date ) )
											: ''
										),
										(
										( ( 'on' === $show_author || 'on' === $show_date ) && 'on' === $show_categories )
											? ' | '
											: ''
										),
										(
										'on' === $show_categories
											? get_the_category_list( ', ' )
											: ''
										)
									);
								}
								if ( ! has_shortcode( get_the_content(), 'et_pb_blog' ) ) {
									if ( 'on' === $show_content ) {
										global $more;
										$more = null;
										the_content( __( 'read more...', 'et_builder' ) );
									} else {
										if ( has_excerpt() ) {
											the_excerpt();
										} else {
											truncate_post( 270 );
										}
										$more = sprintf( ' <a href="%1$s" class="more-link" >%2$s</a>', esc_url( get_permalink() ), __( 'read more', 'et_builder' ) );
										echo $more;
									}
								} else if ( has_excerpt() ) {
									the_excerpt();
								}
								?>
							<?php // } // 'off' === $fullwidth || ! in_array( $post_format, array( 'link', 'audio', 'quote', 'gallery' ?>

						</article> <!-- .et_pb_post -->
						<?php
					} // endwhile

					echo '</div> <!-- .et_pb_posts -->';
					$container_is_closed = true;
					if ( function_exists( 'wp_pagenavi' ) ) {
						wp_pagenavi();
					} else {
						get_template_part( 'includes/navigation', 'index' );
					}


				} else {
					get_template_part( 'includes/no-results', 'index' );
				}
				?>

<?php

$posts = ob_get_contents();
ob_end_clean();

if ( true === $dots_compi_sidebar ) {
	ob_start();
	dynamic_sidebar();
	$sidebar_contents = ob_get_contents();
	ob_end_clean();
}

$class  = " et_pb_module et_pb_bg_layout_{$background_layout}";
$output = sprintf(
	'<div class="et_pb_blog_grid clearfix %2$s et_pb_blog_grid_dropshadow"%4$s>
				%1$s
			%3$s',
	$posts,
	esc_attr( $class ),
	( ! $container_is_closed ? '</div> <!-- .et_pb_posts -->' : '' ),
	' data-columns'
);

$output = sprintf( '<div class="et_pb_section et_section_regular">
						<div class="et_pb_row">
							<div class="et_pb_column %2$s">
								<div class="et_pb_blog_grid_wrapper">%1$s</div>
							</div>
							%3$s
		</div> <!-- #content-area -->
	</div> <!-- #main-content -->
						</div>
					</div>',
	$output,
	( false === $dots_compi_sidebar ) ? 'et_pb_column_4_4' : 'et_pb_column_3_4',
	( true === $dots_compi_sidebar ) ? sprintf( '<div class="et_pb_column et_pb_column_1_4">%1$s</div>', $sidebar_contents ) : ''
);

echo $output;

get_footer();

