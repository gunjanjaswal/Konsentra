<?php
/**
 * Settings page markup.
 *
 * @package ConsentBannerNest
 *
 * @var array $settings   Current settings.
 * @var array $categories Category definitions.
 */

defined( 'ABSPATH' ) || exit;

$opt = CONSENT_BANNER_NEST_OPTION;
?>
<div class="wrap consent-banner-nest-admin">
	<h1><?php esc_html_e( 'Consent Banner Nest', 'consent-banner-nest' ); ?></h1>
	<p class="cp-tagline"><?php esc_html_e( 'A privacy-first cookie consent banner for your WordPress site.', 'consent-banner-nest' ); ?></p>

	<form method="post" action="options.php">
		<?php settings_fields( 'consent_banner_nest_group' ); ?>

		<div class="cp-admin-grid">
			<div class="cp-admin-main">

				<!-- General -->
				<div class="cp-card">
					<h2><?php esc_html_e( 'General', 'consent-banner-nest' ); ?></h2>

					<table class="form-table" role="presentation">
						<tr>
							<th scope="row"><?php esc_html_e( 'Enable banner', 'consent-banner-nest' ); ?></th>
							<td>
								<label>
									<input type="checkbox" name="<?php echo esc_attr( $opt ); ?>[enabled]" value="1" <?php checked( $settings['enabled'] ); ?> />
									<?php esc_html_e( 'Show the consent banner to visitors', 'consent-banner-nest' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="cp-position"><?php esc_html_e( 'Position', 'consent-banner-nest' ); ?></label></th>
							<td>
								<select id="cp-position" name="<?php echo esc_attr( $opt ); ?>[position]">
									<?php
									$positions = array(
										'bottom'       => __( 'Bottom (full width)', 'consent-banner-nest' ),
										'top'          => __( 'Top (full width)', 'consent-banner-nest' ),
										'bottom-left'  => __( 'Bottom left', 'consent-banner-nest' ),
										'bottom-right' => __( 'Bottom right', 'consent-banner-nest' ),
									);
									foreach ( $positions as $value => $label ) {
										printf(
											'<option value="%1$s" %2$s>%3$s</option>',
											esc_attr( $value ),
											selected( $settings['position'], $value, false ),
											esc_html( $label )
										);
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="cp-layout"><?php esc_html_e( 'Layout', 'consent-banner-nest' ); ?></label></th>
							<td>
								<select id="cp-layout" name="<?php echo esc_attr( $opt ); ?>[layout]">
									<option value="bar" <?php selected( $settings['layout'], 'bar' ); ?>><?php esc_html_e( 'Bar', 'consent-banner-nest' ); ?></option>
									<option value="box" <?php selected( $settings['layout'], 'box' ); ?>><?php esc_html_e( 'Box', 'consent-banner-nest' ); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="cp-expiry"><?php esc_html_e( 'Ask again after', 'consent-banner-nest' ); ?></label></th>
							<td>
								<input type="number" id="cp-expiry" min="1" max="3650" name="<?php echo esc_attr( $opt ); ?>[consent_expiry]" value="<?php echo esc_attr( $settings['consent_expiry'] ); ?>" class="small-text" />
								<?php esc_html_e( 'days', 'consent-banner-nest' ); ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Buttons', 'consent-banner-nest' ); ?></th>
							<td>
								<label class="cp-block">
									<input type="checkbox" name="<?php echo esc_attr( $opt ); ?>[show_reject]" value="1" <?php checked( $settings['show_reject'] ); ?> />
									<?php esc_html_e( 'Show a "Reject all" button', 'consent-banner-nest' ); ?>
								</label>
								<label class="cp-block">
									<input type="checkbox" name="<?php echo esc_attr( $opt ); ?>[show_settings]" value="1" <?php checked( $settings['show_settings'] ); ?> />
									<?php esc_html_e( 'Show a "Manage preferences" button', 'consent-banner-nest' ); ?>
								</label>
							</td>
						</tr>
					</table>
				</div>

				<!-- Content -->
				<div class="cp-card">
					<h2><?php esc_html_e( 'Content', 'consent-banner-nest' ); ?></h2>

					<table class="form-table" role="presentation">
						<tr>
							<th scope="row"><label for="cp-heading"><?php esc_html_e( 'Heading', 'consent-banner-nest' ); ?></label></th>
							<td><input type="text" id="cp-heading" class="regular-text" name="<?php echo esc_attr( $opt ); ?>[heading]" value="<?php echo esc_attr( $settings['heading'] ); ?>" /></td>
						</tr>
						<tr>
							<th scope="row"><label for="cp-message"><?php esc_html_e( 'Message', 'consent-banner-nest' ); ?></label></th>
							<td>
								<textarea id="cp-message" class="large-text" rows="4" name="<?php echo esc_attr( $opt ); ?>[message]"><?php echo esc_textarea( $settings['message'] ); ?></textarea>
								<p class="description"><?php esc_html_e( 'Basic HTML links are allowed.', 'consent-banner-nest' ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="cp-privacy-page"><?php esc_html_e( 'Privacy Policy page', 'consent-banner-nest' ); ?></label></th>
							<td>
								<?php
								wp_dropdown_pages(
									array(
										'name'              => esc_attr( $opt ) . '[privacy_page]',
										'id'                => 'cp-privacy-page',
										'selected'          => (int) $settings['privacy_page'],
										'show_option_none'  => __( '— None —', 'consent-banner-nest' ),
										'option_none_value' => 0,
									)
								);
								?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Button labels', 'consent-banner-nest' ); ?></th>
							<td>
								<p><label class="cp-label-inline"><?php esc_html_e( 'Accept', 'consent-banner-nest' ); ?></label>
									<input type="text" name="<?php echo esc_attr( $opt ); ?>[accept_label]" value="<?php echo esc_attr( $settings['accept_label'] ); ?>" /></p>
								<p><label class="cp-label-inline"><?php esc_html_e( 'Reject', 'consent-banner-nest' ); ?></label>
									<input type="text" name="<?php echo esc_attr( $opt ); ?>[reject_label]" value="<?php echo esc_attr( $settings['reject_label'] ); ?>" /></p>
								<p><label class="cp-label-inline"><?php esc_html_e( 'Preferences', 'consent-banner-nest' ); ?></label>
									<input type="text" name="<?php echo esc_attr( $opt ); ?>[settings_label]" value="<?php echo esc_attr( $settings['settings_label'] ); ?>" /></p>
								<p><label class="cp-label-inline"><?php esc_html_e( 'Save', 'consent-banner-nest' ); ?></label>
									<input type="text" name="<?php echo esc_attr( $opt ); ?>[save_label]" value="<?php echo esc_attr( $settings['save_label'] ); ?>" /></p>
								<p><label class="cp-label-inline"><?php esc_html_e( 'Privacy link', 'consent-banner-nest' ); ?></label>
									<input type="text" name="<?php echo esc_attr( $opt ); ?>[privacy_label]" value="<?php echo esc_attr( $settings['privacy_label'] ); ?>" /></p>
							</td>
						</tr>
					</table>
				</div>

				<!-- Categories -->
				<div class="cp-card">
					<h2><?php esc_html_e( 'Cookie categories', 'consent-banner-nest' ); ?></h2>
					<p class="description"><?php esc_html_e( 'Strictly necessary cookies are always active. Choose which optional categories visitors can control.', 'consent-banner-nest' ); ?></p>

					<table class="form-table" role="presentation">
						<tr>
							<th scope="row"><?php echo esc_html( $categories['functional']['label'] ); ?></th>
							<td><label><input type="checkbox" name="<?php echo esc_attr( $opt ); ?>[cat_functional]" value="1" <?php checked( $settings['cat_functional'] ); ?> /> <?php esc_html_e( 'Offer this category', 'consent-banner-nest' ); ?></label></td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html( $categories['analytics']['label'] ); ?></th>
							<td><label><input type="checkbox" name="<?php echo esc_attr( $opt ); ?>[cat_analytics]" value="1" <?php checked( $settings['cat_analytics'] ); ?> /> <?php esc_html_e( 'Offer this category', 'consent-banner-nest' ); ?></label></td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html( $categories['marketing']['label'] ); ?></th>
							<td><label><input type="checkbox" name="<?php echo esc_attr( $opt ); ?>[cat_marketing]" value="1" <?php checked( $settings['cat_marketing'] ); ?> /> <?php esc_html_e( 'Offer this category', 'consent-banner-nest' ); ?></label></td>
						</tr>
					</table>
				</div>

				<!-- Script blocking -->
				<div class="cp-card">
					<h2><?php esc_html_e( 'Script blocking', 'consent-banner-nest' ); ?></h2>
					<table class="form-table" role="presentation">
						<tr>
							<th scope="row"><?php esc_html_e( 'Block until consent', 'consent-banner-nest' ); ?></th>
							<td>
								<label>
									<input type="checkbox" name="<?php echo esc_attr( $opt ); ?>[block_scripts]" value="1" <?php checked( $settings['block_scripts'] ); ?> />
									<?php esc_html_e( 'Hold back tagged scripts until the visitor consents', 'consent-banner-nest' ); ?>
								</label>
								<p class="description">
									<?php esc_html_e( 'Give a script a type of "text/plain" and a data-cp-category attribute, for example:', 'consent-banner-nest' ); ?>
								</p>
								<code class="cp-code">&lt;script type="text/plain" data-cp-category="analytics" src="..."&gt;&lt;/script&gt;</code>
							</td>
						</tr>
					</table>
				</div>

				<!-- Privacy -->
				<div class="cp-card">
					<h2><?php esc_html_e( 'Consent records', 'consent-banner-nest' ); ?></h2>
					<table class="form-table" role="presentation">
						<tr>
							<th scope="row"><?php esc_html_e( 'Log consent', 'consent-banner-nest' ); ?></th>
							<td>
								<label>
									<input type="checkbox" name="<?php echo esc_attr( $opt ); ?>[log_consent]" value="1" <?php checked( $settings['log_consent'] ); ?> />
									<?php esc_html_e( 'Keep an anonymised record of each consent decision', 'consent-banner-nest' ); ?>
								</label>
								<p class="description"><?php esc_html_e( 'Stores a hashed IP, a timestamp and the categories agreed to. No raw IP addresses are saved.', 'consent-banner-nest' ); ?></p>
							</td>
						</tr>
					</table>
				</div>

			</div>

			<!-- Sidebar: appearance -->
			<div class="cp-admin-side">
				<div class="cp-card">
					<h2><?php esc_html_e( 'Appearance', 'consent-banner-nest' ); ?></h2>
					<p>
						<label class="cp-block-label"><?php esc_html_e( 'Background', 'consent-banner-nest' ); ?></label>
						<input type="text" class="cp-color" name="<?php echo esc_attr( $opt ); ?>[bg_color]" value="<?php echo esc_attr( $settings['bg_color'] ); ?>" />
					</p>
					<p>
						<label class="cp-block-label"><?php esc_html_e( 'Text', 'consent-banner-nest' ); ?></label>
						<input type="text" class="cp-color" name="<?php echo esc_attr( $opt ); ?>[text_color]" value="<?php echo esc_attr( $settings['text_color'] ); ?>" />
					</p>
					<p>
						<label class="cp-block-label"><?php esc_html_e( 'Accent / buttons', 'consent-banner-nest' ); ?></label>
						<input type="text" class="cp-color" name="<?php echo esc_attr( $opt ); ?>[accent_color]" value="<?php echo esc_attr( $settings['accent_color'] ); ?>" />
					</p>
					<p>
						<label class="cp-block-label"><?php esc_html_e( 'Button text', 'consent-banner-nest' ); ?></label>
						<input type="text" class="cp-color" name="<?php echo esc_attr( $opt ); ?>[button_text_color]" value="<?php echo esc_attr( $settings['button_text_color'] ); ?>" />
					</p>
				</div>

				<div class="cp-card cp-help">
					<h2><?php esc_html_e( 'Reopen link', 'consent-banner-nest' ); ?></h2>
					<p><?php esc_html_e( 'Let visitors change their mind. Add this shortcode to your footer or privacy page:', 'consent-banner-nest' ); ?></p>
					<code class="cp-code">[consent_banner_nest_settings]</code>
				</div>

				<div class="cp-card cp-support">
					<h2><?php esc_html_e( 'Support &amp; author', 'consent-banner-nest' ); ?></h2>
					<p class="cp-support-by">
						<?php
						printf(
							/* translators: %s: author name. */
							esc_html__( 'Built by %s.', 'consent-banner-nest' ),
							'<strong>Gunjan Jaswal</strong>'
						);
						?>
					</p>
					<p><?php esc_html_e( 'Enjoying the plugin? A coffee keeps it going.', 'consent-banner-nest' ); ?></p>
					<ul class="cp-links">
						<li><a href="https://ko-fi.com/gunjanjaswal" target="_blank" rel="noopener noreferrer">☕ <?php esc_html_e( 'Buy me a coffee', 'consent-banner-nest' ); ?></a></li>
						<li><a href="https://www.gunjanjaswal.me" target="_blank" rel="noopener noreferrer">🌐 <?php esc_html_e( 'Website', 'consent-banner-nest' ); ?></a></li>
						<li><a href="mailto:hello@gunjanjaswal.me">✉️ hello@gunjanjaswal.me</a></li>
					</ul>
				</div>
			</div>
		</div>

		<?php submit_button( __( 'Save changes', 'consent-banner-nest' ) ); ?>
	</form>

	<p class="cp-footer-credit">
		<?php
		printf(
			/* translators: 1: author link, 2: Ko-fi link. */
			esc_html__( 'Consent Banner Nest by %1$s. If it helps, you can %2$s.', 'consent-banner-nest' ),
			'<a href="https://www.gunjanjaswal.me" target="_blank" rel="noopener noreferrer">Gunjan Jaswal</a>',
			'<a href="https://ko-fi.com/gunjanjaswal" target="_blank" rel="noopener noreferrer">' . esc_html__( 'support the work on Ko-fi', 'consent-banner-nest' ) . '</a>'
		);
		?>
	</p>
</div>
