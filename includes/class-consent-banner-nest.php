<?php
/**
 * Main plugin bootstrap class.
 *
 * @package ConsentBannerNest
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class Consent_Banner_Nest
 *
 * Wires the moving parts together and exposes a single instance.
 */
final class Consent_Banner_Nest {

	/**
	 * Single instance of the class.
	 *
	 * @var Consent_Banner_Nest|null
	 */
	private static $instance = null;

	/**
	 * Front-end handler.
	 *
	 * @var Consent_Banner_Nest_Frontend
	 */
	public $frontend;

	/**
	 * Admin handler.
	 *
	 * @var Consent_Banner_Nest_Admin|null
	 */
	public $admin = null;

	/**
	 * Retrieve the single instance.
	 *
	 * @return Consent_Banner_Nest
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor. Hooks are registered here.
	 */
	private function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init_components' ) );
	}

	/**
	 * Instantiate the front-end and admin components.
	 *
	 * @return void
	 */
	public function init_components() {
		$this->frontend = new Consent_Banner_Nest_Frontend();

		if ( is_admin() && class_exists( 'Consent_Banner_Nest_Admin' ) ) {
			$this->admin = new Consent_Banner_Nest_Admin();
		}
	}

	/**
	 * Read the stored settings merged with defaults.
	 *
	 * @return array
	 */
	public static function get_settings() {
		$saved = get_option( CONSENT_BANNER_NEST_OPTION, array() );

		if ( ! is_array( $saved ) ) {
			$saved = array();
		}

		return wp_parse_args( $saved, Consent_Banner_Nest_Settings::get_defaults() );
	}
}
