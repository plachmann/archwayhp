/* globals jQuery */

jQuery( function ( $ ) {
	$(document).on( 'sowsetupform', '.siteorigin-widget-form[data-class="SiteOrigin_Premium_Form"]', function() {

		var $form = $(this);

		if ( typeof $form.data('initialised') === 'undefined' ) {
			var $slugInput = $form.find('[name="so_post_type_settings[slug]"]');
			var oldValue = $slugInput.val();
			$slugInput.css('color', '#aaaaaa');
			$slugInput.on('change',
				function() {
					if( ! confirm(soCptBuilderAdminOptions.loc.confirm_edit_slug) )  {
						$slugInput.val(oldValue);
					}
				}
			);

			$form.data('initialised', true);
		}
	});
} );
