$(function () {
	$(document).ready(function () {
		$(document).on('input', '.input-numeric', function (e) {
			var format = {
				decimals   : $(this).attr('data-decimals') || 0,
				decsep     : $(this).attr('data-decsep') || '.',
				thousanssep: $(this).attr('data-thousanssep') || '',
				descsepsec : $(this).attr('data-decsep-seconds') || '.',
				min        : $(this).attr('data-min') || 0,
				max        : $(this).attr('data-max') || Number.MAX_VALUE
			}

			value = $(this).val();

			$.each(format.descsepsec.split('|'), function () {
				value = value.replace(this, format.decsep);
			});

			var value = SITE.utils.numberFormat(value, format.decimals, format.decsep, format.thousanssep, true);
			$(this).val(value);
		});

        $(document).on('input', '.area-counter', function () {
            var max = $(this).attr('data-max') || 0;
            var value = $(this).val();

            if(value.length > max) {
            	value = value.substr(0, max);
            	$(this).val(value);
            }

            $(this).parent().find('span').html(max - value.length);
        });

        $('.area-counter').trigger('input');

		window.SITE.utils = {
			numberFormat: function (value, decimals, decPoint, thousansSeparator, nullable) {
				var value = value || '', decimals = decimals || 0, decPoint = decPoint || '.',thousansSeparator = thousansSeparator || '',nullable = nullable === undefined ? true : nullable;

				value = value.replace(new RegExp("[^\\d\\" + decPoint + "]", 'g'), '');
				var valueChunks = value.split(decPoint);

				value = valueChunks[0];
				var firstTousanLength = value.length % 3;
				value = value.substr(0, firstTousanLength) + thousansSeparator + value.substr(firstTousanLength).replace(/(\d{3})(?=\d)/g, "$1" + thousansSeparator);
				value = value.trim();

				if(decimals > 0 && valueChunks.length > 1) {
					valueFraction = '';
					for(var i = 1; i < valueChunks.length; i++) {
						valueFraction += valueChunks[i];
					}

					valueFraction = valueFraction.substr(0, decimals);

					if(value == '') { value = '0'; }
					value += decPoint + valueFraction;
				}

				if(value == '' && !nullable) {
					value = '0';
				}

				return value;
			}
		}
	});
});