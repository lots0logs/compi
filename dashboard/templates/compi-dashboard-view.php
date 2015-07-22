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



echo '					</div>';

if ( isset( $dash_tabs ) ) {
	$first = true;
	foreach ( $dash_tabs as $key => $value ) {
		$current_section = $key;


		printf( '		<section class="mdl-tabs__panel%2$s" id="fixed-tab-%1$s">
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

				switch ( $option['type'] ) {

					case 'switch' :
						printf( '<div class="unit-10 unit-push-right dots_switch"><label for="dots_compi[%2$s]" class="mdl-switch mdl-js-switch mdl-js-ripple-effect"><input type="checkbox" id="dots_compi[%2$s]" name="dots_compi[%2$s]" value="1" class="mdl-switch__input" %3$s><span class="mdl-switch__label">%1$s</span></label></div>',
							isset( $option[ 'title_' . $current_location ] ) ? esc_html( $option[ 'title_' . $current_location ] ) : esc_html( $option['title'] ),
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

	} // end foreach ( $compi_sections as $key => $value )
} // end if ( isset( $compi_sections ) )

echo '</div>';
/*printf(
	'<div class="mdl-grid dots_save_wrap"><div class="mdl-cell mdl-cell--12-col"><button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent dots_save">%1$s</button>
					<span class="mdl-spinner mdl-js-spinner mdl-spinner--single-color"></span><input type="hidden" name="action" value="save_compi" /></div></div>',
	esc_html__( 'Save Changes', 'Compi' )
);*/
echo '</div></div></div></form>';