<?php
/**
 * Main plugin bootstrap class.
 *
 * @package ConsentPilot
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class Consent_Pilot
 *
 * Wires the moving parts together and exposes a single instance.
 */
final class Consent_Pilot {

	/**
	 * Single instance of the class.
	 *
	 * @var Consent_Pilot|null
	 */
	private static $instance = null;

	/**
	 * Front-end handler.
	 *
	 * @var Consent_Pilot_Frontend
	 */
	public $frontend;

	/**
	 * Admin handler.
	 *
	 * @var Consent_Pilot_Admin|null
	 */
	public $admin = null;

	/**
	 * Retrieve the single instance.
	 *
	 * @return Consent_Pilot
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
		add_action( 'init', array( $this, 'load_textdomain' ) );
		add_action( 'plugins_loaded', array( $this, 'init_components' ) );
	}

	/**
	 * Load the plugin translations.
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'consent-pilot', false, dirname( CONSENT_PILOT_BASENAME ) . '/languages' );
	}

	/**
	 * Instantiate the front-end and admin components.
	 *
	 * @return void
	 */
	public function init_components() {
		$this->frontend = new Consent_Pilot_Frontend();

		if ( is_admin() && class_exists( 'Consent_Pilot_Admin' ) ) {
			$this->admin = new Consent_Pilot_Admin();
		}
	}

	/**
	 * Read the stored settings merged with defaults.
	 *
	 * @return array
	 */
	public static function get_settings() {
		$saved = get_option( CONSENT_PILOT_OPTION, array() );

		if ( ! is_array( $saved ) ) {
			$saved = array();
		}

		return wp_parse_args( $saved, Consent_Pilot_Settings::get_defaults() );
	}
}
