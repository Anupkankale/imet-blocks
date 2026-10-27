/**
 * Lint rules for the plugin's JavaScript (npm run lint:js), ESLint flat config, on top of the WordPress defaults
 * from @wordpress/scripts (which already know the WordPress packages are provided at run time).
 * Formatting and JSDoc rules are warnings for now (the existing code was not written to them); real mistakes such as
 * undefined variables stay errors. Fix the warnings file by file, then change them to errors.
 */
const base = require( '@wordpress/scripts/config/eslint.config.cjs' );
const globals = require( 'globals' );

module.exports = [
	...base,
	{ ignores: [ 'tests/e2e/**', 'playwright-report/**', 'test-results/**' ] },
	{
		files: [ '**/*.js' ],
		languageOptions: { globals: { ...globals.browser } },
		rules: {
			'prettier/prettier': 'warn',
			'jsdoc/require-param': 'warn',
			'jsdoc/require-param-type': 'warn',
			'jsdoc/check-line-alignment': 'warn',
			'jsdoc/check-tag-names': [ 'warn', { definedTags: [ 'jest-environment' ] } ],
			// @wordpress/* packages are provided by WordPress at run time (webpack externals), not installed in node_modules.
			'import/no-unresolved': 'off',
			'import/no-extraneous-dependencies': 'off',
		},
	},
	{
		files: [ 'tests/**/*.js', 'jest.config.js', 'eslint.config.js', 'playwright.config.js', 'webpack.config.js' ],
		languageOptions: { globals: { ...globals.node, ...globals.jest } },
		rules: { 'import/no-extraneous-dependencies': 'off' },
	},
];
