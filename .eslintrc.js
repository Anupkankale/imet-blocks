/**
 * Lint rules for the plugin's JavaScript (npm run lint:js).
 * Formatting and JSDoc rules are warnings for now (the existing code was not written to them); real mistakes such as
 * undefined variables stay errors. Fix the warnings file by file, then change them to errors.
 */
module.exports = {
	extends: [ 'plugin:@wordpress/eslint-plugin/recommended' ],
	env: { browser: true },
	ignorePatterns: [ 'build/', 'vendor/', 'node_modules/', 'tests/e2e/' ],
	rules: {
		'prettier/prettier': 'warn',
		'jsdoc/require-param': 'warn',
		'jsdoc/require-param-type': 'warn',
		'jsdoc/check-line-alignment': 'warn',
	},
};
