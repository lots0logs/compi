/*
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

	var Dots_Builder_Conversion = {

		progress_bar: false,
		option_selected: false,

		init: function () {
			this.add_action();
			this.submit();
		},

		add_action: function () {
			var self = this;
			$('<option>').val('dots_builder_conversion')
			.text('Convert to Divi Builder')
			.appendTo("select[name='action'], select[name='action2']");

			$("select[name='action'], select[name='action2']").change(function () {
				var selected = $(this).val();
				self.option_selected = 'dots_builder_conversion' === selected;
				console.log(self.option_selected);
			})
		},

		submit: function () {

			var self = this;

			$(document.body).on('submit', '#posts-filter', function (e) {

				var submitButtonTop = $(this).find('#doaction[type="submit"]'),
					submitButtonBottom = $(this).find('#doaction2[type="submit"]'),
					data = $(this).serialize(),
					data_obj = {},
					dots_action = 'dots_builder_conversion';

				if (self.option_selected && !submitButtonTop.hasClass('button-disabled') && !submitButtonBottom.hasClass('button-disabled')) {
					e.preventDefault();
					submitButtonTop.addClass('button-disabled');
					submitButtonBottom.addClass('button-disabled');
					self.setup_modal();

					// start the process
					self.process_step(1, data, self);
				}


			});
		},

		process_step: function (step, data, self) {

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					form: data,
					action: 'dots_compi_do_builder_conversion',
					step: step,
					_wpnonce: dots_compi.nonce
				},
				dataType: "json",
				success: function (response) {

					if ('done' == response.step || response.error) {

						var modal = $('.dots_compi_modal'),
							modal_content = modal.find('.dots_compi_modal_content');

						$('#posts-filter').find('.button-disabled').removeClass('button-disabled');

						if (response.error) {

							var error_message = response.message;
							modal_content.html('<div class="dots_compi_error"><p>' + error_message + '</p></div>');

						} else {

							//modal.remove();
							//window.location = response.url;

						}

					} else {
						self.progress_bar.MaterialProgress.setProgress(response.percent);
						self.process_step(parseInt(response.step), data, self);
					}

				}
			}).fail(function (response) {
				if (window.console && window.console.log) {
					console.log(response);
				}
			});

		},

		modal_html: function () {
			return '<div class="dots dots_compi_modal"><div class="modal_header">' +
				'<div class="modal_close icon_close"></div><h1>Builder Conversion</h1>' +
				'<p>Converting Elegant Builder layouts to the Divi Builder.</p></div>' +
				'<div class="modal_content"><div id="dots_compi_progress" class="mdl-progress mdl-js-progress">' +
				'</div></div></div><div class="dots_compi_overlay"></div>';
		},

		setup_modal: function () {
			var self = this;
			$(self.modal_html()).insertAfter($('#posts-filter'));
			$('.dots_compi_overlay').prependTo($('body'));
			$('#dots_compi_progress').on('mdl-componentupgraded', function () {
				self.progress_bar = document.getElementById('dots_compi_progress');
				console.log(self.progress_bar);
				self.progress_bar.MaterialProgress.setProgress(1);
			});
			window.componentHandler.upgradeAllRegistered();
		}

	};

	$(document).ready(function () {
		Dots_Builder_Conversion.init();
	});


})(jQuery);