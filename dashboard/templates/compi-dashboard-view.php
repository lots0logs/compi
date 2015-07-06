<?php

/**
 * Provide a dashboard view for the plugin
 *
 * This file is used to markup the dashboard of the plugin.
 *
 * @link       http://wpdots.com
 * @since      1.0.0
 *
 * @package    Compi
 * @subpackage Compi/dashboard/templates
 */


$compi_options               = $this->compi_options;
$compi_sections              = $this->compi_sections;
$sharing_locations_options   = $this->sharing_locations_options;
$sharing_networks_options    = $this->sharing_networks_options;
$sharing_sidebar_options     = $this->sharing_sidebar_options;
$sharing_inline_options      = $this->sharing_inline_options;
$sharing_popup_options       = $this->sharing_popup_options;
$sharing_flyin_options       = $this->sharing_flyin_options;
$sharing_media_options       = $this->sharing_media_options;
$follow_networks_options     = $this->follow_networks_options;
$follow_widget_options       = $this->follow_widget_options;
$follow_shortcode_options    = $this->follow_shortcode_options;
$general_main_options        = $this->general_main_options;
$compi_post_types            = $this->compi_post_types;
$header_importexport_options = $this->header_importexport_options;
$header_stats_options        = $this->header_stats_options;

echo '
		<div id="dots_admin_wrapper_outer">
			<div id="dots_admin_wrapper" class="dots_admin">
				<div id="dots_admin_header">
					<div id="dots_admin_logo" class="dots_admin_icon_compi dots_admin_icon"></div>
					<ul>';
if ( isset( $compi_sections['header']['contents'] ) ) {
	foreach ( $compi_sections['header']['contents'] as $key => $value ) {
		printf( '<li><a href="#tab_dots_admin_tab_content_header_%1$s" id="dots_admin_tab_content_header_%1$s" class="dots_admin_icon_header_%1$s dots_admin_icon"><span></span></a></li>',
		        esc_attr( $key )
		);
	}
}
echo '
					</ul>
				</div>
				<div class="clearfix"></div>

				<div id="dots_admin_navigation">
					<ul>';
$menu_count = 0;
if ( isset( $compi_sections ) ) {
	foreach ( $compi_sections as $key => $value ) {
		if ( $key !== 'header' ) {
			$current_section = $key;
			foreach ( $value as $key => $value ) {
				if ( $key == 'title' ) {
					printf( '<li><a href="#" class="dots_admin_icon_%1$s dots_admin_icon dots_admin_tab_parent"><span>%2$s</span></a>',
					        esc_attr( $current_section ),
					        esc_html( $value )
					);
				} else {
					printf( '<ul class="dots_admin_%1$s_nav">',
					        esc_attr( $current_section )
					);
					foreach ( $value as $key => $value ) {
						printf( '<li><a href="#tab_dots_admin_tab_content_%1$s_%2$s" id="dots_admin_tab_content_%1$s_%2$s" class="dots_admin_icon_%2$s dots_admin_icon"><span>%3$s</span></a></li>',
						        esc_attr( $current_section ),
						        esc_attr( $key ),
						        esc_html( $value )
						);
					}
					echo '</ul></li>';
				} // end else
			} // end foreach( $value as $key => $value )
		} // end if ( $key !== 'header')
	} //end foreach ( $compi_sections as $key => $value )
} // end if ( isset( $compi_sections ) )
echo '</ul>
					</div>

					<div id="dots_admin_content">
						<form id="dots_compi_options" enctype="multipart/form-data">';
settings_fields( 'dots_compi_settings_group' );

if ( isset( $compi_sections ) ) {
	foreach ( $compi_sections as $key => $value ) {
		$current_section = $key;

		if ( $key !== 'header' ) {
			foreach ( $value['contents'] as $key => $value ) {
				$current_location = $key;
				$options_prefix   = $current_section . '_' . $key;
				$options_array    = ${$current_section . '_' . $key . '_options'};
				$sidebar_section  = 'sidebar' == $key ? true : false;
				printf( '<div class="dots_admin_tab_content dots_admin_tab_content_%1$s_%2$s">',
				        esc_attr( $current_section ),
				        esc_attr( $key )
				);
				foreach ( $options_array as $option ) {
					$current_option_name = '';
					if ( isset( $option['name'] ) ) {
						$current_option_name = $options_prefix . '_' . $option['name'];
					}

					$current_option_value = isset( $compi_options[ $current_option_name ] ) ? $compi_options[ $current_option_name ] : '';

					if ( ! isset( $compi_options[ $current_option_name ] ) && isset( $option['default'] ) ) {
						$current_option_value = isset( $option[ 'default_' . $current_location ] ) ? $option[ 'default_' . $current_location ] : $option['default'];
					}

					switch ( $option['type'] ) {

						case 'multi_select' :
							echo '<div class="dots_admin_row dots_admin_selection">';
							$i                    = 0;
							$current_option_value = '' == $current_option_value ? array() : $current_option_value;
							foreach ( $option['value'] as $location => $location_name ) {
								printf( '<div class="dots_admin_location dots_admin_multi_selectable  dots_admin_icon"><div class="dots_admin_location_tile"><h1>%1$s</h1><div class="dots_admin_location_content %7$s">%8$s<div class="dots_admin_location_icons dots_admin_location_icons_%2$s">%9$s%10$s</div></div><input class="dots_admin_toggle" type="checkbox" id="dots_admin[%3$s][%6$s]" name="dots_admin[%3$s][]" value="%4$s" %5$s></div>',
								        esc_html( $location_name ),
								        esc_attr( $location ),
								        esc_attr( $current_option_name ),
								        esc_attr( $location ),
								        checked( in_array( $location, $current_option_value ), true, false ),
								        esc_attr( $i ),
								        ( 'inline' === $location || 'media' === $location ) ? esc_attr( 'dots_admin_location_content_' . $location ) : '',
								        ( 'popup' === $location || 'media' === $location ) ? '' : '</div>',
								        ( 'popup' === $location || 'media' === $location ) ? '</div>' : '',
								        ( 'media' === $location ) ? '<i class="dots_admin_icon_image dots_admin_icon"></i>' : ''
								);
								$i ++;
							}
							echo '</div>';
							break;

						case 'select' :
							$current_option_list = isset( $option[ 'value_' . $current_location ] ) ? $option[ 'value_' . $current_location ] : $option['value'];
							printf(
								'<li class="select%3$s"%4$s><p>%1$s</p>
																<select name="dots_admin[%2$s]">',
								isset( $option[ 'title_' . $current_location ] ) ? esc_html( $option[ 'title_' . $current_location ] ) : esc_html( $option['title'] ),
								esc_attr( $current_option_name ),
								isset( $option['display_if'] ) ? ' dots_admin_hidden_option' : '',
								isset( $option['display_if'] ) ? ' data-condition="' . esc_attr( $option['display_if'] ) . '"' : ''
							);

							foreach ( $current_option_list as $actual_value => $display_value ) {
								printf( '<option value="%1$s" %2$s>%3$s</option>',
								        esc_attr( $actual_value ),
								        selected( $actual_value, $current_option_value, false ),
								        esc_html( $display_value )
								);
							}

							echo '</select>';

							if ( isset( $option['hint_text'] ) ) {
								printf(
									'<span class="more_info dots_admin_icon"><span class="dots_admin_more_text">%1$s</span></span>',
									esc_html( $option['hint_text'] )
								);
							}
							echo '</li>';
							break;

						case 'checkbox' :
							printf( '<li class="dots_admin_checkbox clearfix%5$s"%4$s%6$s><p>%1$s</p><input type="checkbox" id="dots_admin[%2$s]" name="dots_admin[%2$s]" value="1" %3$s><label for="dots_admin[%2$s]"></label>',
							        isset( $option[ 'title_' . $current_location ] ) ? esc_html( $option[ 'title_' . $current_location ] ) : esc_html( $option['title'] ),
							        esc_attr( $current_option_name ),
							        checked( $current_option_value, 1, false ),
							        isset( $option['conditional'] ) ? ' data-enables_1="' . esc_attr( $options_prefix . '_' . $option['conditional'] ) . '"' : '',
							        isset( $option['conditional'] ) ? ' dots_admin_conditional' : '',
							        isset( $option['conditional_2'] ) ? ' data-enables_2="' . esc_attr( $options_prefix . '_' . $option['conditional_2'] ) . '"' : ''
							);
							if ( isset( $option['hint_text'] ) ) {
								printf(
									'<span class="more_info dots_admin_icon"><span class="dots_admin_more_text">%1$s</span></span>',
									esc_html( $option['hint_text'] )
								);
							}
							echo '</li>';
							break;

						case 'input_field' :
							printf(
								'<li class="input clearfix%4$s%7$s" %5$s><p>%1$s</p><input type="%9$s" name="dots_admin[%2$s]" value="%3$s"%6$s%8$s>',
								isset( $option[ 'title_' . $current_location ] ) ? esc_html( $option[ 'title_' . $current_location ] ) : esc_html( $option['title'] ),
								esc_attr( $current_option_name ),
								esc_attr( $current_option_value ),
								isset( $option['display_if'] ) ? ' dots_admin_hidden_option' : '',
								isset( $option['display_if'] ) ? ' data-condition="' . esc_attr( $option['display_if'] ) . '"' : '',
								'number' == $option['subtype'] ? ' placeholder="0"' : '',
								'text' == $option['subtype'] ? ' dots_admin_longinput' : '',
								( isset( $option['class'] )
									? sprintf( ' class="%1$s"', esc_attr( $option['class'] ) )
									: ''
								),
								( isset( $option['hide_contents'] )
									? 'password'
									: 'text'
								)
							);
							if ( isset( $option['hint_text'] ) ) {
								printf( '<span class="more_info dots_admin_icon"><span class="dots_admin_more_text">%1$s</span></span>',
									(
									! isset( $option['hint_text_with_links'] )
										? esc_html( $option['hint_text'] )
										: $option['hint_text']
									)
								);
							}
							echo '</li>';
							break;

						case 'checkbox_posts' :
							echo '<li><ul class="inline">';
							$i                    = 0;
							$current_option_value = '' == $current_option_value ? array() : $current_option_value;
							$post_types           = ! empty( $option['value'] ) ? $option['value'] : $compi_post_types;

							if ( isset( $option['include_home'] ) && true == $option['include_home'] ) {
								$post_types = array_merge( array( 'home' => 'home' ), $post_types );
							}

							foreach ( $post_types as $post_type ) {
								printf( '<li class="dots_admin_checkbox"><input type="checkbox" id="dots_admin[%1$s][%4$s]" name="dots_admin[%1$s][]" value="%3$s" %2$s><label for="dots_admin[%1$s][%4$s]"></label><p>%3$s</p></li>',
								        esc_attr( $current_option_name ),
								        checked( in_array( $post_type, $current_option_value ), true, false ),
								        esc_attr( $post_type ),
								        esc_attr( $i )
								);
								$i ++;
							}
							echo '</ul><div style="clear:both;"></div></li>';
							break;

						case 'section_start' :
							printf(
								'%5$s<div class="dots_admin_form dots_admin_row%2$s"%3$s%4$s>
																%1$s
																%6$s
																<ul>',
								isset( $option['title'] ) ? sprintf( '<h2>%1$s</h2>', esc_html( $option['title'] ) ) : '',
								isset( $option['display_if'] ) ? ' dots_admin_hidden_option' : '',
								isset( $option['display_if'] ) ? ' data-condition="' . esc_attr( $option['display_if'] ) . '"' : '',
								( isset( $current_option_name ) && '' != $current_option_name )
									? sprintf( ' data-name="dots_admin[%1$s]"', esc_attr( $current_option_name ) )
									: '',
								( isset( $option['sub_section'] ) && true == $option['sub_section'] )
									? '<li class="dots_admin_auto_height">'
									: '',
								isset( $option['subtitle'] )
									? sprintf( '<p class="dots_admin_section_subtitle">%1$s</p>', esc_html( $option['subtitle'] ) )
									: ''
							);
							break;

						case 'section_end' :
							printf( '</ul></div>%1$s',
							        ( isset( $option['sub_section'] ) && true == $option['sub_section'] ) ? '</li>' : ''
							);
							break;

						case 'text' :
							printf(
								'<li class="dots_admin_auto_height">
																<textarea placeholder="%1$s" rows="%2$s" name="dots_admin[%4$s]"%5$s>%3$s</textarea>
															</li>',
								esc_attr( $option['placeholder'] ),
								esc_attr( $option['rows'] ),
								esc_textarea( stripslashes( $current_option_value ) ),
								esc_attr( $current_option_name ),
								( isset( $option['class'] )
									? sprintf( ' class="%1$s"', esc_attr( $option['class'] ) )
									: ''
								)
							);
							break;

						case 'shortcode' :
							printf(
								'<div class="dots_admin_shortcode_gen dots_admin_form dots_admin_row">
																<button class="dots_admin_icon" id="dots_admin_shortcode_button">%1$s</button>
																<span class="spinner"></span>
																<textarea placeholder="%2$s" rows="6" id="dots_admin_shortcode_field"></textarea>
															</div>',
								esc_html( $option['button_text'] ),
								esc_attr( $option['placeholder'] )
							);
							break;

						case 'main_title' :
							printf(
								'<div class="dots_admin_row dots_admin_selection">
																<h1>%1$s</h1>
																%2$s
															</div>',
								esc_html( $option['title'] ),
								isset( $option['subtitle'] )
									? sprintf( '<p>%1$s</p>', esc_html( $option['subtitle'] ) )
									: ''
							);
							break;

						case 'note' :
							printf(
								'<div class="dots_admin_row dots_admin_note">
																<h2>%1$s</h2><p><span>%2$s</span></p>
															</div>',
								esc_html__( 'Note:', 'Compi' ),
								esc_html( $option['text'] )
							);
							break;

						case 'color_picker' :
							printf(
								'<li class="input clearfix dots_admin_color_picker">
																<p>%4$s</p>
																<input class="et-compi-color-picker" type="text" maxlength="7" placeholder="%1$s" name=dots_admin[%2$s] value="%3$s" />
															</li>',
								esc_attr( $option['placeholder'] ),
								esc_attr( $current_option_name ),
								esc_attr( $current_option_value ),
								esc_html( $option['title'] )
							);
							break;

						case 'button' :
							printf(
								'<li class="dots_admin_action_button">
																<a href="%1$s" class="dots_admin_icon %2$s">%3$s</a>
																<span class="spinner"></span>
															</li>',
								esc_url( $option['link'] ),
								esc_html( $option['class'] ),
								$this->api_is_network_authorized( $option['action'] )
									? __( 'Re-Authorize', 'Compi' ) :
									esc_html( $option['title'] )
							);
							break;
					} // end switch
				} // end foreach( $options_array as $option)
				echo '</div>';
			} // end foreach( $value['contents'] as $key => $value )
		} // end if ( $key !== 'header')
	} // end foreach ( $compi_sections as $key => $value )
} // end if ( isset( $compi_sections ) )
printf(
	'<div class="dots_admin_row dots_admin_save_changes">
								<button class="dots_admin_icon">%1$s</button>
								<span class="spinner"></span>
							</div>
							<input type="hidden" name="action" value="save_compi" />',
	esc_html__( 'Save Changes', 'Compi' )
);
echo '</form>';
if ( isset( $compi_sections['header']['contents'] ) ) {
	foreach ( $compi_sections['header']['contents'] as $key => $value ) {
		$options_array = ${'header_' . $key . '_options'};

		printf(
			'<div class="dots_admin_tab_content dots_admin_tab_content_header_%1$s dots_admin_header_option">',
			esc_attr( $key )
		);
		if ( isset( $options_array ) ) {
			foreach ( $options_array as $option ) {
				switch ( $option['type'] ) {

					case 'import' :
						echo '<div class="dots_admin_form dots_admin_row">
													<h1>' . esc_html( $option['title'] ) . '</h1>
													<p>' . __( 'You can either export your Compi Settings or import settings from another install of Compi below.', 'Compi' ) . '</p>
												</div>';

						echo '<div class="dots_admin_form dots_admin_row">
													<h2>' . esc_html__( 'Import Compi Settings', 'Compi' ) . '</h2>
													<div class="dots_admin_import_form dots_admin_row">
														<p class="dots_admin_section_subtitle">' . __( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'Compi' ) . '</p>
														<form method="post" enctype="multipart/form-data" action="tools.php?page=dots_compi_options#tab_dots_admin_tab_content_header_importexport">
															<input type="file" name="import_file"/>';
						wp_nonce_field( 'dots_admin_import_nonce', 'dots_admin_import_nonce' );
						echo '<button class="dots_admin_icon dots_admin_icon_importexport" type="submit" name="submit_import" id="submit_import">' . __( 'Import', 'Compi' ) . '</button>
															<input type="hidden" name="dots_admin_action" value="import_settings" />
														</form>
													</div>
												</div>';

						break;

					case 'export' :
						echo '<div class="dots_admin_form dots_admin_row">
													<h1>' . esc_html( $option['title'] ) . '</h1>
													<p>' . __( 'You can either export your Compi Settings or import settings from another install of Compi below.', 'Compi' ) . '</p>
												</div>';

						echo '<div class="dots_admin_form dots_admin_row">
													<h1>' . esc_html( $option['title'] ) . '</h1>
													<p>' . __( 'You can either export your Compi Settings or import settings from another install of Compi below.', 'Compi' ) . '</p>
												</div>
												<div class="dots_admin_import_form dots_admin_row">
													<h2>' . __( 'Export Compi Settings', 'Compi' ) . '</h2>
													<p class="dots_admin_section_subtitle">' . __( 'Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'Compi' ) . '</p>
													<form method="post">
														<input type="hidden" name="dots_admin_action" value="export_settings" />
														<p>';
						wp_nonce_field( 'dots_admin_export_nonce', 'dots_admin_export_nonce' );
						echo '<button class="dots_admin_icon dots_admin_icon_importexport" type="submit" name="submit_export" id="submit_export">' . __( 'Export', 'Compi' ) . '</button>
														</p>
													</form>
												</div>';

						break;

				} // end switch
			} // end foreach( $options_array as $option )
		} // end if ( isset( $options_array ) )
		echo '</div><!-- .dots_admin_tab_content_header_ -->';
	} // end foreach ( $compi_sections[ 'header' ][ 'contents' ] as $key => $value )
} // end if ( isset( $compi_sections[ 'header' ][ 'contents' ] ) )
echo '</div>
				</div>
			</div>';