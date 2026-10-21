/**
 * @wordpress/scripts config + three additions:
 *  - shared stylesheets (base, editor canvas, page layout) and editor additions (src/editor) as their own entries;
 *  - CSS url()s are left as written: they point at ../assets/ (stable file names, so fonts can be preloaded);
 *  - each block's default.html (its default inner content) is copied next to its block.json.
 */
const CopyWebpackPlugin = require( 'copy-webpack-plugin' );
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

for ( const rule of defaultConfig.module.rules ) {
	for ( const use of Array.isArray( rule.use ) ? rule.use : [] ) {
		if ( use && typeof use.loader === 'string' && /[\\/]css-loader[\\/]/.test( use.loader ) ) {
			use.options = { ...use.options, url: false };
		}
	}
}

module.exports = {
	...defaultConfig,
	entry: () => ( {
		...( typeof defaultConfig.entry === 'function' ? defaultConfig.entry() : defaultConfig.entry ),
		'shared/base': './src/shared/base.scss',
		'shared/editor-canvas': './src/shared/editor-canvas.scss',
		'shared/page-layout': './src/shared/page-layout.scss',
		'editor/index': './src/editor/index.js',
	} ),
	plugins: [
		...defaultConfig.plugins,
		new CopyWebpackPlugin( {
			patterns: [ { from: 'blocks/*/default.html', context: 'src', noErrorOnMissing: true } ],
		} ),
	],
};
