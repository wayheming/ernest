/* global wp, ernestGutenbergConfig */

'use strict';

const {useEffect, useState} = wp.element;
const {registerBlockType} = wp.blocks;
const {ToggleControl, PanelBody, Placeholder} = wp.components;
const {InspectorControls} = wp.blockEditor || wp.editor;

registerBlockType( 'ernest/table-block', {
	title: ernestGutenbergConfig.i18n.title,
	description: ernestGutenbergConfig.i18n.description,
	category: 'widgets',
	attributes: {
		displayId: {
			type: 'boolean',
			default: true,
		},
		displayFirstName: {
			type: 'boolean',
			default: true,
		},
		displayLastName: {
			type: 'boolean',
			default: true,
		},
		displayEmail: {
			type: 'boolean',
			default: true,
		},
		displayDate: {
			type: 'boolean',
			default: true,
		},
	},
	edit: ( {attributes, setAttributes} ) => {
		const {
			displayId,
			displayFirstName,
			displayLastName,
			displayEmail,
			displayDate,
		} = attributes;

		const toggleAttribute = ( attribute ) => ( value ) => {
			setAttributes( {[attribute]: value} );
		};

		return [
			<Placeholder>
				<h1>Ernest API Table</h1>
			</Placeholder>,

			<InspectorControls>
				<PanelBody title="Settings">
					<ToggleControl
						label="Display ID column"
						checked={displayId}
						onChange={toggleAttribute( 'displayId' )}
					/>

					<ToggleControl
						label="Display First Name column"
						checked={displayFirstName}
						onChange={toggleAttribute( 'displayFirstName' )}
					/>

					<ToggleControl
						label="Display Last Name column"
						checked={displayLastName}
						onChange={toggleAttribute( 'displayLastName' )}
					/>

					<ToggleControl
						label="Display Email column"
						checked={displayEmail}
						onChange={toggleAttribute( 'displayEmail' )}
					/>

					<ToggleControl
						label="Display Date column"
						checked={displayDate}
						onChange={toggleAttribute( 'displayDate' )}
					/>
				</PanelBody>
			</InspectorControls>,
		];
	},
	save( props ) {
		const {
			attributes: {
				displayId,
				displayFirstName,
				displayLastName,
				displayEmail,
				displayDate,
			},
		} = props;

		const dataAttributes = {
			id: displayId,
			firstName: displayFirstName,
			lastName: displayLastName,
			email: displayEmail,
			date: displayDate,
		};

		return [
			<h1>Ernest API Table</h1>,
			<table className="ernest" data-columns={JSON.stringify( dataAttributes )}></table>,
		];
	},
} );



