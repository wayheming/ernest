'use strict';

const { createElement, Fragment } = wp.element;
const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.blockEditor || wp.editor;
const { SelectControl, ToggleControl, PanelBody, Placeholder } = wp.components;

registerBlockType( 'ernest/table-block', {
	title: 'Ernest API Table',
	description: 'Ernest API Table',
	category: 'widgets',
	attributes: {
		id: {
			type: 'boolean',
		},
	},
	edit( props ) {
		const { attributes, setAttributes } = props;
		let jsx;

		wp.ajax.post( 'my-plugin/my-endpoint', {
			data: {
				message: 'Hello world!'
			}
		} ).done( function( response ) {
			setAttributes( { message: response } );
		} );

		jsx = [
			<div>
				<h1>Ernest API Table</h1>
			</div>
		];

		return jsx;
	},
	save( props ) {
		return null;
	},
} );



