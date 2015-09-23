<?php
/*
 * compi-dashboard-view.php
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
 * Provides a dashboard view for the plugin
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
$eb_post = array();
settings_fields( 'dots_compi_settings_group' );
echo '				<h4>Compi Settings</h4>';

printf( '<div class="dots_save_wrap">
						<button id="dots_save_button" class="mdl-button mdl-js-button mdl-shadow--4dp mdl-button--fab mdl-button--mini-fab mdl-js-ripple-effect mdl-button--colored mdl-color--accent">
							<i class="material-icons">check</i>
						</button>
						<div class="mdl-tooltip" for="dots_save_button">%1$s</div>
						<span class="mdl-spinner mdl-js-spinner mdl-spinner--single-color"></span>
						<input type="hidden" name="action" value="save_compi" />
					</div>',
	esc_html__( 'Save Changes', 'Compi' )
);

echo '				<div class="mdl-tabs mdl-js-tabs mdl-js-ripple-effect">
						<div class="mdl-tabs__tab-bar">';


if ( isset( $dash_options_all ) ) {

	$first = true;
	foreach ( $dash_options_all as $tab_name => $tab ) {
		if ( isset( $tab['title'] ) ) {
			printf( '<a href="#fixed-tab-%1$s" class="mdl-tabs__tab%3$s">%2$s</a>',
				esc_attr( $tab_name ),
				$tab['title'],
				( true === $first ) ? ' is-active' : ''
			);
			$first = false;
		}
	}
}



echo '					</div>';

if ( isset( $dash_options_all ) ) {
	$first = true;
	foreach ( $dash_options_all as $tab_name => $tab ) {
		$current_section = $tab_name;


		printf( '		<section class="mdl-tabs__panel%2$s" id="fixed-tab-%1$s">
							<div class="page-content units-row units-padding">
								<div class="unit-centered unit-60">
									<div class="units-row units-split">',
			esc_attr( $current_section ),
			( true === $first ) ? ' is-active' : ''
		);
		$first = false;
		foreach ( $tab['contents'] as $option_item => $item_properties ) {

			$options_prefix = $current_section;
			$option         = $item_properties;

			$current_option_name = '';
			if ( isset( $option['name'] ) ) {
				$current_option_name = $options_prefix . '_' . $option['name'];
			}


			$current_option_value = isset( $compi_options[ $current_option_name ] ) ? $compi_options[ $current_option_name ] : '';

			switch ( $option['type'] ) {

				case 'switch' :
					printf( '<div class="unit-10 unit-push-right dots_switch dots_compi_%1$s"><label for="dots_compi[%1$s]" class="mdl-switch mdl-js-switch mdl-js-ripple-effect"><input type="checkbox" id="dots_compi[%1$s]" name="dots_compi[%1$s]" value="1" class="mdl-switch__input" %2$s><span class="mdl-switch__label"></span></label></div>',
						esc_attr( $current_option_name ),
						checked( $current_option_value, 1, false )
					);

					break;

				case 'option_heading' :
					printf(
						'<div class="units-row units-split dots_option_row"><div class="unit-90%2$s">
										%1$s
										%3$s</div>',
						isset( $option['title'] ) ? sprintf( '<h6>%1$s</h6>', esc_html( $option['title'] ) ) : '',
						( isset( $option['sub_section'] ) && true == $option['sub_section'] ) ? '' : ' ',
						isset( $option['subtitle'] ) ? sprintf( '<p>%1$s</p>', esc_html( $option['subtitle'] ) ) : ''
					);
					break;

				case 'option_end' :
					printf( '%1$s',
						( isset( $option['sub_section'] ) && true == $option['sub_section'] ) ? '</li>' : ''
					);
					echo '</div>';
					break;

				case 'table_start' :
					printf(
						'<table class="mdl-data-table mdl-js-data-table mdl-data-table--selectable" style="display:%1$s;">
							<thead>
								<tr>
									<th>Post ID</th>
									<th class="mdl-data-table__cell--non-numeric">Post Title</th>
									<th class="mdl-data-table__cell--non-numeric">Post Type</th>
								</tr>
							</thead>
							<tbody>',
						1 == $compi_options[$options_prefix . '_builder_conversion'] ? 'table' : 'none'
					);
					break;

				case 'table_row' :
					if ( is_array( $eb_posts ) && ! empty( $eb_posts ) ) {
						foreach ( $eb_posts as $eb_post ) {

							$post_type = get_post_type_object( $eb_post->post_type );
							printf(
								'<tr>
									<td>%1$s</td>
									<td class="mdl-data-table__cell--non-numeric">%2$s</td>
									<td class="mdl-data-table__cell--non-numeric">%3$s</td>
								</tr>',
								$eb_post->ID,
								$eb_post->post_title,
								$post_type->labels->singular_name
							);

						}
					}
					break;

				case
				'table_end' :
					echo '	</tbody>
						</table>';
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
		} // end foreach( $value['contents'] as $key => $value )
		echo '</div></div></div></section>';

	} // end foreach ( $compi_sections as $key => $value )
} // end if ( isset( $compi_sections ) )

echo '</div>';
/*printf(
	'<div class="mdl-grid dots_save_wrap"><div class="mdl-cell mdl-cell--12-col"><button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent dots_save">%1$s</button>
					<span class="mdl-spinner mdl-js-spinner mdl-spinner--single-color"></span><input type="hidden" name="action" value="save_compi" /></div></div>',
	esc_html__( 'Save Changes', 'Compi' )
);*/
echo '</div></div></div></form>';