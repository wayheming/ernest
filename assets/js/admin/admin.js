/* global ernestAdminConfig, jQuery */

(
	function( $ ) {
		'use strict';

		// Ernest admin.
		const ernestAdmin = {
			// Init.
			init() {
				ernestAdmin.updateButtonAction();
			},

			// Update data button action.
			updateButtonAction() {
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

							// eslint-disable-next-line no-console
							console.error( `Error: ${ status } - ${ error }` );
						},
					} );
				} );
			},
		};

		ernestAdmin.init();
		// eslint-disable-next-line func-call-spacing
	}
	( jQuery )
);
