/*
 * dashboard.js
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

(function ($) {

	'use strict';

	$(window).load(function () {

		if ($('.dots_save_wrap button').length) {
			initCompiSettingsPage();
		}

	});

	function initCompiSettingsPage() {
		$('.dots_save_wrap button').on('click', function (event) {
			event.preventDefault();
			event.stopPropagation();
			//console.log('clicked!');

			var options_fromform = $('#dots_compi_options').serialize(),
				$spinner = $(this).parent().find('.mdl-spinner');

			$.ajax({
				type: 'POST',
				url: compiSettings.ajaxurl,
				data: {
					action: 'dots_compi_save_settings',
					options: options_fromform,
					save_settings_nonce: compiSettings.save_settings
				},
				beforeSend: function (xhr) {
					//console.log(xhr);
					console.log(options_fromform);
					$('.dots_save_wrap button').fadeOut();
					$spinner.addClass('is-active');
				},
				success: function (data) {
					setTimeout(function () {
						$spinner.removeClass('is-active');
						$('.dots_save_wrap button').fadeIn();
						//display_warning(data);
						console.log(data);
					}, 1000);

				},
				error: function (jqXHR, textStatus) {
					console.log(jqXHR);
					console.log(textStatus);
				}
			});
			return false;
		});

		$(window).load(function () {
			$('.dots_compi_tools_builder_conversion').on('click', '*', function () {
				$_this = $('.dots_compi_tools_builder_conversion').find('input');
				setTimeout(function () {
					if ($_this.val() === '1') {
						$_this.parents('.dots_option_row').find('table').css('display', 'table');
					} else {
						$_this.parents('.dots_option_row').find('table').css('display', 'none');
					}
				}, 500);

			});
		});

	}

	function display_warning($warn_window) {
		if ('' == $warn_window) {
			return;
		}

		$('#wpwrap').append($warn_window);
	}

	function generate_warning($message, $link) {
		var link = '' == $link ? '#' : $link;
		$.ajax({
			type: 'POST',
			url: compiSettings.ajaxurl,
			data: {
				action: 'generate_modal_warning',
				message: $message,
				ok_link: link,
				generate_warning_nonce: compiSettings.generate_warning
			},
			success: function (data) {
				display_warning(data);
			}
		});
	}


})(jQuery);
