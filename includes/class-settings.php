<?php

defined('ABSPATH') || exit;

class WICB_Settings_Page {

    private $option_key = 'wicb_settings';

    public function __construct() {
        add_action('admin_init', [$this, 'register_settings']);
        add_action('update_option_' . $this->option_key, [$this, 'settings_updated'], 10, 3);
        add_action('admin_notices', [$this, 'admin_notices']);
    }

    public function register_settings() {
        register_setting(
            'wicb_settings_group',
            $this->option_key,
            [
                'sanitize_callback' => [$this, 'sanitize_settings'],
            ]
        );
    }

    /**
     * Sanitize the settings before saving.
     */
    public function sanitize_settings($input) {
        $output = [];

        // Make sure checkboxes are either 1 or 0
        $output['enable_country'] = isset($input['enable_country']) && $input['enable_country'] ? 1 : 0;
        $output['enable_ip']      = isset($input['enable_ip']) && $input['enable_ip'] ? 1 : 0;

        return $output;
    }

    /**
     * Set a transient notice when settings are updated.
     */
    public function settings_updated($old_value, $value, $option) {
        if ($old_value !== $value) {
            set_transient('wicb_settings_notice', 'success', 30);
        } else {
            set_transient('wicb_settings_notice', 'error', 30);
        }
    }

    /**
     * Display admin notices for success or error.
     */
    public function admin_notices() {
        $notice = get_transient('wicb_settings_notice');
        if (!$notice) return;

        if ($notice === 'success') {
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Settings updated successfully.', 'ip-country-blocker') . '</p></div>';
        } elseif ($notice === 'error') {
            echo '<div class="notice notice-error is-dismissible"><p>' . esc_html__('No changes made or an error occurred.', 'ip-country-blocker') . '</p></div>';
        }

        delete_transient('wicb_settings_notice');
    }

    /**
     * Get settings with default values.
     */
    public static function get_settings() {
        return get_option('wicb_settings', [
            'enable_country' => 0,
            'enable_ip' => 0,
        ]);
    }
}
