<?php

defined('ABSPATH') || exit;

class WICB_Country_Blocker {

    private $option = 'wicb_blocked_countries';

    public function __construct() {
        add_action('admin_init', [$this, 'save_countries']);
        add_action('admin_notices', [$this, 'admin_notices']);
    }

    public function save_countries() {
        // Unslash all POST data first
        $post_data = wp_unslash($_POST);

        if (!isset($post_data['wicb_countries']) || !check_admin_referer('wicb_save_countries', 'wicb_nonce')) {
            return;
        }

        $posted_countries = $post_data['wicb_countries'];

        // Make sure it's an array
        if (!is_array($posted_countries)) {
            return;
        }

        // Sanitize each country code
        $countries = array_map('sanitize_text_field', $posted_countries);

        // Update option safely
        $updated = update_option($this->option, $countries);

        if ($updated !== false) {
            set_transient('wicb_country_notice', 'success', 30);
        } else {
            set_transient('wicb_country_notice', 'error', 30);
        }
    }

    public function admin_notices() {
        $notice = get_transient('wicb_country_notice');
        if (!$notice) return;

        if ($notice === 'success') {
            echo '<div class="notice notice-success is-dismissible"><p>Countries updated successfully.</p></div>';
        } elseif ($notice === 'error') {
            echo '<div class="notice notice-error is-dismissible"><p>Nothing was changed or an error occurred.</p></div>';
        }

        delete_transient('wicb_country_notice');
    }

    public static function get_blocked_countries() {
        return get_option('wicb_blocked_countries', []);
    }
}
