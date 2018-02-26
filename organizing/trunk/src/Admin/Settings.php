<?php
/**
 * Organizing settings page
 *
 * @package HenriqueSilverio\Organizing\Admin
 * @since 1.0.0
 */
namespace HenriqueSilverio\Organizing\Admin;

/**
 * Settings class.
 *
 * @since 1.0.0
 */
class Settings
{
    /**
     * Hook actions and filters.
     *
     * @since 1.0.0
     */
    public function start()
    {
        add_action('admin_init', [$this, 'onAdminInit']);
        add_action('admin_menu', [$this, 'onAdminMenuReady']);
    }

    /**
     * Add Organizing settings page.
     *
     * @since 1.0.0
     */
    public function onAdminMenuReady() {
        add_options_page(
            __('Organizing Settings', 'organizing'),
            __('Organizing', 'organizing'),
            'manage_options',
            'options-organizing',
            [$this, 'pageRenderer']
        );
    }

    /**
     * Register settings, sections and fields with WordPress Settings API.
     *
     * @since 1.0.0
     */
    public function onAdminInit()
    {
        $sectionGroup = 'options-organizing';
        $sectionName  = 'organizing_settings';

        register_setting(
            $sectionGroup,
            $sectionName,
            [
                'type'              => 'integer',
                'default'           => 5,
                'sanitize_callback' => 'sanitize_text_field'
            ]
        );

        $settingsSection  = 'reading';
        $page             = $sectionGroup;

        add_settings_section(
            $settingsSection,
            __('Reading', 'organizing'),
            null,
            $page
        );

        add_settings_field(
            $sectionName,
            __('Listings show at most', 'organizing'),
            [$this, 'inputRenderer'],
            $page,
            $settingsSection,
            ['label_for' => $sectionName]
        );
    }

    /**
     * Renders form input.
     *
     * @since 1.0.0
     */
    public function inputRenderer()
    {
        $options = get_option('organizing_settings');

        echo '
            <input
                id="organizing_settings"
                class="small-text"
                name="organizing_settings"
                type="number"
                step="1"
                min="1"
                required
                value="' . $options . '"
            >

            documents
        ';
    }

    /**
     * Renders settings page.
     *
     * @since 1.0.0
     */
    public function pageRenderer()
    {
        require ORGANIZING_TEMPLATE_PATH . '/admin/settings.php';
    }
}
