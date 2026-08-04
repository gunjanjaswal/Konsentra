<?php
/**
 * Settings page markup.
 *
 * @package Konsentra
 *
 * @var array $settings   Current settings.
 * @var array $categories Category definitions.
 */

defined( 'ABSPATH' ) || exit;

$konsentra_opt = KONSENTRA_OPTION;
?>
<div class="wrap konsentra-admin">
	<h1><?php esc_html_e( 'Konsentra', 'konsentra' ); ?></h1>
	<p class="cp-tagline"><?php esc_html_e( 'A privacy-first cookie consent banner for your WordPress site.', 'konsentra' ); ?></p>

	<form method="post" action="options.php">
		<?php settings_fields( 'konsentra_group' ); ?>

		<div class="cp-admin-grid">
			<div class="cp-admin-main">

				<!-- General -->
				<div class="cp-card">
					<h2><?php esc_html_e( 'General', 'konsentra' ); ?></h2>

					<table class="form-table" role="presentation">
						<tr>
							<th scope="row"><?php esc_html_e( 'Enable banner', 'konsentra' ); ?></th>
							<td>
								<label>
									<input type="checkbox" name="<?php echo esc_attr( $konsentra_opt ); ?>[enabled]" value="1" <?php checked( $settings['enabled'] ); ?> />
									<?php esc_html_e( 'Show the consent banner to visitors', 'konsentra' ); ?>
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="cp-position"><?php esc_html_e( 'Position', 'konsentra' ); ?></label></th>
							<td>
								<select id="cp-position" name="<?php echo esc_attr( $konsentra_opt ); ?>[position]">
									<?php
									$konsentra_positions = array(
										'bottom'       => __( 'Bottom (full width)', 'konsentra' ),
										'top'          => __( 'Top (full width)', 'konsentra' ),
										'bottom-left'  => __( 'Bottom left', 'konsentra' ),
										'bottom-right' => __( 'Bottom right', 'konsentra' ),
									);
									foreach ( $konsentra_positions as $konsentra_value => $konsentra_label ) {
										printf(
											'<option value="%1$s" %2$s>%3$s</option>',
											esc_attr( $konsentra_value ),
											selected( $settings['position'], $konsentra_value, false ),
											esc_html( $konsentra_label )
										);
									}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="cp-layout"><?php esc_html_e( 'Layout', 'konsentra' ); ?></label></th>
							<td>
								<select id="cp-layout" name="<?php echo esc_attr( $konsentra_opt ); ?>[layout]">
									<option value="bar" <?php selected( $settings['layout'], 'bar' ); ?>><?php esc_html_e( 'Bar', 'konsentra' ); ?></option>
									<option value="box" <?php selected( $settings['layout'], 'box' ); ?>><?php esc_html_e( 'Box', 'konsentra' ); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="cp-expiry"><?php esc_html_e( 'Ask again after', 'konsentra' ); ?></label></th>
							<td>
								<input type="number" id="cp-expiry" min="1" max="3650" name="<?php echo esc_attr( $konsentra_opt ); ?>[consent_expiry]" value="<?php echo esc_attr( $settings['consent_expiry'] ); ?>" class="small-text" />
								<?php esc_html_e( 'days', 'konsentra' ); ?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Buttons', 'konsentra' ); ?></th>
							<td>
								<label class="cp-block">
									<input type="checkbox" name="<?php echo esc_attr( $konsentra_opt ); ?>[show_reject]" value="1" <?php checked( $settings['show_reject'] ); ?> />
									<?php esc_html_e( 'Show a "Reject all" button', 'konsentra' ); ?>
								</label>
								<label class="cp-block">
									<input type="checkbox" name="<?php echo esc_attr( $konsentra_opt ); ?>[show_settings]" value="1" <?php checked( $settings['show_settings'] ); ?> />
									<?php esc_html_e( 'Show a "Manage preferences" button', 'konsentra' ); ?>
								</label>
							</td>
						</tr>
					</table>
				</div>

				<!-- Content -->
				<div class="cp-card">
					<h2><?php esc_html_e( 'Content', 'konsentra' ); ?></h2>

					<table class="form-table" role="presentation">
						<tr>
							<th scope="row"><label for="cp-heading"><?php esc_html_e( 'Heading', 'konsentra' ); ?></label></th>
							<td><input type="text" id="cp-heading" class="regular-text" name="<?php echo esc_attr( $konsentra_opt ); ?>[heading]" value="<?php echo esc_attr( $settings['heading'] ); ?>" /></td>
						</tr>
						<tr>
							<th scope="row"><label for="cp-message"><?php esc_html_e( 'Message', 'konsentra' ); ?></label></th>
							<td>
								<textarea id="cp-message" class="large-text" rows="4" name="<?php echo esc_attr( $konsentra_opt ); ?>[message]"><?php echo esc_textarea( $settings['message'] ); ?></textarea>
								<p class="description"><?php esc_html_e( 'Basic HTML links are allowed.', 'konsentra' ); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for="cp-privacy-page"><?php esc_html_e( 'Privacy Policy page', 'konsentra' ); ?></label></th>
							<td>
								<?php
								wp_dropdown_pages(
									array(
										'name'              => esc_attr( $konsentra_opt ) . '[privacy_page]',
										'id'                => 'cp-privacy-page',
										'selected'          => (int) $settings['privacy_page'],
										'show_option_none'  => esc_html__( '— None —', 'konsentra' ),
										'option_none_value' => 0,
									)
								);
								?>
							</td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Button labels', 'konsentra' ); ?></th>
							<td>
								<p><label class="cp-label-inline"><?php esc_html_e( 'Accept', 'konsentra' ); ?></label>
									<input type="text" name="<?php echo esc_attr( $konsentra_opt ); ?>[accept_label]" value="<?php echo esc_attr( $settings['accept_label'] ); ?>" /></p>
								<p><label class="cp-label-inline"><?php esc_html_e( 'Reject', 'konsentra' ); ?></label>
									<input type="text" name="<?php echo esc_attr( $konsentra_opt ); ?>[reject_label]" value="<?php echo esc_attr( $settings['reject_label'] ); ?>" /></p>
								<p><label class="cp-label-inline"><?php esc_html_e( 'Preferences', 'konsentra' ); ?></label>
									<input type="text" name="<?php echo esc_attr( $konsentra_opt ); ?>[settings_label]" value="<?php echo esc_attr( $settings['settings_label'] ); ?>" /></p>
								<p><label class="cp-label-inline"><?php esc_html_e( 'Save', 'konsentra' ); ?></label>
									<input type="text" name="<?php echo esc_attr( $konsentra_opt ); ?>[save_label]" value="<?php echo esc_attr( $settings['save_label'] ); ?>" /></p>
								<p><label class="cp-label-inline"><?php esc_html_e( 'Privacy link', 'konsentra' ); ?></label>
									<input type="text" name="<?php echo esc_attr( $konsentra_opt ); ?>[privacy_label]" value="<?php echo esc_attr( $settings['privacy_label'] ); ?>" /></p>
							</td>
						</tr>
					</table>
				</div>

				<!-- Categories -->
				<div class="cp-card">
					<h2><?php esc_html_e( 'Cookie categories', 'konsentra' ); ?></h2>
					<p class="description"><?php esc_html_e( 'Strictly necessary cookies are always active. Choose which optional categories visitors can control.', 'konsentra' ); ?></p>

					<table class="form-table" role="presentation">
						<tr>
							<th scope="row"><?php echo esc_html( $categories['functional']['label'] ); ?></th>
							<td><label><input type="checkbox" name="<?php echo esc_attr( $konsentra_opt ); ?>[cat_functional]" value="1" <?php checked( $settings['cat_functional'] ); ?> /> <?php esc_html_e( 'Offer this category', 'konsentra' ); ?></label></td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html( $categories['analytics']['label'] ); ?></th>
							<td><label><input type="checkbox" name="<?php echo esc_attr( $konsentra_opt ); ?>[cat_analytics]" value="1" <?php checked( $settings['cat_analytics'] ); ?> /> <?php esc_html_e( 'Offer this category', 'konsentra' ); ?></label></td>
						</tr>
						<tr>
							<th scope="row"><?php echo esc_html( $categories['marketing']['label'] ); ?></th>
							<td><label><input type="checkbox" name="<?php echo esc_attr( $konsentra_opt ); ?>[cat_marketing]" value="1" <?php checked( $settings['cat_marketing'] ); ?> /> <?php esc_html_e( 'Offer this category', 'konsentra' ); ?></label></td>
						</tr>
					</table>
				</div>

				<!-- Script blocking -->
				<div class="cp-card">
					<h2><?php esc_html_e( 'Script blocking', 'konsentra' ); ?></h2>
					<table class="form-table" role="presentation">
						<tr>
							<th scope="row"><?php esc_html_e( 'Block until consent', 'konsentra' ); ?></th>
							<td>
								<label>
									<input type="checkbox" name="<?php echo esc_attr( $konsentra_opt ); ?>[block_scripts]" value="1" <?php checked( $settings['block_scripts'] ); ?> />
									<?php esc_html_e( 'Hold back tagged scripts until the visitor consents', 'konsentra' ); ?>
								</label>
								<p class="description">
									<?php esc_html_e( 'Give a script a type of "text/plain" and a data-cp-category attribute, for example:', 'konsentra' ); ?>
								</p>
								<code class="cp-code">&lt;script type="text/plain" data-cp-category="analytics" src="..."&gt;&lt;/script&gt;</code>
							</td>
						</tr>
					</table>
				</div>

				<!-- Privacy -->
				<div class="cp-card">
					<h2><?php esc_html_e( 'Consent records', 'konsentra' ); ?></h2>
					<table class="form-table" role="presentation">
						<tr>
							<th scope="row"><?php esc_html_e( 'Log consent', 'konsentra' ); ?></th>
							<td>
								<label>
									<input type="checkbox" name="<?php echo esc_attr( $konsentra_opt ); ?>[log_consent]" value="1" <?php checked( $settings['log_consent'] ); ?> />
									<?php esc_html_e( 'Keep an anonymised record of each consent decision', 'konsentra' ); ?>
								</label>
								<p class="description"><?php esc_html_e( 'Stores a hashed IP, a timestamp and the categories agreed to. No raw IP addresses are saved.', 'konsentra' ); ?></p>
							</td>
						</tr>
					</table>
				</div>

			</div>

			<!-- Sidebar: appearance -->
			<div class="cp-admin-side">
				<div class="cp-card">
					<h2><?php esc_html_e( 'Appearance', 'konsentra' ); ?></h2>
					<p>
						<label class="cp-block-label"><?php esc_html_e( 'Background', 'konsentra' ); ?></label>
						<input type="text" class="cp-color" name="<?php echo esc_attr( $konsentra_opt ); ?>[bg_color]" value="<?php echo esc_attr( $settings['bg_color'] ); ?>" />
					</p>
					<p>
						<label class="cp-block-label"><?php esc_html_e( 'Text', 'konsentra' ); ?></label>
						<input type="text" class="cp-color" name="<?php echo esc_attr( $konsentra_opt ); ?>[text_color]" value="<?php echo esc_attr( $settings['text_color'] ); ?>" />
					</p>
					<p>
						<label class="cp-block-label"><?php esc_html_e( 'Accent / buttons', 'konsentra' ); ?></label>
						<input type="text" class="cp-color" name="<?php echo esc_attr( $konsentra_opt ); ?>[accent_color]" value="<?php echo esc_attr( $settings['accent_color'] ); ?>" />
					</p>
					<p>
						<label class="cp-block-label"><?php esc_html_e( 'Button text', 'konsentra' ); ?></label>
						<input type="text" class="cp-color" name="<?php echo esc_attr( $konsentra_opt ); ?>[button_text_color]" value="<?php echo esc_attr( $settings['button_text_color'] ); ?>" />
					</p>
				</div>

				<div class="cp-card cp-help">
					<h2><?php esc_html_e( 'Reopen link', 'konsentra' ); ?></h2>
					<p><?php esc_html_e( 'Let visitors change their mind. Add this shortcode to your footer or privacy page:', 'konsentra' ); ?></p>
					<code class="cp-code">[konsentra_settings]</code>
				</div>

				<div class="cp-card cp-support">
					<h2><?php esc_html_e( 'Support &amp; author', 'konsentra' ); ?></h2>
					<p class="cp-support-by">
						<?php
						printf(
							/* translators: %s: author name. */
							esc_html__( 'Built by %s.', 'konsentra' ),
							'<strong>Gunjan Jaswal</strong>'
						);
						?>
					</p>
					<p><?php esc_html_e( 'Enjoying the plugin? A coffee keeps it going.', 'konsentra' ); ?></p>
					<ul class="cp-links">
						<li><a href="https://ko-fi.com/gunjanjaswal" target="_blank" rel="noopener noreferrer">☕ <?php esc_html_e( 'Buy me a coffee', 'konsentra' ); ?></a></li>
						<li><a href="https://www.gunjanjaswal.me" target="_blank" rel="noopener noreferrer">🌐 <?php esc_html_e( 'Website', 'konsentra' ); ?></a></li>
						<li><a href="mailto:hello@gunjanjaswal.me">✉️ hello@gunjanjaswal.me</a></li>
					</ul>
				</div>
			</div>
		</div>

		<?php submit_button( __( 'Save changes', 'konsentra' ) ); ?>
	</form>

	<p class="cp-footer-credit">
		<?php
		printf(
			/* translators: 1: author link, 2: Ko-fi link. */
			esc_html__( 'Konsentra by %1$s. If it helps, you can %2$s.', 'konsentra' ),
			'<a href="https://www.gunjanjaswal.me" target="_blank" rel="noopener noreferrer">Gunjan Jaswal</a>',
			'<a href="https://ko-fi.com/gunjanjaswal" target="_blank" rel="noopener noreferrer">' . esc_html__( 'support the work on Ko-fi', 'konsentra' ) . '</a>'
		);
		?>
	</p>
</div>
