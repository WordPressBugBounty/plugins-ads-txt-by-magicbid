<?php
if (!defined('ABSPATH')) {
    exit;
}

// Review Action For Admin
add_action('admin_notices', 'mb_plgn_ads_review_notice');

function mb_plgn_ads_review_notice()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    if (get_option('mb_plgn_ads_review_dismissed')) {
        return;
    }

    global $wpdb;
    $mb_plgn_ads_table_main = $wpdb->prefix . 'mb_plgn_ads_txt_backups';
    // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,PluginCheck.Security.DirectDB.UnescapedDBParameter -- $mb_plgn_ads_table_main derived from $wpdb->prefix and escaped; safe internal query; No need of cache
    $mb_plgn_ads_total_ads = $wpdb->get_var("SELECT count(*) FROM $mb_plgn_ads_table_main");
    if (!$mb_plgn_ads_total_ads) {
        return;
    }
?>
    <div class="notice notice-info is-dismissible mb_plgn_ads-review-notice">
        <p>
            ⭐ <strong>Thank you for using Ads.txt File Manager By Magicbid.</strong><br>
            If it’s adding value to your site, we’d truly appreciate a quick review 🚀.
            Your feedback helps us keep building better tools for publishers.
            We appreciate your support.
        </p>
        <p>
            <a href="https://wordpress.org/support/plugin/ads-txt-by-magicbid/reviews/"
                target="_blank"
                class="button button-primary">
                Leave a Review
            </a>
        </p>
    </div>
<?php
}

add_action('admin_enqueue_scripts', function () {
    wp_add_inline_script('jquery', "
        jQuery(document).on('click', '.mb_plgn_ads-review-notice .notice-dismiss', function() {
            jQuery.post(ajaxurl, {
                action: 'mb_plgn_ads_dismiss_review_notice'
            });
        });
    ");
});

add_action('wp_ajax_mb_plgn_ads_dismiss_review_notice', function () {
    update_option('mb_plgn_ads_review_dismissed', 1);
    wp_die();
});
