const { test, expect } = require( '@playwright/test' );
const WpAdminPage = require( '../../../pages/wp-admin-page.js' );

test( 'Basic paywall widget sanity test', async ( { page }, testInfo ) => {
	const contentText = 'Lorem ipsum dolor sit amet';
	// Arrange.
	const wpAdmin = new WpAdminPage( page, testInfo );
	const editor = await wpAdmin.useElementorCleanPost();
	await editor.addWidget( 'paywall' );
	// Act.
	await page.frameLocator( '.elementor-control-content iframe' ).locator( '#tinymce' ).fill( contentText );
	await page.locator( '[data-setting="url"]' ).fill( 'https://buy.stripe.com/test_3cs4ht3ffeWkcs84gg' );
	await page.locator( '[data-setting="paywall_link_label"]' ).fill( 'Read this story for 16$' );
	// Assert.
	const div = await editor.getPreviewFrame().waitForSelector( '.paywall-text-container' );
	const text = await div.textContent();
	expect( text.trim() ).toBe( contentText );
} );
