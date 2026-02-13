<?php
defined('ABSPATH') || exit;
$wicb_response = wp_remote_get('https://api.first.org/data/v1/countries?limit=300');
$wicb_data = json_decode(wp_remote_retrieve_body($wicb_response), true);

$wicb_countries = isset($wicb_data['data']) ? $wicb_data['data'] : [];
$wicb_blocked = WICB_Country_Blocker::get_blocked_countries();
$wicb_total_countries = count($wicb_countries);
?>
<div class="wrap wicb-wrap">
    <h1><?php echo esc_html__('Country Blocker', 'ip-country-blocker'); ?></h1>
    <p><?php echo esc_html__('Select countries you want to block from accessing your website.', 'ip-country-blocker'); ?></p>

    <!-- Total Countries -->
    <p><?php echo esc_html__('Total Countries', 'ip-country-blocker'); ?>: <?php echo esc_html($wicb_total_countries); ?></p>

    <form method="post" id="countriesForm">
        <?php wp_nonce_field('wicb_save_countries', 'wicb_nonce'); ?>
        <!-- Search + Buttons -->
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
            <input type="text" id="countrySearch" placeholder="<?php echo esc_attr__('Search countries...', 'ip-country-blocker'); ?>" style="width: 300px;">
            <div>
                <button class="button button-secondary" type="button" id="uncheckAllBtn"><?php echo esc_html__('Uncheck All', 'ip-country-blocker'); ?></button>
                <button class="button button-primary" type="submit" form="countriesForm"><?php echo esc_html__('Save Countries', 'ip-country-blocker'); ?></button>
            </div>
        </div>

        <div class="wicb-country-grid">
            <?php foreach ($wicb_countries as $wicb_code => $wicb_country): 
                $wicb_code = strtoupper($wicb_code);
                $wicb_name = $wicb_country['country']; // no esc_html here
            ?>
                <label class="wicb-country-item">
                    <input type="checkbox" name="wicb_countries[]"
                        value="<?php echo esc_attr($wicb_code); ?>"
                        <?php checked(in_array($wicb_code, $wicb_blocked)); ?>>
                    <span><?php echo esc_html($wicb_name); ?> (<?php echo esc_html($wicb_code); ?>)</span>
                </label>
            <?php endforeach; ?>
        </div>

        <button class="button button-primary" type="submit"><?php echo esc_html__('Save Countries', 'ip-country-blocker'); ?></button>
    </form>
</div>
