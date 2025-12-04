<?php

defined('ABSPATH') || exit;

class WICB_IP_Blocker {

    private $option = 'wicb_blocked_ips';

    public function __construct() {
        add_action('admin_init', [$this, 'process_ip']);
        add_action('admin_notices', [$this, 'admin_notices']);
    }

    public function process_ip() {
        $ips = get_option($this->option, []);

        // Unslash all POST and GET data first
        $post_data = wp_unslash($_POST);
        $get_data  = wp_unslash($_GET);

        /**
         * Add new IP
         */
        if (isset($post_data['new_ip'])) {

            $wicb_ip_nonce = isset($post_data['wicb_ip_nonce']) ? $post_data['wicb_ip_nonce'] : '';

            // Verify nonce
            if (!wp_verify_nonce($wicb_ip_nonce, 'wicb_save_ip')) {
                wp_die('Security check failed. Please try again.');
            }

            $new_ip_raw = $post_data['new_ip'];
            $new_ip     = sanitize_text_field($new_ip_raw);

            if (filter_var($new_ip, FILTER_VALIDATE_IP)) {
                $ips[] = $new_ip;
                $ips   = array_unique($ips);
                update_option($this->option, $ips);

                // Store transient with IP info
                set_transient('wicb_ip_notice', ['type' => 'success_add', 'ip' => $new_ip], 30);
            } else {
                set_transient('wicb_ip_notice', ['type' => 'error_add', 'ip' => $new_ip], 30);
            }

            wp_safe_redirect(admin_url('admin.php?page=wicb-ip'));
            exit;
        }

        /**
         * Delete single IP
         */
        if (isset($get_data['delete_ip'])) {
            $ip_raw = $get_data['delete_ip'];
            $ip     = sanitize_text_field($ip_raw);

            if (in_array($ip, $ips)) {
                $ips = array_diff($ips, [$ip]);
                update_option($this->option, $ips);
                set_transient('wicb_ip_notice', ['type' => 'success_delete', 'ip' => $ip], 30);
            } else {
                set_transient('wicb_ip_notice', ['type' => 'error_delete', 'ip' => $ip], 30);
            }

            wp_safe_redirect(admin_url('admin.php?page=wicb-ip'));
            exit;
        }

        /**
         * Delete All IPs
         */
        if (isset($post_data['delete_all_ips'])) {
            $wicb_delete_all_nonce = isset($post_data['wicb_delete_all_nonce']) ? $post_data['wicb_delete_all_nonce'] : '';

            // Verify nonce
            if (!wp_verify_nonce($wicb_delete_all_nonce, 'wicb_delete_all_ips')) {
                wp_die('Security check failed. Please try again.');
            }

            update_option($this->option, []);
            set_transient('wicb_ip_notice', ['type' => 'success_delete_all'], 30);

            wp_safe_redirect(admin_url('admin.php?page=wicb-ip'));
            exit;
        }
    }

    public function admin_notices() {
        $notice = get_transient('wicb_ip_notice');
        if (!$notice) return;

        $type = $notice['type'];
        $ip = isset($notice['ip']) ? $notice['ip'] : '';

        switch ($type) {
            case 'success_add':
                echo '<div class="notice notice-success is-dismissible"><p>IP ' . esc_html($ip) . ' added successfully.</p></div>';
                break;
            case 'error_add':
                echo '<div class="notice notice-error is-dismissible"><p>Invalid IP: ' . esc_html($ip) . '. Please enter a valid IP address.</p></div>';
                break;
            case 'success_delete':
                echo '<div class="notice notice-success is-dismissible"><p>IP ' . esc_html($ip) . ' deleted successfully.</p></div>';
                break;
            case 'error_delete':
                echo '<div class="notice notice-error is-dismissible"><p>IP ' . esc_html($ip) . ' not found or could not be deleted.</p></div>';
                break;
            case 'success_delete_all':
                echo '<div class="notice notice-success is-dismissible"><p>All IPs deleted successfully.</p></div>';
                break;
        }

        delete_transient('wicb_ip_notice');
    }

    public static function get_ips() {
        return get_option('wicb_blocked_ips', []);
    }
}
