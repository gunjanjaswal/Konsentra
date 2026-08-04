<?php
/**
 * Settings model: defaults, sanitization and the option schema.
 *
 * @package Konsentra
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class Konsentra_Settings
 */
class Konsentra_Settings {

	/**
	 * Default settings used on activation and as a fallback.
	 *
	 * @return array
	 */
	public static function get_defaults() {
		return array(
			// Banner behaviour.
			'enabled'            => 1,
			'position'           => 'bottom', // bottom | top | bottom-left | bottom-right.
			'layout'             => 'bar',    // bar | box.
			'consent_expiry'     => 180,      // Days before consent is asked again.
			'show_reject'        => 1,
			'show_settings'      => 1,

			// Content.
			'heading'            => __( 'We value your privacy', 'konsentra' ),
			'message'            => __( 'We use cookies to improve your experience, analyse traffic and personalise content. You can accept all cookies or manage your preferences.', 'konsentra' ),
			'accept_label'       => __( 'Accept all', 'konsentra' ),
			'reject_label'       => __( 'Reject all', 'konsentra' ),
			'settings_label'     => __( 'Manage preferences', 'konsentra' ),
			'save_label'         => __( 'Save preferences', 'konsentra' ),
			'privacy_label'      => __( 'Privacy Policy', 'konsentra' ),
			'privacy_page'       => 0,

			// Appearance.
			'bg_color'           => '#1f2933',
			'text_color'         => '#ffffff',
			'accent_color'       => '#2f80ed',
			'button_text_color'  => '#ffffff',

			// Categories. Necessary is always on and cannot be disabled.
			'cat_functional'     => 1,
			'cat_analytics'      => 1,
			'cat_marketing'      => 1,

			// Script blocking.
			'block_scripts'      => 1,

			// Privacy.
			'log_consent'        => 0,
		);
	}

	/**
	 * The cookie categories the banner exposes.
	 *
	 * @return array
	 */
	public static function get_categories() {
		return array(
			'necessary'  => array(
				'label'       => __( 'Strictly necessary', 'konsentra' ),
				'description' => __( 'Required for the site to function. These cannot be switched off.', 'konsentra' ),
				'locked'      => true,
			),
			'functional' => array(
				'label'       => __( 'Functional', 'konsentra' ),
				'description' => __( 'Remember your choices such as language or region for a better experience.', 'konsentra' ),
				'locked'      => false,
			),
			'analytics'  => array(
				'label'       => __( 'Analytics', 'konsentra' ),
				'description' => __( 'Help us understand how visitors interact with the site.', 'konsentra' ),
				'locked'      => false,
			),
			'marketing'  => array(
				'label'       => __( 'Marketing', 'konsentra' ),
				'description' => __( 'Used to deliver relevant ads and measure their performance.', 'konsentra' ),
				'locked'      => false,
			),
		);
	}

	/**
	 * Sanitize the settings array before it is stored.
	 *
	 * Registered as the sanitize callback for register_setting().
	 *
	 * @param array $input Raw submitted values.
	 * @return array
	 */
	public static function sanitize( $input ) {
		$defaults = self::get_defaults();
		$clean    = array();

		if ( ! is_array( $input ) ) {
			$input = array();
		}

		// Checkboxes / toggles.
		$booleans = array(
			'enabled',
			'show_reject',
			'show_settings',
			'cat_functional',
			'cat_analytics',
			'cat_marketing',
			'block_scripts',
			'log_consent',
		);
		foreach ( $booleans as $key ) {
			$clean[ $key ] = empty( $input[ $key ] ) ? 0 : 1;
		}

		// Enumerated values.
		$positions          = array( 'bottom', 'top', 'bottom-left', 'bottom-right' );
		$clean['position']  = ( isset( $input['position'] ) && in_array( $input['position'], $positions, true ) )
			? $input['position']
			: $defaults['position'];

		$layouts         = array( 'bar', 'box' );
		$clean['layout'] = ( isset( $input['layout'] ) && in_array( $input['layout'], $layouts, true ) )
			? $input['layout']
			: $defaults['layout'];

		// Numbers.
		$clean['consent_expiry'] = isset( $input['consent_expiry'] )
			? absint( $input['consent_expiry'] )
			: $defaults['consent_expiry'];
		if ( $clean['consent_expiry'] < 1 ) {
			$clean['consent_expiry'] = $defaults['consent_expiry'];
		}

		$clean['privacy_page'] = isset( $input['privacy_page'] ) ? absint( $input['privacy_page'] ) : 0;

		// Text fields.
		$text_fields = array(
			'heading',
			'accept_label',
			'reject_label',
			'settings_label',
			'save_label',
			'privacy_label',
		);
		foreach ( $text_fields as $key ) {
			$clean[ $key ] = isset( $input[ $key ] )
				? sanitize_text_field( wp_unslash( $input[ $key ] ) )
				: $defaults[ $key ];
		}

		// Message allows a small amount of inline markup.
		$clean['message'] = isset( $input['message'] )
			? wp_kses_post( wp_unslash( $input['message'] ) )
			: $defaults['message'];

		// Colors.
		$colors = array( 'bg_color', 'text_color', 'accent_color', 'button_text_color' );
		foreach ( $colors as $key ) {
			$value         = isset( $input[ $key ] ) ? sanitize_hex_color( $input[ $key ] ) : '';
			$clean[ $key ] = $value ? $value : $defaults[ $key ];
		}

		return $clean;
	}
}
