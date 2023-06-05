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
	 * @var string
	 */
	const PAYWALL_COOKIE_PREFIX = 'elementor_paywall_';

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
			'paywall_payment_link',
			[
				'label' => esc_html__( 'Stripe Link', 'elementor' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://buy.stripe.com/', 'elementor' ),
				'options' => [ 'url', 'nofollow' ],
				'default' => [
					'url' => '',
					'nofollow' => true,
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'paywall_link_label',
			[
				'label' => esc_html__( 'Stripe Link Label', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Read this story for 20$', 'elementor' ),
				'default' => '',
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
		$link = $settings['paywall_payment_link'];
		$link_label = $settings['paywall_link_label'];

		$is_protected = $this->check_is_protected();

		if ( ! empty( $text ) ) :
			?>
			<div class="elementor-widget-paywall">
				<div class="paywall-text-container">
					<?php if ( $is_protected ) : ?>
						<div class="paywall-text-blur"></div>
					<?php endif; ?>

					<?php echo wp_kses_post( $text ); ?>
				</div>
				<?php if ( $is_protected && ! empty( $link['url'] ) && ! empty( $link_label ) ) : ?>
					<a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link_label ); ?></a>
				<?php endif; ?>
			</div>
			<?php
		endif;
	}

	/**
	 * Validate if user has payment confirmation or specific cookie
	 *
	 * @return bool
	 */
	private function check_is_protected() {
		$post_id = get_queried_object_id();
		$cookie_name = self::PAYWALL_COOKIE_PREFIX . $post_id;
		$is_protected_cookie = ! empty( $_COOKIE[ $cookie_name ] ) ? $_COOKIE[ $cookie_name ] : false;
		$is_payment_confirmed = ! empty( $_GET[ 'checkout_session_id' ] ) ? $_GET[ 'checkout_session_id' ] : false; // phpcs:ignore -- nonce validation is not require here.

		return ! ( ! empty( $is_payment_confirmed ) || $is_protected_cookie );
	}
}

