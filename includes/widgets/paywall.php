<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Elementor Paywall Widget.
 *
 * Elementor widget that inserts paywall content into wp
 *
 * @since 1.0.0
 */
class Widget_Paywall extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve Paywall widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_name() {
		return 'paywall';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Paywall widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_title() {
		return esc_html__( 'Paywall', 'elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Paywall widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_icon() {
		return 'eicon-product-description';
	}

	/**
	 * Get custom help URL.
	 *
	 * Retrieve a URL where the user can get more information about the widget.
	 *
	 * @return string Widget help URL.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_custom_help_url() {
		return 'https://developers.elementor.com/docs/widgets/';
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the paywall widget belongs to.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the paywall widget belongs to.
	 *
	 * @return array Widget keywords.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_keywords() {
		return [ 'paywall', 'text', 'stripe' ];
	}

	/**
	 * Register paywall widget controls.
	 *
	 * Add input fields to allow the user to customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'paywall_text',
			[
				'label' => esc_html__( 'Text', 'elementor' ),
				'type' => Controls_Manager::WYSIWYG,
				'placeholder' => esc_html__( 'Type your text here', 'elementor' ),
			]
		);

		$this->add_control(
			'paywall_price',
			[
				'label' => esc_html__( 'Price', 'elementor' ),
				'type' => Controls_Manager::NUMBER,
				'placeholder' => esc_html__( 'Type your text here', 'elementor' ),
				'min' => 5,
				'max' => 100,
				'step' => 1,
				'default' => 10,
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render paywall widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		$text = $settings['paywall_text'];
		$price = $settings['paywall_price'];

		if ( ! empty( $text ) ) :
			?>
			<div class="elementor-widget-paywall">
				<div class="paywall-text-container">
					<div class="paywall-text"></div>
					<?php echo wp_kses_post( $text ); ?>
				</div>
				<button>Read this story for <?php echo esc_html( $price ); ?>$</button>
			</div>
			<?php
		endif;
	}

}

