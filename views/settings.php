<?php
$wicb_settings = WICB_Settings_Page::get_settings();
?>
<div class="wrap wicb-wrap">
    <h1>
        <?php echo esc_html__('IP & Country Blocker', 'ip-country-blocker'); ?> - 
        <?php echo esc_html__('Settings', 'ip-country-blocker'); ?>
    </h1>

    <form method="post" action="options.php">
        <?php settings_fields('wicb_settings_group'); ?>
        <?php do_settings_sections('wicb_settings_group'); ?>

        <table class="form-table">
            <tr>
                <th scope="row"><?php echo esc_html__('Enable Country Blocking', 'ip-country-blocker'); ?></th>
                <td>
                    <input type="checkbox" name="wicb_settings[enable_country]" value="1"
                        <?php checked($wicb_settings['enable_country'], 1); ?>>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php echo esc_html__('Enable IP Blocking', 'ip-country-blocker'); ?></th>
                <td>
                    <input type="checkbox" name="wicb_settings[enable_ip]" value="1"
                        <?php checked($wicb_settings['enable_ip'], 1); ?>>
                </td>
            </tr>
        </table>

        <?php submit_button(); ?>
    </form>
</div>
