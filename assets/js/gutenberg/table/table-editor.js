/* global wp, ernestGutenbergTableConfig */

'use strict';

const {useEffect, useState} = wp.element;
const {registerBlockType} = wp.blocks;
const {serverSideRender: ServerSideRender = wp.components.ServerSideRender} = wp;
const {ToggleControl, PanelBody, Placeholder} = wp.components;
const {InspectorControls, useBlockProps} = wp.blockEditor || wp.editor;

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
	edit: ( {attributes, setAttributes} ) => {
		const {
			id,
			fname,
			lname,
			email,
			date,
		} = attributes;

		const [ tableData, setMyData ] = useState( [] );

		function fetchData() {
			wp.ajax.post( 'ernest_get_table', {
			} ).done( function( response ) {
				setMyData( response );
			} );
		}

		const toggleAttribute = ( attribute ) => ( value ) => {
			setAttributes( {[attribute]: value} );
		};

		useEffect( () => {
			fetchData();
		}, [id, fname, lname, email, date] );

		return (
			<div className="wp-block-table">
				<h1>Ernest API Table edit</h1>

				{ tableData.length > 0 &&
				  <table>
					  <thead>
					  <tr>
						  <th>Column 1</th>
						  <th>Column 2</th>
					  </tr>
					  </thead>
					  <tbody>
					  { tableData.map( ( row ) => (
						  <tr key={ row.id }>
							  <td>{ row.column1 }</td>
							  <td>{ row.column2 }</td>
						  </tr>
					  ) ) }
					  </tbody>
				  </table>
				}

				<InspectorControls>
					<PanelBody title="Settings">
						<ToggleControl
							label="Display ID column"
							checked={id}
							onChange={toggleAttribute( 'id' )}
						/>

						<ToggleControl
							label="Display First Name column"
							checked={fname}
							onChange={toggleAttribute( 'fname' )}
						/>

						<ToggleControl
							label="Display Last Name column"
							checked={lname}
							onChange={toggleAttribute( 'lname' )}
						/>

						<ToggleControl
							label="Display Email column"
							checked={email}
							onChange={toggleAttribute( 'email' )}
						/>

						<ToggleControl
							label="Display Date column"
							checked={date}
							onChange={toggleAttribute( 'date' )}
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
			id: id,
			firstName: fname,
			lastName: lname,
			email: email,
			date: date,
		};

		return (
			<div className="wp-block-table">
				<h1>Ernest API Table</h1>
				<table className="ernest" data-columns={JSON.stringify( dataAttributes )}></table>
			</div>
		);
	},
} );



