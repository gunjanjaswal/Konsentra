<?php
/**
 * Main plugin bootstrap class.
 *
 * @package Konsentra
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class Konsentra
 *
 * Wires the moving parts together and exposes a single instance.
 */
final class Konsentra {

	/**
	 * Single instance of the class.
	 *
	 * @var Konsentra|null
	 */
	private static $instance = null;

	/**
	 * Front-end handler.
	 *
	 * @var Konsentra_Frontend
	 */
	public $frontend;

	/**
	 * Admin handler.
	 *
	 * @var Konsentra_Admin|null
	 */
	public $admin = null;

	/**
	 * Retrieve the single instance.
	 *
	 * @return Konsentra
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
		$this->frontend = new Konsentra_Frontend();

		if ( is_admin() && class_exists( 'Konsentra_Admin' ) ) {
			$this->admin = new Konsentra_Admin();
		}
	}

	/**
	 * Read the stored settings merged with defaults.
	 *
	 * @return array
	 */
	public static function get_settings() {
		$saved = get_option( KONSENTRA_OPTION, array() );

		if ( ! is_array( $saved ) ) {
			$saved = array();
		}

		return wp_parse_args( $saved, Konsentra_Settings::get_defaults() );
	}
}
