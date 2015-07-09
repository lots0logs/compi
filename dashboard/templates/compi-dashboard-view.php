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


echo '
		<div id="dots_admin_wrapper_outer" class="units-row">
			<div id="dots_admin_wrapper" class="dots_admin">
			<form id="dots_compi_options" enctype="multipart/form-data">
				<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header mdl-layout--fixed-tabs">
					<header class="mdl-layout__header">
						<div class="mdl-layout__header-row">
							<span class="mdl-layout-title">Compi Control</span>
						</div>
						<div class="mdl-layout__tab-bar mdl-js-ripple-effect">';


if ( isset( $dash_tabs ) ) {
	$first = true;
	foreach ( $dash_tabs as $key => $value ) {
		if ( isset( $value['title'] ) ) {
			printf( '<a href="#fixed-tab-%1$s" class="mdl-layout__tab%3$s">%2$s</a>',
			        esc_attr( $key ),
			        $value['title'],
			        ( true === $first ) ? ' is-active' : ''
			);
			$first = false;
		}
	}
}


echo '					</div>
					</header>
					<div class="mdl-layout__drawer">
						<span class="mdl-layout-title">Plugin Settings</span>';


if ( isset( $dash_tabs['plugin']['contents'] ) ) {
	echo '<nav class="mdl-navigation">';
	foreach ( $dash_tabs['plugin']['contents'] as $key => $value ) {
		printf( '<a class="mdl-navigation__link" href="">%s</a>',
		        esc_attr( $key )
		);
	}
	echo '</nav>';
}


echo '				</div>
					<main class="mdl-layout__content">';


$menu_count = 0;
settings_fields( 'dots_compi_settings_group' );

if ( isset( $dash_tabs ) ) {
	$first = true;
	foreach ( $dash_tabs as $key => $value ) {
		$current_section = $key;

		if ( $key !== 'header' ) {
			printf( '<section class="mdl-layout__tab-panel%2$s" id="fixed-tab-%1$s">
												<div class="page-content units-row units-padding">',
			        esc_attr( $current_section ),
			        ( true === $first ) ? ' is-active' : ''
			);
			$first = false;
			foreach ( $value['contents'] as $key => $value ) {
				$current_location = $key;
				$options_prefix   = $current_section . '_' . $key;
				$options_array    = ${$current_section . '_' . $key . '_options'};
				$sidebar_section  = 'sidebar' == $key ? true : false;

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
							printf( '<li><label for="dots_admin[%2$s]" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect%5$s"%4$s%6$s><input type="checkbox" id="dots_admin[%2$s]" name="dots_admin[%2$s]" value="1" class="mdl-checkbox__input" %3$s><span class="mdl-checkbox__label">%1$s</span></label>',
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

						case 'radio' :
							printf( '<li><label for="dots_admin[%2$s]" class="mdl-radio mdl-js-radio%5$s"%4$s%6$s><input type="radio" id="dots_admin[%2$s]" name="dots_admin[%2$s]" value="1" class="mdl-radio__button" %3$s><span class="mdl-radio__label">%1$s</span></label>',
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

						case 'switch' :
							printf( '<li><label for="dots_admin[%2$s]" class="mdl-switch mdl-js-switch mdl-js-ripple-effect%5$s"%4$s%6$s><input type="checkbox" id="dots_admin[%2$s]" name="dots_admin[%2$s]" value="1" class="mdl-switch__input" %3$s><span class="mdl-switch__label">%1$s</span></label>',
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
								'<li class="input clearfix%4$s%7$s" %5$s><p class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label"><input type="%9$s" class="mdl-textfield__input" name="dots_admin[%2$s]" value="%3$s"%6$s%8$s><label class="mdl-textfield__label" for="dots_admin[%2$s]">%1$s</label></p>',
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
								'%5$s<div class="units-row dots_admin_row%2$s"%3$s%4$s>
																			%1$s
																			%6$s
																			<div class="unit-100">',
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
							printf( '</div></div>%1$s',
							        ( isset( $option['sub_section'] ) && true == $option['sub_section'] ) ? '</li>' : ''
							);
							break;

						case 'text' :
							printf(
								'<li class="dots_admin_auto_height"><p class="mdl-textfield mdl-js-textfield">
																			<textarea class="mdl-textfield__input" placeholder="%1$s" rows="%2$s" name="dots_admin[%4$s]"%5$s>%3$s</textarea>
																		<label class="mdl-textfield__label" for="address">%6$s</label></p></li>',
								esc_attr( $option['placeholder'] ),
								esc_attr( $option['rows'] ),
								esc_textarea( stripslashes( $current_option_value ) ),
								esc_attr( $current_option_name ),
								( isset( $option['class'] )
									? sprintf( ' class="%1$s"', esc_attr( $option['class'] ) )
									: ''
								),
								isset( $option['title'] ) ? esc_html( $option['title'] ) : ''
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
								'<div class="mdl-cell--stretch dots_admin_row dots_admin_selection">
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

						case 'card' :
							printf(
								'<div class="unit-30">
										<h2>%2$s</h2>

									<p class="h4">%3$s</p>

										<table class="table-hovered">
											<thead>
												<tr>
													<th class="width-25">Enable</th>
													<th class="width-75">Enhancement</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td class="width-25">
														<label for="dots_admin[%4$s]" class="mdl-switch mdl-js-switch mdl-js-ripple-effect">
															<input type="checkbox" id="dots_admin[%4$s]" name="dots_admin[%4$s]" class="mdl-switch__input" %5$s>
															<span class="mdl-switch__label"></span>
														</label>
													</td>
													<td class="width-75">%3$s</td>
												</tr>
											</tbody>
										</table>

								</p></div>',
								esc_html( $option['class'] ),
								esc_html( $option['title'] ),
								isset( $option['description'] ) ? esc_html( $option['description'] ) : '',
								esc_attr( $current_option_name ),
								checked( $current_option_value, 1, false )
							);
							break;
					} // end switch
				} // end foreach( $options_array as $option)
			} // end foreach( $value['contents'] as $key => $value )
			echo '</div></section>';
		} // end if ( $key !== 'header')
	} // end foreach ( $compi_sections as $key => $value )
} // end if ( isset( $compi_sections ) )

echo '
</main>';
printf(
	'<div class="admin-row"><button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">%1$s</button>
					<input type="hidden" name="action" value="save_compi" /></div>',
	esc_html__( 'Save Changes', 'Compi' )
);
echo '
</div>
</div>
</form>
</div>
</div>';