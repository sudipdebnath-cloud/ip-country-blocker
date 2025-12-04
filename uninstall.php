<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete plugin options
delete_option('wicb_settings');
delete_option('wicb_blocked_countries');
delete_option('wicb_blocked_ips');

// Delete country lookup transients safely
$wicb_all_options = wp_load_alloptions();

foreach ($wicb_all_options as $wicb_option_name => $wicb_value) {
    // Match only transients related to this plugin
    if (strpos($wicb_option_name, '_transient_geo_country_') === 0) {
        $wicb_transient_name = str_replace('_transient_', '', $wicb_option_name);
        delete_transient($wicb_transient_name);
    }

    if (strpos($wicb_option_name, '_transient_timeout_geo_country_') === 0) {
        $wicb_transient_name = str_replace('_transient_timeout_', '', $wicb_option_name);
        delete_transient($wicb_transient_name);
    }
}
