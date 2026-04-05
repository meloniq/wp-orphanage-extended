<?php
/**
 * Plugin Name: WP-Orphanage Extended
 * Plugin URI: https://blog.meloniq.net/2012/01/29/wp-orphanage-extended/
 *
 * Description: Plugin to promote users with no roles set (the orphans) to the role from other blog where they registered or to default if any found.
 * Tags: orphanage, orphan, user, role
 *
 * Requires at least: 4.9
 * Requires PHP:      7.4
 * Version: 1.4
 *
 * Author: MELONIQ.NET
 * Author URI: https://meloniq.net/
 *
 * License:           GPLv2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain: wp-orphanage-extended
 *
 * @package WPOrphanage\Extended
 */

namespace WPOrphanage\Extended;

// If this file is accessed directly, then abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Plugin version and textdomain constants.
 */
define( 'WPOEX_VERSION', '1.4' );
define( 'WPOEX_TD', 'wp-orphanage-extended' );


/**
 * Setup.
 *
 * @return void
 */
function init() {
	global $wporphanageex;

	require_once __DIR__ . '/src/class-utils.php';
	require_once __DIR__ . '/src/class-admin-page.php';
	require_once __DIR__ . '/src/class-core.php';

	$wporphanageex['admin-page'] = new Admin_Page();
	$wporphanageex['core']       = new Core();
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\init' );

/**
 * Action on plugin activate.
 *
 * @return void
 */
function activate() {
	global $wpdb;

	// set default role if not exist.
	if ( ! get_option( 'wporphanageex_role' ) ) {
		if ( get_option( 'default_role' ) ) {
			update_option( 'wporphanageex_role', get_option( 'default_role' ) );
		} else {
			update_option( 'wporphanageex_role', 'subscriber' );
		}
	}

	// set default prefix if not exist.
	$prefixes   = array();
	$prefixes[] = $wpdb->prefix;
	if ( ! get_option( 'wporphanageex_prefixes' ) ) {
		update_option( 'wporphanageex_prefixes', $prefixes );
	}
}
register_activation_hook( __FILE__, __NAMESPACE__ . '\activate' );
