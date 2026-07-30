<?php
/**
 * Admin: settings page, registration and asset loading.
 *
 * @package ConsentPilot
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class Consent_Pilot_Admin
 */
class Consent_Pilot_Admin {

	/**
	 * Settings page hook suffix.
	 *
	 * @var string
	 */
	private $hook = '';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_filter( 'plugin_action_links_' . CONSENT_PILOT_BASENAME, array( $this, 'action_links' ) );
		add_filter( 'plugin_row_meta', array( $this, 'row_meta' ), 10, 2 );
	}

	/**
	 * Add the options page under Settings.
	 *
	 * @return void
	 */
	public function add_menu() {
		$this->hook = add_options_page(
			__( 'Consent Pilot', 'consent-pilot' ),
			__( 'Consent Pilot', 'consent-pilot' ),
			'manage_options',
			'consent-pilot',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Register the single settings option and its sanitize callback.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting(
			'consent_pilot_group',
			CONSENT_PILOT_OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( 'Consent_Pilot_Settings', 'sanitize' ),
				'default'           => Consent_Pilot_Settings::get_defaults(),
			)
		);
	}

	/**
	 * Enqueue admin styles only on our settings screen.
	 *
	 * @param string $hook Current admin page hook.
	 * @return void
	 */
	public function enqueue_assets( $hook ) {
		if ( $hook !== $this->hook ) {
			return;
		}

		wp_enqueue_style(
			'consent-pilot-admin',
			CONSENT_PILOT_URL . 'admin/css/admin.css',
			array(),
			CONSENT_PILOT_VERSION
		);

		wp_enqueue_script(
			'consent-pilot-admin',
			CONSENT_PILOT_URL . 'admin/js/admin.js',
			array( 'wp-color-picker' ),
			CONSENT_PILOT_VERSION,
			true
		);
		wp_enqueue_style( 'wp-color-picker' );
	}

	/**
	 * Add a quick "Settings" link on the plugins screen.
	 *
	 * @param array $links Existing action links.
	 * @return array
	 */
	public function action_links( $links ) {
		$url  = admin_url( 'options-general.php?page=consent-pilot' );
		$link = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'consent-pilot' ) . '</a>';
		array_unshift( $links, $link );
		return $links;
	}

	/**
	 * Add support and author links under the plugin description.
	 *
	 * @param array  $links Existing row meta links.
	 * @param string $file  Plugin file being processed.
	 * @return array
	 */
	public function row_meta( $links, $file ) {
		if ( CONSENT_PILOT_BASENAME !== $file ) {
			return $links;
		}

		$links[] = '<a href="https://ko-fi.com/gunjanjaswal" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Support on Ko-fi', 'consent-pilot' ) . '</a>';
		$links[] = '<a href="https://www.gunjanjaswal.me" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Author', 'consent-pilot' ) . '</a>';

		return $links;
	}

	/**
	 * Render the settings page view.
	 *
	 * @return void
	 */
	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings   = Consent_Pilot::get_settings();
		$categories = Consent_Pilot_Settings::get_categories();

		require CONSENT_PILOT_PATH . 'admin/views/settings-page.php';
	}
}
