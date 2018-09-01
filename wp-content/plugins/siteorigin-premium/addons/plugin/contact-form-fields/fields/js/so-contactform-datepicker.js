/* globals jQuery */

jQuery( function ( $ ) {

	$( '.datepicker-container' ).each( function ( index, element ) {
		var $datepickerContainer = $(element);
		var $datepicker = $datepickerContainer.find( '.so-premium-datepicker' );
		var options = $datepicker.data( 'options' );
		var $valInput = $datepickerContainer.siblings( '.so-contactform-datetime' );
		$datepicker.pikaday( {
			defaultDate: new Date( $valInput.val() ),
			setDefaultDate: true,
			onSelect: function () {
				var date = this.getDate();
				var $timepicker = $datepickerContainer.siblings( '.timepicker-container' ).find( '.so-premium-timepicker' );
				if ( $timepicker.length > 0 ) {
					var time = $timepicker.timepicker( 'getTime' );
					if ( time && time instanceof Date ) {
						date.setHours( time.getHours(), time.getMinutes(), time.getSeconds(), time.getMilliseconds() );
						$valInput.val( date );
					}
				} else {
					$valInput.val( date );
				}
			},
			disableWeekends: options.disableWeekends,
			disableDayFn: function( date ) {
				var isDisabledDay = options.disabled.days.indexOf(date.getDay().toString()) > -1;
				if(isDisabledDay) {
					return true;
				}
				var disabled = options.disabled.dates.some(function (epoch) {
					var d = new Date(epoch);
					return d.getFullYear() === date.getFullYear() &&
						d.getMonth() === date.getMonth() &&
						d.getDate() === date.getDate();
				});

				return disabled;
			}

		} );
	} );

	$( '.timepicker-container' ).each( function ( index, element ) {
		var $timepickerContainer = $( element );
		var $timepicker = $timepickerContainer.find('.so-premium-timepicker');
		var options = $timepicker.data('options');
		$timepicker.timepicker(options);
		var $valInput = $timepickerContainer.siblings( '.so-contactform-datetime' );
		if ( $valInput.val() ) {
			var curDate = new Date( $valInput.val() );
			// If it's not a valid date, assume it's just a time string, e.g. '12:30pm'
			if ( isNaN( curDate.valueOf() ) ) {
				$timepicker.val( $valInput.val() );
			} else {
				$timepicker.timepicker( 'setTime', curDate );
			}
		}
		$timepicker.on( 'changeTime', function () {
			var $datepicker = $timepickerContainer.siblings( '.datepicker-container' ).find( '.so-premium-datepicker' );
			// If we have a datepicker too, then set the time on the datepicker's selected date.
			if ( $datepicker.length > 0 ) {
				var date = $datepicker.data( 'pikaday' ).getDate();
				if ( date ) {
					var time = $timepicker.timepicker( 'getTime' );
					time = new Date( date.setHours(
						time.getHours(),
						time.getMinutes(),
						time.getSeconds(),
						time.getMilliseconds()
					) );
					$valInput.val( time );
				}
			} else {
				$valInput.val( $timepicker.val() );
			}
		} );
	} );

} );

