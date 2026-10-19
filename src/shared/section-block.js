/**
 * Every IMET section is the same kind of block: a wrapper element (section/nav/div with the design's classes)
 * around normal, freely editable core blocks. block.json → "imet" says which element and classes:
 *   { "tagName": "section", "className": "section faq", "anchor": "faq" }
 *
 * - New block: filled with its default content (default.html next to block.json, passed in by PHP as
 *   window.imetBlocks.defaults) and given its default anchor, so links like #faq work straight away.
 * - "Transform to": a core Group carrying the section's class (pages built before these blocks existed) can be
 *   turned into the block in the editor; WP-CLI `wp imet migrate` does the same for whole pages.
 */
import { registerBlockType, createBlock, parse } from '@wordpress/blocks';
import {
	useBlockProps,
	useInnerBlocksProps,
	store as blockEditorStore,
} from '@wordpress/block-editor';
import { useSelect, useDispatch } from '@wordpress/data';
import { useEffect } from '@wordpress/element';

export function registerSectionBlock( metadata, settings = {} ) {
	const { tagName: Tag = 'section', className = '', anchor: defaultAnchor = '' } = metadata.imet || {};
	const classes = `imet-section ${ className }`.trim();
	const key = className.split( ' ' ).pop(); // the class that identifies this section, e.g. "faq"

	function Edit( { clientId, attributes, setAttributes } ) {
		const isEmpty = useSelect(
			( select ) => select( blockEditorStore ).getBlockCount( clientId ) === 0,
			[ clientId ]
		);
		const { replaceInnerBlocks } = useDispatch( blockEditorStore );

		useEffect( () => {
			if ( ! isEmpty ) {
				return;
			}
			const html = window.imetBlocks?.defaults?.[ metadata.name ];
			if ( html ) {
				replaceInnerBlocks( clientId, parse( html ), false );
			}
			if ( defaultAnchor && ! attributes.anchor ) {
				setAttributes( { anchor: defaultAnchor } );
			}
		}, [] ); // eslint-disable-line react-hooks/exhaustive-deps -- only when the block is first inserted

		const innerBlocksProps = useInnerBlocksProps( useBlockProps( { className: classes } ) );
		return <Tag { ...innerBlocksProps } />;
	}

	function save() {
		return <Tag { ...useInnerBlocksProps.save( useBlockProps.save( { className: classes } ) ) } />;
	}

	return registerBlockType( metadata, {
		edit: Edit,
		save,
		transforms: {
			from: [
				{
					type: 'block',
					blocks: [ 'core/group' ],
					isMatch: ( { className: groupClass } ) =>
						!! groupClass && groupClass.split( /\s+/ ).includes( key ),
					transform: ( { anchor, className: groupClass = '' }, innerBlocks ) => {
						// Keep classes an editor added to the group; the section's own classes come from the block.
						const own = new Set( className.split( ' ' ) );
						const extra = groupClass.split( /\s+/ ).filter( ( c ) => c && ! own.has( c ) ).join( ' ' );
						return createBlock(
							metadata.name,
							{ anchor, ...( extra && { className: extra } ) },
							innerBlocks
						);
					},
				},
			],
		},
		...settings,
	} );
}
