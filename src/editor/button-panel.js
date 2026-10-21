/**
 * "IMET button" panel on every core/button block: pick a colour and a size instead of typing classes.
 * Writes the classes the button system styles (src/shared/_buttons.scss), e.g. "btn-navy btn-md";
 * any other classes on the button are kept.
 */
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from '@wordpress/components';

const COLOURS = [
	{ value: 'btn-navy', label: 'Navy (primary)' },
	{ value: 'btn-white', label: 'White (on dark backgrounds)' },
	{ value: 'btn-gold', label: 'Gold' },
	{ value: 'btn-outline', label: 'Outline' },
	{ value: 'btn-outline-light', label: 'Outline light (on dark backgrounds)' },
];
const SIZES = [
	{ value: 'btn-sm', label: 'S' },
	{ value: 'btn-md', label: 'M' },
	{ value: 'btn-lg', label: 'L' },
];
// Names used by earlier versions of the page, read as their new equivalent.
const ALIASES = { 'btn-xs': 'btn-sm', 'btn-eligible': 'btn-lg', 'btn-cta': 'btn-lg', 'btn-underline': null };

const tokens = ( className ) => ( className || '' ).split( /\s+/ ).filter( Boolean );

function current( className, options, fallback ) {
	const values = options.map( ( o ) => o.value );
	const found = tokens( className )
		.map( ( c ) => ( c in ALIASES ? ALIASES[ c ] : c ) )
		.find( ( c ) => values.includes( c ) );
	return found || fallback;
}

function withClass( className, options, value ) {
	const drop = new Set( [ ...options.map( ( o ) => o.value ), ...Object.keys( ALIASES ) ] );
	return [ ...tokens( className ).filter( ( c ) => ! drop.has( c ) ), value ].join( ' ' );
}

const withButtonPanel = createHigherOrderComponent(
	( BlockEdit ) => ( props ) => {
		if ( props.name !== 'core/button' ) {
			return <BlockEdit { ...props } />;
		}
		const { attributes, setAttributes } = props;
		const colour = current( attributes.className, COLOURS, 'btn-navy' );
		const size = current( attributes.className, SIZES, 'btn-md' );
		// Keep both choices explicit in the class list (also normalises old names).
		const set = ( options, value ) => {
			const nextColour = options === COLOURS ? value : colour;
			const nextSize = options === SIZES ? value : size;
			setAttributes( {
				className: withClass( withClass( attributes.className, COLOURS, nextColour ), SIZES, nextSize ),
			} );
		};
		return (
			<>
				<BlockEdit { ...props } />
				<InspectorControls>
					<PanelBody title="IMET button" initialOpen>
						<SelectControl
							label="Colour"
							value={ colour }
							options={ COLOURS }
							onChange={ ( v ) => set( COLOURS, v ) }
							__nextHasNoMarginBottom
							__next40pxDefaultSize
						/>
						<ToggleGroupControl
							label="Size"
							value={ size }
							onChange={ ( v ) => set( SIZES, v ) }
							isBlock
							__nextHasNoMarginBottom
							__next40pxDefaultSize
						>
							{ SIZES.map( ( s ) => (
								<ToggleGroupControlOption key={ s.value } value={ s.value } label={ s.label } />
							) ) }
						</ToggleGroupControl>
						<p style={ { marginTop: 12, color: '#757575', fontSize: 12 } }>
							S 48px · M 60px · L 72px tall (a little smaller on phones). Same font, corners and hover for
							every button.
						</p>
					</PanelBody>
				</InspectorControls>
			</>
		);
	},
	'withImetButtonPanel'
);

addFilter( 'editor.BlockEdit', 'imet-blocks/button-panel', withButtonPanel );
