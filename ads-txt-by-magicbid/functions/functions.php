<?php

if (!defined('ABSPATH')) exit;

// Admin menu
add_action('admin_menu', function () {
    add_menu_page(
        'Ads.txt',
        'Ads.txt',
        'manage_options',
        'mb-plgn-ads-txt-file-manager',
        'mb_plgn_ads_txt_admin_page',
        'dashicons-media-text',
        60
    );
});

// Load page
function mb_plgn_ads_txt_admin_page()
{
    include_once MB_PLGN_ADS_TXT_PATH . 'views/admin-page.php';
}

// Enqueue assets
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook === 'toplevel_page_mb-plgn-ads-txt-file-manager') {
        wp_enqueue_style('mb-plgn-ads-txt-style', MB_PLGN_ADS_TXT_URL . 'assets/style.css', [], '2.0.0');
        wp_enqueue_script('mb-plgn-ads-txt-script', MB_PLGN_ADS_TXT_URL . 'js/admin.js', ['jquery'], '2.0.1', true);
        wp_localize_script('mb-plgn-ads-txt-script', 'mbPlgnAdsTxtAjax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mb_plgn_ads_txt_nonce')
        ]);
    }
});


// AJAX handlers
add_action('wp_ajax_load_mb_plgn_ads_txt', function () {
    check_ajax_referer('mb_plgn_ads_txt_nonce');

    $type = (isset($_POST['file_type']) && $_POST['file_type'] === 'app') ? 'app-ads.txt' : 'ads.txt';
    $file = MB_PLGN_HOME_PATH . $type;
    if (!file_exists($file)) {
        wp_send_json_error(['message' => 'ads.txt not found']);
    }

    $content = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    wp_send_json_success(['content' => $content]);
});

add_action('wp_ajax_create_mb_plgn_ads_txt', function () {
    $type = (isset($_POST['file_type']) && $_POST['file_type'] === 'app') ? 'app-ads.txt' : 'ads.txt';
    check_ajax_referer('mb_plgn_ads_txt_nonce');
    file_put_contents(MB_PLGN_HOME_PATH . $type, '');
    wp_send_json_success();
});

add_action('wp_ajax_save_mb_plgn_ads_txt', function () {
    check_ajax_referer('mb_plgn_ads_txt_nonce');
    if (!current_user_can('manage_options')) wp_send_json_error();

    if (!isset($_POST['content'])) {
        wp_send_json_error(['message' => 'No content found to save!']);
    }
    $content = implode("\n", array_map('sanitize_text_field', wp_unslash($_POST['content'])));
    $type = (isset($_POST['file_type']) && $_POST['file_type'] === 'app') ? 'app-ads.txt' : 'ads.txt';
    $file = MB_PLGN_HOME_PATH . $type;

    // Backup current content
    if (file_exists($file)) {
        $old_content = file_get_contents($file);
        if (!empty($old_content)) {
            global $wpdb;
            /** @noinspection SqlResolve */
            /** @noinspection SqlNoDataSourceInspection */
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
            $wpdb->insert($wpdb->prefix . 'mb_plgn_ads_txt_backups', [
                'content' => $old_content,
                'user_id' => get_current_user_id(),
                'file_type' => (isset($_POST['file_type']) && $_POST['file_type'] === 'app') ? 'app' : 'web'
            ]);
        }
    }

    file_put_contents($file, $content);
    wp_send_json_success(['message' => 'Ads.txt saved']);
});

add_action('wp_ajax_load_mb_plgn_ads_txt_backups', function () {
    check_ajax_referer('mb_plgn_ads_txt_nonce');
    if (!current_user_can('manage_options')) wp_send_json_error();
    global $wpdb;
    $fileType = (isset($_POST['file_type']) && $_POST['file_type'] === 'app') ? 'app' : 'web';
    /** @noinspection SqlResolve */
    /** @noinspection SqlNoDataSourceInspection */
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery
    $results = $wpdb->get_results(
        $wpdb->prepare("
        SELECT b.id, b.content, b.created_at, u.display_name as user_name
        FROM {$wpdb->prefix}mb_plgn_ads_txt_backups b
        LEFT JOIN {$wpdb->prefix}users u ON b.user_id = u.ID
        WHERE b.file_type = %s
        ORDER BY b.created_at DESC
    ", $fileType),
        ARRAY_A
    );

    wp_send_json_success($results);
});

add_action('wp_ajax_restore_mb_plgn_ads_txt', function () {
    check_ajax_referer('mb_plgn_ads_txt_nonce');
    global $wpdb;
    if (!isset($_POST['id'])) {
        wp_send_json_error(['message' => 'Backup id not found!']);
    }
    $id = intval($_POST['id']);
    /** @noinspection SqlResolve */
    /** @noinspection SqlNoDataSourceInspection */
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.DirectQuery
    $row = $wpdb->get_row($wpdb->prepare("SELECT content FROM {$wpdb->prefix}mb_plgn_ads_txt_backups WHERE id = %d", $id));
    if (!$row) wp_send_json_error();

    // Backup current before restoring
    $type = (isset($_POST['file_type']) && $_POST['file_type'] === 'app') ? 'app-ads.txt' : 'ads.txt';
    $current = file_get_contents(MB_PLGN_HOME_PATH . $type);
    /** @noinspection SqlResolve */
    /** @noinspection SqlNoDataSourceInspection */
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
    $wpdb->insert($wpdb->prefix . 'mb_plgn_ads_txt_backups', [
        'content' => $current,
        'user_id' => get_current_user_id()
    ]);

    file_put_contents(MB_PLGN_HOME_PATH . $type, $row->content);
    wp_send_json_success(['message' => 'Backup restored and current version saved.']);
});

add_action('wp_ajax_delete_mb_plgn_ads_txt_backup', function () {
    check_ajax_referer('mb_plgn_ads_txt_nonce');
    if (!current_user_can('manage_options')) wp_send_json_error();

    global $wpdb;
    $id = isset($_POST['id']) ? intval($_POST['id']) : '';
    $table = $wpdb->prefix . 'mb_plgn_ads_txt_backups';
    // Delete backup row (safe prepared DELETE query)
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
    $deleted = $wpdb->delete($table, ['id' => $id]);

    if ($deleted) {
        wp_send_json_success();
    } else {
        wp_send_json_error(['message' => 'Could not delete backup']);
    }
});
