<?php
/**
 * Plugin Name:       Ads.txt File Manager By Magicbid
 * Plugin URI:        https://magicbid.ai/contact-us/?utm_source=wordpress-plugin&utm_medium=wordpress-plugin&utm_campaign=wordpress-plugin-traffic&utm_id=wordpress-plugin
 * Description:       Easily manage your site's both ads.txt and app-ads.txt file directly from the WordPress admin, with automatic versioned backups.
 * Version:           2.2.0
 * Author:            Magicbid.ai
 * Author URI:        https://magicbid.ai/?utm_source=wordpressplugin&utm_medium=wordpressplugin&utm_campaign=wordpressplugintraffic&utm_id=wordpressplugin
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */


if (!defined('ABSPATH')) exit;

// Define constants
define('MB_PLGN_ADS_TXT_PATH', plugin_dir_path(__FILE__));
define('MB_PLGN_ADS_TXT_URL', plugin_dir_url(__FILE__));
if (!function_exists('get_home_path')) {
    require_once ABSPATH . 'wp-admin/includes/file.php';
}
define('MB_PLGN_HOME_PATH', get_home_path());

define('MB_PLGN_ADS_TXT_DB_VERSION', '1.1');

// Activation hook: create backups table
register_activation_hook(__FILE__, function () {

    global $wpdb;
    $table_name = $wpdb->prefix . 'mb_plgn_ads_txt_backups';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS `$table_name` (
        `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `content` LONGTEXT NOT NULL,
        `user_id` BIGINT(20) UNSIGNED NOT NULL,
        `file_type` VARCHAR(10) DEFAULT 'web',
        `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);

    global $wpdb;
});

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'mb_plgn_ads_txt_plugin_action_links');
function mb_plgn_ads_txt_plugin_action_links($links)
{
    $settings_link = '<a href="admin.php?page=mb-plgn-ads-txt-file-manager">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

define('MB_PLGN_ADS_TXT_TABLE_BACKUPS', $wpdb->prefix . 'mb_plgn_ads_txt_backups' );

// UPDATE COLUMN
add_action('plugins_loaded', 'mb_plgn_ads_txt_check_db_version');

function mb_plgn_ads_txt_check_db_version() {
    global $wpdb;

    $installed_ver = get_option('mb_plgn_ads_txt_db_version');
    if ($installed_ver !== MB_PLGN_ADS_TXT_DB_VERSION) {

        // Check if 'file_type' column exists
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.PreparedSQL.NotPrepared -- $table is derived from $wpdb->prefix and escaped. DDL queries cannot be prepared or cached.;
        $column = $wpdb->get_var("SHOW COLUMNS FROM `".MB_PLGN_ADS_TXT_TABLE_BACKUPS."` LIKE 'file_type'");
        if (empty($column) || $column === null) {
        // Add column if missing (schema change during plugin upgrade)
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.DirectDatabaseQuery.SchemaChange,WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQL.NotPrepared -- $table is derived from $wpdb->prefix and escaped;
            $wpdb->query("ALTER TABLE `".MB_PLGN_ADS_TXT_TABLE_BACKUPS."` ADD COLUMN `file_type` VARCHAR(10) DEFAULT 'web'");
        }

        update_option('mb_plgn_ads_txt_db_version', MB_PLGN_ADS_TXT_DB_VERSION);
    }
}

require_once MB_PLGN_ADS_TXT_PATH . 'functions/functions.php';
