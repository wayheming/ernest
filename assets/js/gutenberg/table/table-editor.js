// eslint-disable-next-line no-redeclare
/* global wp, ernestGutenbergTableConfig */

'use strict';

const { useEffect, useState } = wp.element;
const { registerBlockType } = wp.blocks;
const { ToggleControl, PanelBody } = wp.components;
const { InspectorControls } = wp.blockEditor || wp.editor;

registerBlockType( 'ernest/table', {
	title: ernestGutenbergTableConfig.i18n.title,
	description: ernestGutenbergTableConfig.i18n.description,
	category: 'widgets',
	attributes: {
		id: {
			type: 'boolean',
			default: true,
		},
		fname: {
			type: 'boolean',
			default: true,
		},
		lname: {
			type: 'boolean',
			default: true,
		},
		email: {
			type: 'boolean',
			default: true,
		},
		date: {
			type: 'boolean',
			default: true,
		},
	},
	edit: ( { attributes, setAttributes } ) => {
		const {
			id,
			fname,
			lname,
			email,
			date,
		} = attributes;

		// eslint-disable-next-line react-hooks/rules-of-hooks
		const [ tableData, setTableData ] = useState( [] );

		// eslint-disable-next-line react-hooks/exhaustive-deps
		function fetchData() {
			wp.ajax.post( 'ernest_get_table', {
				attributes: {
					id,
					fname,
					lname,
					email,
					date,
				},
			} ).done( function( response ) {
				setTableData( response );
			} );
		}

		const toggleAttribute = ( attribute ) => ( value ) => {
			setAttributes( { [ attribute ]: value } );
		};

		// eslint-disable-next-line react-hooks/rules-of-hooks
		useEffect( () => {
			fetchData();
		}, [ id, fname, lname, email, date, fetchData ] );

		return (
			<div className="wp-block-table">
				{ tableData.length > 0 ? <div dangerouslySetInnerHTML={ { __html: tableData } } /> : <p>{ ernestGutenbergTableConfig.i18n.loading }</p> }

				<InspectorControls>
					<PanelBody title="Settings">
						<ToggleControl
							label="Display ID column"
							checked={ id }
							onChange={ toggleAttribute( 'id' ) }
						/>

						<ToggleControl
							label="Display First Name column"
							checked={ fname }
							onChange={ toggleAttribute( 'fname' ) }
						/>

						<ToggleControl
							label="Display Last Name column"
							checked={ lname }
							onChange={ toggleAttribute( 'lname' ) }
						/>

						<ToggleControl
							label="Display Email column"
							checked={ email }
							onChange={ toggleAttribute( 'email' ) }
						/>

						<ToggleControl
							label="Display Date column"
							checked={ date }
							onChange={ toggleAttribute( 'date' ) }
						/>
					</PanelBody>
				</InspectorControls>
			</div>
		);
	},
	save( props ) {
		const {
			attributes: {
				id,
				fname,
				lname,
				email,
				date,
			},
		} = props;

		const dataAttributes = {
			id,
			fname,
			lname,
			email,
			date,
		};

		return (
			<div className="wp-block-table">
				<table className="ernest" data-attributes={ JSON.stringify( dataAttributes ) }></table>
			</div>
		);
	},
} );

