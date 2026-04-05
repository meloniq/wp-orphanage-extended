<?php
/**
 * Core functionality.
 *
 * @package WPOrphanage\Extended
 */

namespace WPOrphanage\Extended;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WP_User;

/**
 * Core class.
 */
class Core {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_login', array( $this, 'adopt_this_orphan' ) );
		add_action( 'load-users.php', array( $this, 'adopt_all_orphans' ) );
	}

	/**
	 * Adopts orphaned user.
	 *
	 * @param string $login User login.
	 *
	 * @return void
	 */
	public function adopt_this_orphan( $login ): void {
		$user = get_user_by( 'login', $login );

		if ( ! current_user_can( 'read' ) ) {
			$user_up = new WP_User( $user->ID );
			$user_up->set_role( Utils::search_user_role( $user->ID ) );
		}
	}

	/**
	 * Adopts all orphaned users.
	 *
	 * @return void
	 */
	public function adopt_all_orphans(): void {
		foreach ( Utils::get_all_users() as $user_id ) {
			$user = new WP_User( $user_id );
			if ( ! user_can( $user_id, 'read' ) ) {
				$user->set_role( Utils::search_user_role( $user_id ) );
			}
		}
	}
}
