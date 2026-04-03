<?php
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'wp-orphanage-extended' ) );
	}


	// Update options.
	if ( isset( $_POST['action'] ) && $_POST['action'] === 'update' ) {
		// Check nonce.
		check_admin_referer( 'wporphanageex-settings' );

		update_option( 'wporphanageex_role', $_POST['wporphanageex_role'] );
		if ( isset( $_POST['wporphanageex_prefixes'] ) && is_array( $_POST['wporphanageex_prefixes'] ) ) {
			$prefixes = array();
			foreach ( $_POST['wporphanageex_prefixes'] as $prefix ) {
				if ( ! empty( $prefix ) ) {
					$prefixes[] = wp_kses_data( $prefix );
				}
			}

			update_option( 'wporphanageex_prefixes', $prefixes );
		}

		echo '<div class="updated"><p><strong>' . __( 'Settings saved', 'wp-orphanage-extended' ) . '</strong></p></div>';
	}

$roles = wporphanageex_get_roles();
$wp_orphanageex_role = get_option( 'wporphanageex_role' );
$prefixes = get_option( 'wporphanageex_prefixes' );

?>
<div class="wrap">
	<h1><?php esc_html_e( 'WP Orphanage Extended', 'wp-orphanage-extended' ); ?></h1>

	<form method="post" action="" id="wporphanageex-settings">
		<input type="hidden" name="action" value="update" />
		<?php wp_nonce_field( 'wporphanageex-settings' ); ?>

		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="wporphanageex_role"><?php esc_html_e( 'Choose Default Role:', 'wp-orphanage-extended' ); ?></label></th>
				<td>
					<select name="wporphanageex_role" id="wporphanageex_role">
						<?php if ( $roles ) : ?>
							<?php foreach ( $roles as $role => $value ) : ?>
								<option value="<?php echo esc_attr( $role ); ?>" <?php selected( $wp_orphanageex_role, $role ); ?>><?php echo esc_html( ucfirst( $role ) ); ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select><br />
					<small><?php esc_html_e( 'Choose the default role orphan users should be promoted to (if no role to copy from other table was found).', 'wp-orphanage-extended' ); ?></small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="wporphanageex_prefixes"><?php esc_html_e( 'Add WP Prefixes:', 'wp-orphanage-extended' ); ?></label></th>
				<td>
					<?php if ( $prefixes ) : ?>
						<?php $i = 1; ?>
						<?php foreach ( $prefixes as $prefix ) : ?>
							<?php esc_html_e( 'Prefix', 'wp-orphanage-extended' ); ?> <?php echo $i; ?>: <input name="wporphanageex_prefixes[]" id="wporphanageex_prefixes_<?php echo esc_attr( $i ); ?>" class="regular-text" type="text" value="<?php echo esc_attr( $prefix ); ?>" /><br />
							<?php $i++; ?>
						<?php endforeach; ?>
					<?php endif; ?>
					<br /><?php esc_html_e( 'Add new:', 'wp-orphanage-extended' ); ?> <input name="wporphanageex_prefixes[]" id="wporphanageex_prefixes" class="regular-text" type="text" value="" /><br />
					<small><?php esc_html_e( 'Add prefixes of all WP installs where to search for user role. To remove field, leave it empty. Default WP prefix is <code>wp_</code> ', 'wp-orphanage-extended' ); ?></small>
				</td>
			</tr>
		</table>

		<p class="submit">
			<input type="submit" name="submit" value="<?php esc_html_e( 'Save Changes', 'wp-orphanage-extended' ); ?>" class="button-primary" />
		</p>

	</form>
</div>
