/* global ernestGutenbergTableConfig, jQuery */

(
	function( $ ) {
		'use strict';

		// Ernest admin.
		const ernestTable = {
			// Init.
			init() {
				ernestTable.getTable();
			},

			// Update data button action.
			getTable() {
				$( document ).on( 'ready', () => {
					const $tables = $( 'table.ernest' );

					$tables.each( function() {
						const $table = $( this );

						$.ajax( {
							type: 'POST',
							url: ernestGutenbergTableConfig.adminAjaxUrl,
							data: {
								action: 'ernest_get_table',
								attributes: $table.data( 'attributes' ),
							},
							success: ( response ) => {
								if ( response.success && response.data ) {
									$table.parent().replaceWith( response.data );
								}
							},
							error: ( xhr, status, error ) => {
								// eslint-disable-next-line no-console
								console.error( `Error: ${ status } - ${ error }` );
							},
						} );
					} );
				} );
			},
		};

		ernestTable.init();
		// eslint-disable-next-line func-call-spacing
	}
	( jQuery )
);
