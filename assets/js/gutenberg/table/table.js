/* global ernestGutenbergTableConfig, jQuery */

(
	function( $ ) {
		'use strict';

		// Ernest admin.
		let ernestTable = {
			// Init.
			init: function() {
				ernestTable.getTable();
			},

			// Update data button action.
			getTable: function() {
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
								console.error( `Error: ${status} - ${error}` );
							},
						} );
					})
				} );
			},
		};

		ernestTable.init();
	}
)( jQuery );