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

test( 'Basic paywall widget frontend test', async ( { page }, testInfo ) => {
	const txt = 'Lorem ipsum dolor sit amet';
	const href = 'https://buy.stripe.com/test_3cs4ht3ffeWkcs84gg';
	const label = 'Read this story for 16$';
	// Arrange.
	const wpAdmin = new WpAdminPage( page, testInfo );
	const editor = await wpAdmin.useElementorCleanPost();
	await editor.addWidget( 'paywall' );
	// Act.
	await page.frameLocator( '.elementor-control-content iframe' ).locator( '#tinymce' ).fill( txt );
	await page.locator( '[data-setting="url"]' ).fill( href );
	await page.locator( '[data-setting="paywall_link_label"]' ).fill( label );
	await editor.publishAndViewPage();
	// Assert.
	const stripeLink = await page.locator( '.paywall-buy-link' );
	const stripeHref = await stripeLink.getAttribute( 'href' );
	const stripeLabel = await stripeLink.textContent();
	expect( stripeHref ).toBe( href );
	expect( stripeLabel.trim() ).toBe( label );
} );
