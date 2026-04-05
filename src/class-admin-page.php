<?php
/**
 * Admin Page.
 *
 * @package WPOrphanage\Extended
 */

namespace WPOrphanage\Extended;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Page class.
 */
class Admin_Page {

	/**
	 * Admin page URL.
	 *
	 * @var string
	 */
	public static $admin_page_url = 'options-general.php?page=wp-orphanage-extended';

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu_page' ), 10 );
		add_action( 'admin_post_wporphanageex_settings', array( $this, 'handle_settings_save' ) );
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Add menu page.
	 *
	 * @return void
	 */
	public function add_menu_page(): void {
		add_options_page(
			__( 'WP Orphanage Extended', 'wp-orphanage-extended' ),
			__( 'WP Orphanage Extended', 'wp-orphanage-extended' ),
			'manage_options',
			'wp-orphanage-extended',
			array( $this, 'render_page' )
		);
	}

	/**
	 * Render page.
	 *
	 * @return void
	 */
	public function render_page(): void {
		$oex_roles           = Utils::get_roles();
		$wp_orphanageex_role = get_option( 'wporphanageex_role' );
		$prefixes            = get_option( 'wporphanageex_prefixes' );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'WP Orphanage Extended', 'wp-orphanage-extended' ); ?></h1>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="wporphanageex-settings">
				<input type="hidden" name="action" value="wporphanageex_settings">
				<?php wp_nonce_field( 'wporphanageex-settings' ); ?>

				<table class="form-table">
					<tr valign="top">
						<th scope="row"><label for="wporphanageex_role"><?php esc_html_e( 'Choose Default Role:', 'wp-orphanage-extended' ); ?></label></th>
						<td>
							<select name="wporphanageex_role" id="wporphanageex_role">
								<?php if ( $oex_roles ) : ?>
									<?php foreach ( $oex_roles as $oex_role => $value ) : ?>
										<option value="<?php echo esc_attr( $oex_role ); ?>" <?php selected( $wp_orphanageex_role, $oex_role ); ?>><?php echo esc_html( ucfirst( $oex_role ) ); ?></option>
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
									<?php esc_html_e( 'Prefix', 'wp-orphanage-extended' ); ?> <?php echo esc_attr( $i ); ?>: <input name="wporphanageex_prefixes[]" id="wporphanageex_prefixes_<?php echo esc_attr( $i ); ?>" class="regular-text" type="text" value="<?php echo esc_attr( $prefix ); ?>" /><br />
									<?php ++$i; ?>
								<?php endforeach; ?>
							<?php endif; ?>
							<br /><?php esc_html_e( 'Add new:', 'wp-orphanage-extended' ); ?> <input name="wporphanageex_prefixes[]" id="wporphanageex_prefixes" class="regular-text" type="text" value="" /><br />
							<small><?php esc_html_e( 'Add prefixes of all WP installs where to search for user role. To remove field, leave it empty.', 'wp-orphanage-extended' ); ?></small>
							<small><?php esc_html_e( 'Default WP prefix is:', 'wp-orphanage-extended' ); ?> <code>wp_</code> </small>
						</td>
					</tr>
				</table>

				<p class="submit">
					<input type="submit" name="submit" value="<?php esc_html_e( 'Save Changes', 'wp-orphanage-extended' ); ?>" class="button-primary" />
				</p>

			</form>
		</div>
		<?php
	}

	/**
	 * Handle settings save.
	 *
	 * @return void
	 */
	public function handle_settings_save(): void {
		// Verify nonce.
		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'wporphanageex-settings' ) ) {
			wp_die( esc_html__( 'Security check failed.', 'wp-orphanage-extended' ) );
		}

		// Check capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to perform this action.', 'wp-orphanage-extended' ) );
		}

		// Validate required fields.
		if ( ! isset( $_POST['wporphanageex_role'] ) || ! isset( $_POST['wporphanageex_prefixes'] ) ) {
			wp_safe_redirect( add_query_arg( 'wpoex_error', 'missing_fields', admin_url( self::$admin_page_url ) ) );
			exit;
		}

		// Validate prefixes field.
		if ( ! is_array( $_POST['wporphanageex_prefixes'] ) ) {
			wp_safe_redirect( add_query_arg( 'wpoex_error', 'invalid_prefixes', admin_url( self::$admin_page_url ) ) );
			exit;
		}

		// Save role.
		$posted_role = sanitize_text_field( wp_unslash( $_POST['wporphanageex_role'] ) );
		update_option( 'wporphanageex_role', $posted_role );

		// Save prefixes.
		$prefixes = array();
		foreach ( $_POST['wporphanageex_prefixes'] as $prefix ) { // phpcs:ignore
			if ( ! empty( $prefix ) ) {
				$prefixes[] = wp_kses_data( $prefix );
			}
		}
		update_option( 'wporphanageex_prefixes', $prefixes );

		// Redirect back with success message.
		wp_safe_redirect( add_query_arg( 'wpoex_success', 'settings_saved', admin_url( self::$admin_page_url ) ) );
		exit;
	}

	/**
	 * Display admin notices.
	 *
	 * @return void
	 */
	public function admin_notices(): void {
		$screen = get_current_screen();
		if ( ! $screen || 'settings_page_wp-orphanage-extended' !== $screen->id ) {
			return;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['wpoex_error'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$error = sanitize_text_field( wp_unslash( $_GET['wpoex_error'] ) );

			$messages = array(
				'missing_fields'   => __( 'Please fill in all required fields.', 'wp-orphanage-extended' ),
				'invalid_prefixes' => __( 'Invalid prefixes value.', 'wp-orphanage-extended' ),
				'default'          => __( 'An error occurred.', 'wp-orphanage-extended' ),
			);

			$message = $messages[ $error ] ?? $messages['default'];
			?>
			<div class="notice notice-error is-dismissible">
				<p><?php echo esc_html( $message ); ?></p>
			</div>
			<?php
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['wpoex_success'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$success = sanitize_text_field( wp_unslash( $_GET['wpoex_success'] ) );

			$messages = array(
				'settings_saved' => __( 'Settings saved', 'wp-orphanage-extended' ),
				'default'        => __( 'Action completed', 'wp-orphanage-extended' ),
			);

			$message = $messages[ $success ] ?? $messages['default'];
			?>
			<div class="notice notice-success is-dismissible">
				<p><?php echo esc_html( $message ); ?></p>
			</div>
			<?php
		}
	}
}
