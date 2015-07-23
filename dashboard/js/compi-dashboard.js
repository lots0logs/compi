(function ($) {

	'use strict';

	$(window).load(function () {
		console.log('window loaded!');

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
					$('.dots_save_wrap button').hide();
					$spinner.addClass('is-active');
				},
				success: function (data) {
					setTimeout(function() {
						$spinner.removeClass('is-active');
						$('.dots_save_wrap button').show();
						//display_warning(data);
						console.log(data);
					}, 1000);

				},
				error: function(jqXHR, textStatus) {
					console.log(jqXHR);
					console.log(textStatus);
				}
			});
			return false;
		});




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

	});


})(jQuery);
