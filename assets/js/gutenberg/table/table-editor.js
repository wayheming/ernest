/* global wp, ernestGutenbergTableConfig */

'use strict';

const {useEffect, useState} = wp.element;
const {registerBlockType} = wp.blocks;
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

		const [tableData, setTableData] = useState( [] );

		function fetchData() {
			wp.ajax.post( 'ernest_get_table', {} ).done( function( response ) {
				setTableData( response );
			} );
		}

		function MyTable( data ) {
			const headersToInclude = {
				'ID': id,
				'First Name': fname,
				'Last Name': lname,
				'Email': email,
				'Date': date,
			};

			const headers = data.headers.filter( header => headersToInclude[header] );

			const rows = Object.keys( data.rows ).map( ( key ) => {
				const row = data.rows[key];
				return (
					<tr key={key}>
						{id && <td>{row.id}</td>}
						{fname && <td>{row.fname}</td>}
						{lname && <td>{row.lname}</td>}
						{email && <td>{row.email}</td>}
						{date && <td>{new Date( row.date * 1000 ).toLocaleString()}</td>}
					</tr>
				);
			} );

			const headerCells = headers.map( ( header, index ) => (
				<th key={index}>{header}</th>
			) );

			return (
				<div>
					<table>
						<thead>
						<tr>{headerCells}</tr>
						</thead>
						<tbody>{rows}</tbody>
					</table>
				</div>
			);
		}


		const toggleAttribute = ( attribute ) => ( value ) => {
			setAttributes( {[attribute]: value} );
		};

		useEffect( () => {
			fetchData();
		}, [] );

		return (
			<div className="wp-block-table">
				<h1>Ernest API Table edit</h1>

				{tableData.headers ? <MyTable headers={tableData.headers} rows={tableData.rows}/> :
					<p>{ernestGutenbergTableConfig.i18n.loading}</p>}

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



