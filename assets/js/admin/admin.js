/* global ernestAdminConfig, jQuery */

(
	function( $ ) {
		'use strict';

		// Ernest admin.
		let ernestAdmin = {
			// Init.
			init: function() {
				ernestAdmin.updateButtonAction();
			},

			// Update data button action.
			updateButtonAction: function() {
				$( document ).on( 'click', '#ernest-update-data-button', ( event ) => {
					event.preventDefault();

					const $table = $( '.ernest-dashboard__content' );

					$table.addClass( 'ernest-loading' );

					$.ajax( {
						type: 'POST',
						url: ernestAdminConfig.adminAjaxUrl,
						data: {
							action: 'ernest_get_actual_table',
							security: ernestAdminConfig.adminUpdateDataNonce,
						},
						success: ( response ) => {
							$table.removeClass( 'ernest-loading' );

							if ( response.success && response.data ) {
								$table.html( response.data );
							}
						},
						error: ( xhr, status, error ) => {
							$table.removeClass( 'ernest-loading' );

							console.error( `Error: ${status} - ${error}` );
						},
					} );
				} );
			},
		};

		ernestAdmin.init();
	}
)( jQuery );