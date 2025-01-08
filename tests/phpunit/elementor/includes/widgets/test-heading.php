<?php

namespace Elementor\Tests\Phpunit\Includes\Widgets;

use Elementor\Plugin;
use ElementorEditorTesting\Elementor_Test_Base;
use Elementor\Heading;

class Test_Widget_Heading extends Elementor_Test_Base {

	const HEADING_NON_SECURE_SETTINGS_MOCK = [
		'id' => 'e8e55a1',
		'elType' => 'widget',
		'settings' => [
			'title' => '<div aria-disabled="true">Title<script>alert()</script></div>',
			'size' => 'large',
			'header_size' => 'h1',
			'link' => [
				'url' => ''
			]
		],
		'widgetType' => 'heading',
	];

	public function test_sanitize__data_attributes() {
		$html_to_sanitize = '
			<div data-foo="bar" data-settings="{&quot;submenu_icon&quot;:{&quot;value&quot;:&quot;&lt;img src=x onerror=alert()&gt;&quot;,&quot;library&quot;:&quot;&quot;},&quot;layout&quot;:&quot;horizontal&quot;,&quot;toggle&quot;:&quot;burger&quot;}">
				<h1>Heading</h1>
			</div>
		';
		$heading_widget = Plugin::instance()->widgets_manager->get_widget_types( 'heading' );

		// assert that string is not containing data attributes
		$this->assertStringNotContainsString( 'data-foo', $heading_widget->sanitize( $html_to_sanitize ) );
		$this->assertStringNotContainsString( 'data-settings', $heading_widget->sanitize( $html_to_sanitize ) );
		$this->assertStringNotContainsString( 'alert()', $heading_widget->sanitize( $html_to_sanitize ) );
	}

	public function test_sanitize__img() {
		$html_to_sanitize = '
			<div>
				<img src="x" onerror="alert()">
			</div>
		';
		$heading_widget = Plugin::instance()->widgets_manager->get_widget_types( 'heading' );

		// assert that string is not containing data attributes
		$this->assertStringNotContainsString( '<img src="x" onerror="alert()">', $heading_widget->sanitize( $html_to_sanitize ) );
	}

	public function test_render__data_attributes_for_admin() {
		// Arrange
		$this->act_as_admin();
		$heading = Plugin::$instance->elements_manager->create_element_instance( static::HEADING_NON_SECURE_SETTINGS_MOCK );

		// Act
		ob_start();
		$heading->render_content();
		$rendered_content = ob_get_clean();

		// Assert
		$this->assertStringContainsString( 'aria-disabled', $rendered_content );
		$this->assertStringContainsString( '<script>alert()</script>', $rendered_content );
	}

	public function test_render__data_attributes_for_editor() {
		// Arrange
		$this->act_as_editor();
		$heading = Plugin::$instance->elements_manager->create_element_instance( static::HEADING_NON_SECURE_SETTINGS_MOCK );

		// Act
		ob_start();
		$heading->render_content();
		$rendered_content = ob_get_clean();

		// Assert
		$this->assertStringNotContainsString( 'aria-disabled', $rendered_content );
		$this->assertStringNotContainsString( '<script>alert()</script>', $rendered_content );
	}
}
