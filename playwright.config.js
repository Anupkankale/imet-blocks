const { defineConfig } = require( '@playwright/test' );

// Point BASE_URL at any site that has the pattern's page, e.g. BASE_URL=https://staging.example.com npm run test:e2e
module.exports = defineConfig( {
	testDir: 'tests/e2e',
	timeout: 30000,
	retries: process.env.CI ? 1 : 0,
	reporter: [ [ 'list' ] ],
	use: {
		baseURL: process.env.BASE_URL || 'http://imetworld.local',
		screenshot: 'only-on-failure',
	},
	projects: [
		{ name: 'desktop', use: { viewport: { width: 1440, height: 900 } } },
		{ name: 'mobile', use: { viewport: { width: 390, height: 844 }, isMobile: true, hasTouch: true } },
	],
} );
