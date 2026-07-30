<?php
/**
 * Front-end: enqueues assets, renders the banner and handles consent logging.
 *
 * @package ConsentBannerNest
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class Consent_Banner_Nest_Frontend
 */
class Consent_Banner_Nest_Frontend {

	/**
	 * Cached settings.
	 *
	 * @var array
	 */
	private $settings;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->settings = Consent_Banner_Nest::get_settings();

		if ( empty( $this->settings['enabled'] ) ) {
			return;
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_footer', array( $this, 'render_banner' ) );

		// Shortcode so a "Cookie settings" link can be placed anywhere.
		add_shortcode( 'consent_banner_nest_settings', array( $this, 'settings_link_shortcode' ) );

		// Optional, opt-in consent logging.
		if ( ! empty( $this->settings['log_consent'] ) ) {
			add_action( 'wp_ajax_consent_banner_nest_log', array( $this, 'ajax_log_consent' ) );
			add_action( 'wp_ajax_nopriv_consent_banner_nest_log', array( $this, 'ajax_log_consent' ) );
		}
	}

	/**
	 * Register and enqueue the public CSS and JS.
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		wp_enqueue_style(
			'consent-banner-nest',
			CONSENT_BANNER_NEST_URL . 'public/css/consent-banner-nest.css',
			array(),
			CONSENT_BANNER_NEST_VERSION
		);

		wp_add_inline_style( 'consent-banner-nest', $this->inline_css() );

		wp_enqueue_script(
			'consent-banner-nest',
			CONSENT_BANNER_NEST_URL . 'public/js/consent-banner-nest.js',
			array(),
			CONSENT_BANNER_NEST_VERSION,
			true
		);

		wp_localize_script(
			'consent-banner-nest',
			'consentBannerNest',
			array(
				'cookieName'   => CONSENT_BANNER_NEST_COOKIE,
				'expiryDays'   => (int) $this->settings['consent_expiry'],
				'blockScripts' => ! empty( $this->settings['block_scripts'] ),
				'logConsent'   => ! empty( $this->settings['log_consent'] ),
				'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
				'nonce'        => wp_create_nonce( 'consent_banner_nest_log' ),
				'categories'   => array( 'necessary', 'functional', 'analytics', 'marketing' ),
			)
		);
	}

	/**
	 * Build the CSS custom properties from the saved colors.
	 *
	 * @return string
	 */
	private function inline_css() {
		$s = $this->settings;

		return sprintf(
			':root{--cp-bg:%1$s;--cp-text:%2$s;--cp-accent:%3$s;--cp-btn-text:%4$s;}',
			esc_html( $s['bg_color'] ),
			esc_html( $s['text_color'] ),
			esc_html( $s['accent_color'] ),
			esc_html( $s['button_text_color'] )
		);
	}

	/**
	 * Output the banner markup in the footer.
	 *
	 * @return void
	 */
	public function render_banner() {
		$s          = $this->settings;
		$categories = Consent_Banner_Nest_Settings::get_categories();

		// Only render the categories the admin has kept enabled.
		$active = array( 'necessary' => $categories['necessary'] );
		foreach ( array( 'functional', 'analytics', 'marketing' ) as $key ) {
			if ( ! empty( $s[ 'cat_' . $key ] ) ) {
				$active[ $key ] = $categories[ $key ];
			}
		}

		$privacy_url = '';
		if ( ! empty( $s['privacy_page'] ) ) {
			$privacy_url = get_permalink( (int) $s['privacy_page'] );
		}

		$wrap_classes = array(
			'consent-banner-nest',
			'cp-pos-' . sanitize_html_class( $s['position'] ),
			'cp-layout-' . sanitize_html_class( $s['layout'] ),
		);
		?>
		<div id="consent-banner-nest" class="<?php echo esc_attr( implode( ' ', $wrap_classes ) ); ?>" role="dialog" aria-live="polite" aria-label="<?php esc_attr_e( 'Cookie consent', 'consent-banner-nest' ); ?>" hidden>
			<div class="cp-inner">
				<div class="cp-content">
					<?php if ( ! empty( $s['heading'] ) ) : ?>
						<h2 class="cp-heading"><?php echo esc_html( $s['heading'] ); ?></h2>
					<?php endif; ?>
					<div class="cp-message">
						<?php echo wp_kses_post( wpautop( $s['message'] ) ); ?>
						<?php if ( $privacy_url ) : ?>
							<a class="cp-privacy-link" href="<?php echo esc_url( $privacy_url ); ?>"><?php echo esc_html( $s['privacy_label'] ); ?></a>
						<?php endif; ?>
					</div>

					<div class="cp-prefs" hidden>
						<?php foreach ( $active as $key => $cat ) : ?>
							<label class="cp-pref<?php echo ! empty( $cat['locked'] ) ? ' is-locked' : ''; ?>">
								<span class="cp-pref-body">
									<span class="cp-pref-label"><?php echo esc_html( $cat['label'] ); ?></span>
									<span class="cp-pref-desc"><?php echo esc_html( $cat['description'] ); ?></span>
								</span>
								<span class="cp-switch">
									<input
										type="checkbox"
										class="cp-pref-input"
										data-category="<?php echo esc_attr( $key ); ?>"
										<?php checked( ! empty( $cat['locked'] ) ); ?>
										<?php disabled( ! empty( $cat['locked'] ) ); ?>
									/>
									<span class="cp-switch-track" aria-hidden="true"><span class="cp-switch-thumb"></span></span>
								</span>
							</label>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="cp-actions">
					<?php if ( ! empty( $s['show_settings'] ) ) : ?>
						<button type="button" class="cp-btn cp-btn-ghost" data-cp-action="settings"><?php echo esc_html( $s['settings_label'] ); ?></button>
					<?php endif; ?>
					<?php if ( ! empty( $s['show_reject'] ) ) : ?>
						<button type="button" class="cp-btn cp-btn-ghost" data-cp-action="reject"><?php echo esc_html( $s['reject_label'] ); ?></button>
					<?php endif; ?>
					<button type="button" class="cp-btn cp-btn-save" data-cp-action="save" hidden><?php echo esc_html( $s['save_label'] ); ?></button>
					<button type="button" class="cp-btn cp-btn-accept" data-cp-action="accept"><?php echo esc_html( $s['accept_label'] ); ?></button>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Shortcode that outputs a link to reopen the preferences panel.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function settings_link_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'label' => __( 'Cookie settings', 'consent-banner-nest' ),
			),
			$atts,
			'consent_banner_nest_settings'
		);

		return sprintf(
			'<a href="#" class="cp-reopen" data-cp-action="reopen">%s</a>',
			esc_html( $atts['label'] )
		);
	}

	/**
	 * AJAX handler that records a consent decision.
	 *
	 * Stores an anonymised record: a hashed IP, timestamp and the categories
	 * the visitor agreed to. No raw personal data is kept.
	 *
	 * @return void
	 */
	public function ajax_log_consent() {
		check_ajax_referer( 'consent_banner_nest_log', 'nonce' );

		$raw = isset( $_POST['categories'] ) ? sanitize_text_field( wp_unslash( $_POST['categories'] ) ) : '';
		$allowed  = array( 'necessary', 'functional', 'analytics', 'marketing' );
		$selected = array_values( array_intersect( $allowed, array_map( 'trim', explode( ',', $raw ) ) ) );

		$log   = get_option( 'consent_banner_nest_log', array() );
		if ( ! is_array( $log ) ) {
			$log = array();
		}

		$ip_hash = '';
		if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$ip      = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
			$ip_hash = wp_hash( $ip );
		}

		$log[] = array(
			'time'       => current_time( 'mysql' ),
			'ip_hash'    => $ip_hash,
			'categories' => $selected,
		);

		// Keep the log from growing without bound.
		if ( count( $log ) > 5000 ) {
			$log = array_slice( $log, -5000 );
		}

		update_option( 'consent_banner_nest_log', $log, false );

		wp_send_json_success();
	}
}
