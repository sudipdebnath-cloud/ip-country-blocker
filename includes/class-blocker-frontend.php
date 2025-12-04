<?php

defined('ABSPATH') || exit;

class WICB_Frontend_Blocker {

    public function __construct() {
        add_action('init', [$this, 'block_visitors']);
    }

    /**
     * Get the real visitor IP
     */
    private function get_visitor_ip() {
        $ip = '';

        // Cloudflare support
        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = sanitize_text_field( wp_unslash($_SERVER['HTTP_CF_CONNECTING_IP']) );
        }
        // Standard proxy headers
        elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = sanitize_text_field( wp_unslash($_SERVER['HTTP_CLIENT_IP']) );
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Could be a comma-separated list of IPs, take the first one
            $ips = explode(',', sanitize_text_field( wp_unslash($_SERVER['HTTP_X_FORWARDED_FOR']) ));
            $ip = trim($ips[0]);
        } 
        elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = sanitize_text_field( wp_unslash($_SERVER['REMOTE_ADDR']) );
        }

        // Validate IP
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            $ip = '0.0.0.0';
        }

        return $ip;
    }


    public function block_visitors() {
        $settings = WICB_Settings_Page::get_settings();
        $ip = $this->get_visitor_ip();

        // Block IPs
        if (!empty($settings['enable_ip'])) {
            $ips = WICB_IP_Blocker::get_ips();
            if (in_array($ip, $ips)) {
                wp_die('Access denied: IP blocked', 'Access Denied', ['response' => 403]);
            }
        }

        // Block Countries
        if (!empty($settings['enable_country'])) {

            $cache = 'geo_country_' . md5($ip);
            $country = get_transient($cache);

            if (!$country) {
                $response = wp_remote_get("https://ipapi.co/{$ip}/country/");
                $country = strtoupper(trim(wp_remote_retrieve_body($response)));
                set_transient($cache, $country, DAY_IN_SECONDS);
            }

            $blocked = WICB_Country_Blocker::get_blocked_countries();
            if (in_array($country, $blocked)) {
                wp_die('Access denied from your country', 'Access Denied', ['response' => 403]);
            }
        }
    }
}
