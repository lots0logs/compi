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


echo '<form id="dots_compi_options" enctype="multipart/form-data">
		<div id="dots_admin_wrapper_outer" class="units-container dots">
			<div id="dots_admin_wrapper" class="dots_admin units-row units-padding">
			<div class="unit-centered unit-70" style="position: relative;min-height:700px;">
			';
$menu_count = 0;
settings_fields( 'dots_compi_settings_group' );
echo '<h4>Compi Settings</h4>
						<div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
  <div class="mdl-tabs__tab-bar">';


if ( isset( $dash_tabs ) ) {
	$first = true;
	foreach ( $dash_tabs as $key => $value ) {
		if ( isset( $value['title'] ) ) {
			printf( '<a href="#fixed-tab-%1$s" class="mdl-tabs__tab%3$s">%2$s</a>',
				esc_attr( $key ),
				$value['title'],
				( true === $first ) ? ' is-active' : ''
			);
			$first = false;
		}
	}
}



echo '</div>';

if ( isset( $dash_tabs ) ) {
	$first = true;
	foreach ( $dash_tabs as $key => $value ) {
		$current_section = $key;

		if ( $key !== 'header' ) {
			printf( '<section class="mdl-tabs__panel%2$s" id="fixed-tab-%1$s">
						<div class="page-content units-row units-padding">
						<div class="unit-centered unit-60">
						<div class="units-row units-split">',
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
							printf( '<div class="unit-10 unit-push-right dots_switch"><label for="dots_admin%2$s" class="mdl-switch mdl-js-switch mdl-js-ripple-effect%5$s"%4$s%6$s><input type="checkbox" id="dots_admin%2$s" name="dots_admin%2$s" value="1" class="mdl-switch__input" %3$s><span class="mdl-switch__label">%1$s</span></label></div>',
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
								'<div class="units-row units-split dots_option_row"><div class="unit-90%2$s">
										%1$s
										%3$s</div>',
								isset( $option['title'] ) ? sprintf( '<h6>%1$s</h6>', esc_html( $option['title'] ) ) : '',
								( isset( $option['sub_section'] ) && true == $option['sub_section'] ) ? '' : ' ',
								isset( $option['subtitle'] ) ? sprintf( '<p>%1$s</p>', esc_html( $option['subtitle'] ) ) : ''
							);
							break;

						case 'section_end' :
							printf( '%1$s',
								( isset( $option['sub_section'] ) && true == $option['sub_section'] ) ? '</li>' : ''
							);
							echo '</div>';
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
								'<div class="mdl-cell mdl-cell--12-col dots_admin_row dots_admin_selection">
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

						case 'card_start' :
							printf(
								'<div class="unit-30">
									<div class="mdl-card mdl-shadow--2dp dots_card">
										<div class="mdl-card__title mdl-card--expand%2$s">
											<span class="dots_pb_icon dots_pb_%3$s"></span>
											<h2 class="mdl-card__title-text">%1$s</h2>
										</div>
										<div class="mdl-card__actions">
											<table class="mdl-data-table mdl-js-data-table">
											<thead>
												<tr>
													<th class="mdl-data-table__cell--non-numeric">Enhancement</th>
													<th class="mdl-data-table__cell--non-numeric">On/Off</th>
												</tr>
											</thead>
											<tbody>
												',
								esc_html( $option['title'] ),
								( isset( $option['regular'] ) && true === $option['regular'] ) ? ' regular' : ' fullwidth',
								esc_attr( $option['icon'] )
							);
							break;

						case 'card_option' :
							printf(
								'<tr>
									<td class="mdl-data-table__cell--non-numeric">%1$s</td>
									<td class="mdl-data-table__cell--non-numeric">
										<label for="dots_admin[%2$s]" class="mdl-switch mdl-js-switch mdl-js-ripple-effect">
											<input type="checkbox" id="dots_admin[%2$s]" name="dots_admin[%2$s]" class="mdl-switch__input" %3$s>
											<span class="mdl-switch__label"></span>
										</label>
									</td>
								</tr>',
								isset( $option['description'] ) ? esc_html( $option['description'] ) : '',
								esc_attr( $current_option_name ),
								checked( $current_option_value, 1, false )
							);
							break;

						case 'card_end' :
							printf(
								'		</tbody>
									</table>
								</div></div></div>'
							);
							break;

					} // end switch
				} // end foreach( $options_array as $option)
			} // end foreach( $value['contents'] as $key => $value )
			echo '</div></div></div></section>';
		} // end if ( $key !== 'header')
	} // end foreach ( $compi_sections as $key => $value )
} // end if ( isset( $compi_sections ) )

echo '</div>';
printf(
	'<div class="mdl-grid dots_save_wrap"><div class="mdl-cell mdl-cell--12-col"><button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent dots_save">%1$s</button>
					<input type="hidden" name="action" value="save_compi" /></div></div>',
	esc_html__( 'Save Changes', 'Compi' )
);
echo '</div></div></div></form>';