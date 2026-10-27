/** Unit tests for the front-end scripts (npm run test:unit): jsdom, with WordPress's Babel preset for the ES modules. */
module.exports = {
	testEnvironment: 'jsdom',
	testMatch: [ '**/tests/unit/**/*.test.js' ],
	transform: { '^.+\\.js$': [ 'babel-jest', { presets: [ '@wordpress/babel-preset-default' ] } ] },
};
