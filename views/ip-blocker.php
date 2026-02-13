<?php
defined('ABSPATH') || exit;
$wicb_ips = WICB_IP_Blocker::get_ips();
?>
<div class="wrap wicb-wrap">
    <h1><?php echo esc_html__('IP Blocker', 'developersd-accessshield'); ?></h1>

    <form method="post" style="margin-bottom:20px;">
        <?php wp_nonce_field('wicb_save_ip', 'wicb_ip_nonce'); ?>
        <input type="text" name="new_ip" placeholder="<?php echo esc_attr__('Enter IP address to block', 'developersd-accessshield'); ?>" required>
        <button class="button button-primary"><?php echo esc_html__('Add IP', 'developersd-accessshield'); ?></button>
    </form>

    <?php if (!empty($wicb_ips)) : ?>
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
            <!-- Search box -->
            <input type="text" id="ipSearch" placeholder="<?php echo esc_attr__('Search IP...', 'developersd-accessshield'); ?>" style="width: 300px;">

            <!-- Delete All Form -->
            <form method="post">
                <?php wp_nonce_field('wicb_delete_all_ips', 'wicb_delete_all_nonce'); ?>
                <button class="button button-secondary" type="submit" name="delete_all_ips" onclick="return confirm('<?php echo esc_js(__('Are you sure you want to delete all IPs?', 'developersd-accessshield')); ?>');">
                    <?php echo esc_html__('Delete All IPs', 'developersd-accessshield'); ?>
                </button>
            </form>
        </div>
    <?php endif; ?>

    <table class="widefat striped" id="ipListTable">
        <thead>
            <tr>
                <th><?php echo esc_html__('IP Address', 'developersd-accessshield'); ?></th>
                <th><?php echo esc_html__('Actions', 'developersd-accessshield'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($wicb_ips as $wicb_ip): ?>
                <tr>
                    <td><?php echo esc_html($wicb_ip); ?></td>
                    <td>
                        <a href="?page=wicb-ip&delete_ip=<?php echo urlencode($wicb_ip); ?>"
                           class="button button-small delete-ip"
                           onclick="return confirm('<?php echo esc_js(__('Delete this IP?', 'developersd-accessshield')); ?>');">
                            <?php echo esc_html__('Delete', 'developersd-accessshield'); ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
