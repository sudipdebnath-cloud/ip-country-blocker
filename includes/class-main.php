<?php

defined('ABSPATH') || exit;

class WICB_Main {

    /**
     * Plugin initializer
     */
    public static function init() {

        // Register Activation & Deactivation Hooks only once
        register_activation_hook(WICB_PATH . 'wp-ip-country-blocker.php', ['WICB_Main', 'activate']);
        register_deactivation_hook(WICB_PATH . 'wp-ip-country-blocker.php', ['WICB_Main', 'deactivate']);

        // Load dependencies
        require_once WICB_PATH . 'includes/class-admin-menu.php';
        require_once WICB_PATH . 'includes/class-settings.php';
        require_once WICB_PATH . 'includes/class-country-blocker.php';
        require_once WICB_PATH . 'includes/class-ip-blocker.php';
        require_once WICB_PATH . 'includes/class-blocker-frontend.php';

        // Bootstrap core classes
        new WICB_Admin_Menu();
        new WICB_Settings_Page();
        new WICB_Country_Blocker();
        new WICB_IP_Blocker();
        new WICB_Frontend_Blocker();
    }

    /**
     * Runs on plugin activation
     */
    public static function activate() {

        // Create default settings if not exist
        if (!get_option('wicb_settings')) {
            add_option('wicb_settings', [
                'enable_country' => 0,
                'enable_ip'      => 0,
            ]);
        }

        // Initialize blocked data arrays
        if (!get_option('wicb_blocked_countries')) {
            add_option('wicb_blocked_countries', []);
        }

        if (!get_option('wicb_blocked_ips')) {
            add_option('wicb_blocked_ips', []);
        }
    }

    /**
     * Runs on plugin deactivation
     */
    public static function deactivate() {
        // Optional cleanup code
    }
}
