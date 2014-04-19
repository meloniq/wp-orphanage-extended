=== WP-Orphanage Extended ===
Contributors: meloniq
Donate link: http://blog.meloniq.net/donate/
Tags: users, shared user table, CUSTOM_USER_TABLE, CUSTOM_USER_META_TABLE
Requires at least: 3.3
Tested up to: 3.9
Stable tag: 1.1

Plugin to promote users with no roles set (the orphans) to the role from other blog where they registered or to default if any found.

== Description ==

Users who have not been assigned any Roles or Capabilities are called 'orphans'. When using the [shared users table trick](http://xentek.net/articles/528/implementing-the-wordpress-shared-users-table-trick/) to link up multiple WordPress installations, users who register on one of your blogs, are not given any privileges on the other blogs in the network. WP-Orphanage is a plugin that automatically adopts your orphan users by promoting them to the role of your choosing. By default it is the same as the default role set in the WP Options.

It does it in two ways:

1. Users who try to login to a different blog in the network than the one they signed up on, will be promoted at the time of login. The user won't even know that it happened.
1. When the admin logs into the blog and views the users page, all orphan users – for that blog – are promoted automatically.

By taking a just in time approach, this plugin will not add any noticeable overhead to your WordPress blogs, while providing a seamless experience for the users and administrators.

This plugin is a extended version of WP-Orphanage plugin written by Eric Marden

== Installation ==

1. Download the wp-orphanage-extended.zip file, unzip and upload the whole directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Edit the plugin options to set the role you would like users to be promoted to, and other WP prefixes where to search for roles.
1. As an admin you can visit the Users page to automatically upgrade all orphan users of that blog to that role. Users who login before you do that will also get the same treatment (but only for their account).

== Frequently Asked Questions ==

= Why Would I Want This? =

If you are using the ``CUSTOM_USER_TABLE`` and ``CUSTOM_USER_META_TABLE`` in your [wp-config.php](http://codex.wordpress.org/Editing_wp-config.php#Custom_User_and_Usermeta_Tables), you're probably going to want this.

== Screenshots ==

1. WP-Orphanage Extended Options Screen

== Changelog ==

= 1.1 =
* Changed textdomain to 'wp-orphanage-extended', represented by constant WPOEX_TD
* Minor code styling corrections

= 1.0 = 
* Initial release.

This plugin is a extended version of WP-Orphanage plugin written by Eric Marden (xentek.net)

