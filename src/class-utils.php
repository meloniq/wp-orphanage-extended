<?php
/**
 * Utilities.
 *
 * @package WPOrphanage\Extended
 */

namespace WPOrphanage\Extended;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Utils class.
 */
class Utils {

	/**
	 * Returns an array of user roles.
	 *
	 * @return array
	 */
	public static function get_roles(): array {
		global $wpdb;

		$option = $wpdb->prefix . 'user_roles';

		$roles = get_option( $option );

		return is_array( $roles ) ? $roles : array();
	}

	/**
	 * Returns an array of user IDs.
	 *
	 * @return array
	 */
	public static function get_all_users(): array {
		global $wpdb;

		$results = $wpdb->get_col( "SELECT ID FROM $wpdb->users" ); // phpcs:ignore

		return $results;
	}

	/**
	 * Searching other blogs and returns a user role, if not found, returns default one.
	 *
	 * @param int $user_id User ID to search for. If not set, current user will be used.
	 *
	 * @return string
	 */
	public static function search_user_role( int $user_id = 0 ): string {
		global $wpdb;

		if ( ! $user_id ) {
			$user_id = wp_get_current_user_id();
		}

		$prefixes = get_option( 'wporphanageex_prefixes' );
		if ( $prefixes && is_array( $prefixes ) ) {
			foreach ( $prefixes as $prefix ) {
				$role = get_user_meta( $user_id, $prefix . 'capabilities', true );
				if ( '' !== $role && is_array( $role ) ) {
					foreach ( $role as $key => $value ) {
						return $key;
					}
				}
			}
		}

		// if no one was found, return default role.
		$default = get_option( 'wporphanageex_role' );

		return $default;
	}
}
