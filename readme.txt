=== WP-Orphanage Extended ===
Contributors: meloniq
Tags: users, shared user table, CUSTOM_USER_TABLE, CUSTOM_USER_META_TABLE
Requires at least: 4.9
Tested up to: 6.8
Stable tag: 1.3
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html


Plugin to promote users with no roles set (the orphans) to the role from other blog where they registered or to default if any found.

== Description ==

Users who have not been assigned any Roles or Capabilities are called 'orphans'. 
When using the [shared users table trick](https://web.archive.org/web/20160226091450/http://xentek.net/articles/528/implementing-the-wordpress-shared-users-table-trick/) to link up multiple WordPress installations, users who register on one of your blogs, are not given any privileges on the other blogs in the network. 
WP-Orphanage is a plugin that automatically adopts your orphan users by promoting them to the role of your choosing. 
By default it is the same as the default role set in the WP Options.

It does it in two ways:

1. Users who try to login to a different blog in the network than the one they signed up on, will be promoted at the time of login. The user won't even know that it happened.
2. When the admin logs into the blog and views the users page, all orphan users – for that blog – are promoted automatically.

By taking a just in time approach, this plugin will not add any noticeable overhead to your WordPress blogs, while providing a seamless experience for the users and administrators.

This plugin is a extended version of WP-Orphanage plugin written by Eric Marden

== Installation ==

1. Download the wp-orphanage-extended.zip file, unzip and upload the whole directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Edit the plugin options to set the role you would like users to be promoted to, and other WP prefixes where to search for roles.
4. As an admin you can visit the Users page to automatically upgrade all orphan users of that blog to that role. Users who login before you do that will also get the same treatment (but only for their account).

== Frequently Asked Questions ==

= Why Would I Want This? =

If you are using the ``CUSTOM_USER_TABLE`` and ``CUSTOM_USER_META_TABLE`` in your [wp-config.php](https://wordpress.org/support/article/editing-wp-config-php/#custom-user-and-usermeta-tables), you're probably going to want this.

== Screenshots ==

1. WP-Orphanage Extended Options Screen

== Changelog ==

= 1.3 =
* Fixed security issue CSRF on settings page (reported by Wordfence)
* Fixed loading textdomain too early

= 1.2 =
* Escaped data on settings page

= 1.1 =
* Changed textdomain to 'wp-orphanage-extended', represented by constant WPOEX_TD
* Minor code styling corrections

= 1.0 = 
* Initial release.

This plugin is a extended version of WP-Orphanage plugin written by Eric Marden (xentek.net)

