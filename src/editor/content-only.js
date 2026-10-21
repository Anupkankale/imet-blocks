/**
 * Pages using the IMET Landing template are edited content-only ("post-only"): with block themes the editor
 * otherwise previews them inside the theme's own page template (its title, featured image and narrow content
 * column), which the IMET Landing template doesn't use. Also applies when the template is picked in the editor.
 */
import { subscribe, select, dispatch } from '@wordpress/data';
import { store as editorStore } from '@wordpress/editor';

const TEMPLATE = 'imet-blocks-landing';

subscribe( () => {
	const editor = select( editorStore );
	if ( ! editor?.getCurrentPostType || editor.getCurrentPostType() !== 'page' || ! editor.getRenderingMode ) {
		return;
	}
	if ( editor.getEditedPostAttribute( 'template' ) !== TEMPLATE ) {
		return;
	}
	const mode = editor.getRenderingMode();
	if ( mode && mode !== 'post-only' ) {
		dispatch( editorStore ).setRenderingMode( 'post-only' );
	}
}, editorStore );
