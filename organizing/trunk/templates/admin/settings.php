<?php
/**
 * Organizing settings page
 *
 * @since 1.0.0
 */
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <form method="POST" action="options.php">
        <?php
            settings_fields('options-organizing');
            do_settings_sections('options-organizing');
            submit_button(__('Save Settings', 'organizing'));
        ?>
    </form>
</div>
