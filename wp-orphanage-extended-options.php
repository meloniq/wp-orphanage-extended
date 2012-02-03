<?php
if ( !current_user_can( 'manage_options' ) ) {
			echo '<div class="wrap">' . __('You do not have sufficient permissions to access this page', 'wporphanageex') . '</div>';
			return false;
}

if ( !empty($_POST ) ) {
	update_option('wporphanageex_role', $_POST['wporphanageex_role']);
  if($_POST['wporphanageex_prefixes'] && is_array($_POST['wporphanageex_prefixes'])){
    $prefixes = array();
    foreach($_POST['wporphanageex_prefixes'] as $prefix)
      if($prefix != '')
        $prefixes[] = $prefix;
    
    update_option('wporphanageex_prefixes', $prefixes);
  }
?>
<div id="message" class="updated fade"><?php _e('Settings updated. ','wporphanageex'); ?></div>
<?php
}

$roles = wporphanageex_get_roles();
$wp_orphanageex_role = get_option('wporphanageex_role');
$prefixes = get_option('wporphanageex_prefixes');

?>
<div class="wrap">
	<?php if ( function_exists('screen_icon') ) screen_icon(); ?>
	<h2><?php _e('WP Orphanage Extended','wporphanageex'); ?></h2>

	<form method="post" action="" id="wporphanageex-settings">
		<input type="hidden" name="action" value="update" />

		<table class="form-table">

			<tr valign="top">
				<th scope="row"><label for="wporphanageex_role"><?php _e('Choose Default Role:', 'wporphanageex'); ?></label></th>
				<td>
    			<select name="wporphanageex_role" id="wporphanageex_role">
    				<?php if($roles): ?>
      				<?php foreach($roles as $role => $value): ?>
      					<option value="<?php echo $role; ?>" <?php if ($role == $wp_orphanageex_role) { echo 'selected="selected"'; } ?>><?php echo ucfirst($role); ?></option>
      				<?php endforeach; ?>
    				<?php endif; ?>
    			</select><br />
        	<small><?php _e('Choose the default role orphan users should be promoted to (if no role to copy from other table was found).', 'wporphanageex'); ?></small>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="wporphanageex_prefixes"><?php _e('Add WP Prefixes:', 'wporphanageex'); ?></label></th>
				<td>
  				<?php if($prefixes): ?>
            <?php $i = 1; ?>
    				<?php foreach($prefixes as $prefix): ?>
    					<?php _e('Prefix', 'wporphanageex'); ?> <?php echo $i; ?>: <input name="wporphanageex_prefixes[]" id="wporphanageex_prefixes_<?php echo $i; ?>" value="<?php echo $prefix; ?>" /><br />
              <?php $i++; ?>
    				<?php endforeach; ?>
  				<?php endif; ?>
					<br /><?php _e('Add new:', 'wporphanageex'); ?> <input name="wporphanageex_prefixes[]" id="wporphanageex_prefixes" value="" /><br />
        	<small><?php _e('Add prefixes of all WP installs where to search for user role. To remove field, leave it empty. Default WP prefix is <code>wp_</code> ', 'wporphanageex'); ?></small>
				</td>
			</tr>

		</table>

		<p class="submit">
			<input type="submit" name="submit" value="<?php _e('Save Changes', 'wporphanageex') ?>" class="button-primary" />
			<div class="alignright">Developed by <a href="http://blog.meloniq.net/" title="meloniq.net">meloniq.net</a></div>
		</p>

	</form>	                      
</div>