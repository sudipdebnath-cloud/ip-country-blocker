<?php

defined('ABSPATH') || exit;

class WICB_Admin_Menu {

    public function __construct() {
        add_action('admin_menu', [$this, 'register_menu']);
        add_action('admin_enqueue_scripts', function() {
            wp_enqueue_style(
                'wicb-admin-css',
                WICB_URL . 'assets/admin.css',
                [],
                time() // dynamic version to prevent caching
            );

            wp_enqueue_script(
                'wicb-admin-js',
                WICB_URL . 'assets/admin.js',
                [],
                time(), // dynamic version
                true
            );
        });
    }

    public function register_menu() {
        add_menu_page(
            'IP & Country Blocker',
            'IP & Country Blocker',
            'manage_options',
            'wicb-settings',
            [$this, 'settings_page'],
            'dashicons-shield-alt'
        );

        add_submenu_page(
            'wicb-settings',
            'Country Blocker',
            'Country Blocker',
            'manage_options',
            'wicb-country',
            [$this, 'country_page']
        );

        add_submenu_page(
            'wicb-settings',
            'IP Blocker',
            'IP Blocker',
            'manage_options',
            'wicb-ip',
            [$this, 'ip_page']
        );
    }

    public function settings_page() {
        include WICB_PATH . 'views/settings.php';
    }

    public function country_page() {
        include WICB_PATH . 'views/country-blocker.php';
    }

    public function ip_page() {
        include WICB_PATH . 'views/ip-blocker.php';
    }
}
