<?php
	if ( ! current_user_can( 'manage_options' ) )
		wp_die( __( 'You do not have sufficient permissions to access this page.', WPOEX_TD ) );


	// Update options
	if ( isset( $_POST['options_update'] ) ) {
		update_option('wporphanageex_role', $_POST['wporphanageex_role']);
		if ( isset( $_POST['wporphanageex_prefixes'] ) && is_array( $_POST['wporphanageex_prefixes'] ) ) {
			$prefixes = array();
			foreach ( $_POST['wporphanageex_prefixes'] as $prefix ) {
				if ( ! empty( $prefix ) )
					$prefixes[] = $prefix;
			}

			update_option( 'wporphanageex_prefixes', $prefixes );
		}

		echo '<div class="updated"><p><strong>' . __( 'Settings saved', WPOEX_TD ) . '</strong></p></div>';
	}

$roles = wporphanageex_get_roles();
$wp_orphanageex_role = get_option('wporphanageex_role');
$prefixes = get_option('wporphanageex_prefixes');

?>
<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php _e( 'WP Orphanage Extended', WPOEX_TD ); ?></h2>

	<form method="post" action="" id="wporphanageex-settings">
		<input type="hidden" name="action" value="update" />

		<table class="form-table">
			<tr valign="top">
				<th scope="row"><label for="wporphanageex_role"><?php _e( 'Choose Default Role:', WPOEX_TD ); ?></label></th>
				<td>
					<select name="wporphanageex_role" id="wporphanageex_role">
						<?php if($roles): ?>
							<?php foreach($roles as $role => $value): ?>
								<option value="<?php echo $role; ?>" <?php if ($role == $wp_orphanageex_role) { echo 'selected="selected"'; } ?>><?php echo ucfirst($role); ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select><br />
					<small><?php _e( 'Choose the default role orphan users should be promoted to (if no role to copy from other table was found).', WPOEX_TD ); ?></small>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="wporphanageex_prefixes"><?php _e( 'Add WP Prefixes:', WPOEX_TD ); ?></label></th>
				<td>
					<?php if($prefixes): ?>
						<?php $i = 1; ?>
						<?php foreach($prefixes as $prefix): ?>
							<?php _e( 'Prefix', WPOEX_TD ); ?> <?php echo $i; ?>: <input name="wporphanageex_prefixes[]" id="wporphanageex_prefixes_<?php echo $i; ?>" value="<?php echo $prefix; ?>" /><br />
							<?php $i++; ?>
						<?php endforeach; ?>
					<?php endif; ?>
					<br /><?php _e( 'Add new:', WPOEX_TD ); ?> <input name="wporphanageex_prefixes[]" id="wporphanageex_prefixes" value="" /><br />
					<small><?php _e( 'Add prefixes of all WP installs where to search for user role. To remove field, leave it empty. Default WP prefix is <code>wp_</code> ', WPOEX_TD ); ?></small>
				</td>
			</tr>
		</table>

		<p class="submit">
			<input type="submit" name="submit" value="<?php _e( 'Save Changes', WPOEX_TD ); ?>" class="button-primary" />
		</p>

	</form>
</div>
