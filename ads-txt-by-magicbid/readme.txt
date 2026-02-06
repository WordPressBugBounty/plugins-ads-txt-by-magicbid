=== Ads.txt File Manager By Magicbid ===
Contributors: ratneshmagicbid
Tags: ads.txt, app-ads.txt, monetization, publisher, google ads
Requires at least: 5.0
Tested up to: 6.9
Requires PHP: 7.2
Stable tag: 2.1.9
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Easily manage ads.txt and app-ads.txt files from your WordPress dashboard with editing, backup, and restore options.

== Description ==

**Ads.txt File Manager By Magicbid** allows publishers to manage both `ads.txt` and `app-ads.txt` file directly from the WordPress admin panel, without using FTP or file managers. It offers a safe and intuitive UI to help users edit, save, and back up their ads.txt file to comply with programmatic advertising requirements.

### Features

- **Live ads.txt editor** with line numbering and syntax highlighting
- **Automatic versioned backups** every time you save changes
- **Restore previous versions** anytime from the backup list
- **Create ads.txt file** instantly if it doesn't exist
- **Track changes by user**, showing which admin updated what and when
- **Secure editing** – only admins can access and modify the file
- **Lightweight and fast**, no bloated dependencies

### Why use this plugin?

If you're monetizing your site with platforms like Google AdSense, OpenX, or other SSPs/DSPs, you need to serve a valid `ads.txt` file at the root of your domain. This plugin simplifies that process by letting you manage the file without technical knowledge.

---

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Navigate to **Ads.txt** in the WordPress dashboard sidebar.
4. Use the editor to **create, edit, or restore** the `ads.txt` file.

---

== Usage ==

1. Go to `Ads.txt` from the left admin menu.
2. If the file doesn't exist, click **Create ads.txt**.
3. Edit the file in the editor and click **Save**.
4. Every save creates a backup with timestamp and user info.
5. You can restore any previous version from the **Backups** tab.

---

== Frequently Asked Questions ==

= Where is the ads.txt file saved? =  
The plugin saves it directly in your WordPress root directory as `/ads.txt`.

= What happens if I delete the file? =  
You can recreate it instantly using the **Create** button inside the plugin.

= Are previous versions saved? =  
Yes! Every save action stores a backup which you can restore anytime.

= Who can edit the file? =  
Only WordPress administrators with the `manage_options` capability.

= Can I use this with a multisite installation? =  
Currently, this plugin is designed for single-site use.

= Is this compatible with Google AdSense and Ad Manager? =  
Absolutely! This tool was built to simplify ads.txt compliance for platforms like Google AdSense, GAM and other SSPs.

---

== Screenshots ==

1. Popup to create new ads.txt file 
2. Editor with line numbering and Save button
3. Backup history with restore options
4. New Update UI

---

== Changelog ==

= 2.1.7 =
* Fixed a bug of null backup saving.

= 2.1.6 =
* Added Subscribe Us Widget that only get name, email and wesbite of the user.

= 2.1.5 =
* Added support for `app-ads.txt` alongside `ads.txt` with easy toggle switch.
* Improved UI: Switch between files using button-style toggle.
* Added ability to view and open current file directly from the plugin interface.
* Added delete option for individual backups.
* Added rotating promotional banner section.
* Enhanced responsive layout for editor and sidebar.
* Minor bug fixes and performance improvements.

= 2.1.2 =
* Improved warnings handling for code quality tools (PHPCS)
* Added sanitization and un-slashing to prevent unsafe input
* Added inline ignore flags for secure $wpdb usage

= 2.1.2 =
* Added user tracking for backups
* Improved backup restore logic
* Minor UI improvements

= 2.1.2 =
* Initial release

---

== Upgrade Notice ==

= 2.1.1 =
Better security and code quality handling. Upgrade recommended if you're using a PHPCS-enabled development workflow.

---

== Credits ==

Plugin developed by [Magicbid.ai](https://magicbid.ai/?utm_source=wordpressplugin%09&utm_medium=wordpressplugin%09&utm_campaign=wordpressplugin%09traffic&utm_id=wordpressplugin%09)

---

== Support ==

Need help or want to monetize your site?  
Email us at: `support@magicbid.ai`  
Or visit: [https://magicbid.ai/contact-us/](https://magicbid.ai/contact-us/?utm_source=wordpress-plugin%09&utm_medium=wordpress-plugin%09&utm_campaign=wordpress-plugin-traffic&utm_id=wordpress-plugin%09)

---