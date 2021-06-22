<?php
/*
Plugin Name: WP-Orphanage Extended
Plugin URI: https://blog.meloniq.net/2012/01/29/wp-orphanage-extended/
Description: Plugin to promote users with no roles set (the orphans) to the role from other blog where they registered or to default if any found.

Version: 1.2

Author: MELONIQ.NET
Author URI: https://meloniq.net/
Text Domain: wp-orphanage-extended
Domain Path: /languages
*/


/**
 * Avoid calling file directly.
 */
if ( ! function_exists( 'add_action' ) ) {
	die( 'Whoops! You shouldn\'t be doing that.' );
}


/**
 * Plugin version and textdomain constants.
 */
define( 'WPOEX_VERSION', '1.2' );
define( 'WPOEX_TD', 'wp-orphanage-extended' );


/**
 * Process actions on plugin activation.
 */
register_activation_hook( plugin_basename( __FILE__ ), 'wporphanageex_activate' );


/**
 * Load Text-Domain.
 */
load_plugin_textdomain( WPOEX_TD, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


/**
 * Action on plugin activate.
 *
 * @return void
 */
function wporphanageex_activate() {
	global $wpdb;

	// set default role if not exist
	if ( ! get_option( 'wporphanageex_role' ) && get_option( 'default_role' ) ) {
		update_option( 'wporphanageex_role', get_option( 'default_role' ) );
	} else {
		update_option( 'wporphanageex_role', 'subscriber' );
	}

	// set default prefix if not exist
	$prefixes = array();
	$prefixes[] = $wpdb->prefix;
	if ( ! get_option( 'wporphanageex_prefixes' ) ) {
		update_option( 'wporphanageex_prefixes', $prefixes );
	}

}


/**
 * Populate administration menu of the plugin.
 *
 * @return void
 */
function add_wporphanageex_options_page() {

	add_options_page( __( 'WP Orphanage Extended', WPOEX_TD ), __( 'WP Orphanage Extended', WPOEX_TD ), 'administrator', 'wp-orphanage-extended', 'wporphanageex_menu_settings' );
}
add_action( 'admin_menu', 'add_wporphanageex_options_page' );


/**
 * Create settings page in admin.
 *
 * @return void
 */
function wporphanageex_menu_settings() {
	include_once( dirname( __FILE__ ) . '/wp-orphanage-extended-options.php' );
}


/**
 * Adopts orphaned user.
 *
 * @param string $login
 *
 * @return void
 */
function wporphanageex_adopt_this_orphan( $login ) {
  $user = get_user_by( 'login', $login );

	if ( ! current_user_can( 'read' ) ) {
		$user_up = new WP_User( $user->ID );
		$user_up->set_role( wporphanageex_search_user_role( $user->ID ) );
	}
}
add_action( 'wp_login', 'wporphanageex_adopt_this_orphan' ); 


/**
 * Adopts all orphaned users.
 *
 * @return void
 */
function wporphanageex_adopt_all_orphans() {
	foreach ( wporphanageex_get_all_users() as $user_id ) {
		$user = new WP_User( $user_id );
		if ( ! user_can( $user_id, 'read' ) ) {
			$user->set_role( wporphanageex_search_user_role( $user_id ) );
		}
	}
}
add_action( 'load-users.php', 'wporphanageex_adopt_all_orphans' );


/**
 * Returns an array of user roles.
 *
 * @return array
 */
function wporphanageex_get_roles() {
	global $wpdb;

	$option = $wpdb->prefix . 'user_roles';
	return get_option( $option );
}


/**
 * Returns an array of user IDs.
 *
 * @return array
 */
function wporphanageex_get_all_users() {
	global $wpdb;

	$results = $wpdb->get_col( "SELECT ID FROM $wpdb->users" );
	return $results;
}


/**
 * Searching other blogs and returns a user role, if not found, returns default one.
 *
 * @param int $user_id (optional)
 *
 * @return string
 */
function wporphanageex_search_user_role( $user_id = false ) {
	global $wpdb, $current_user;

	$current_user = wp_get_current_user();
	if ( ! $user_id ) {
		$user_id = $current_user->ID;
	}

	$prefixes = get_option( 'wporphanageex_prefixes' );
	if ( $prefixes && is_array( $prefixes ) ) {
		foreach ( $prefixes as $prefix ) {
			$role = get_user_meta( $user_id, $prefix . 'capabilities', true );
			if ( $role != '' && is_array( $role ) ) {
				foreach ( $role as $key => $value ) {
					return $key;
				}
			}
		}
	}

	// if no one was found, return default role
	$default = get_option( 'wporphanageex_role' );
	return $default;
}

