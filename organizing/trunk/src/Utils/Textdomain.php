<?php
/**
 * Localisation setup
 *
 * @since 1.0.0
 */
namespace HenriqueSilverio\Organizing\Utils;

/**
 * @since 1.0.0
 */
class Textdomain
{
    /**
     * Hook actions and filters.
     *
     * @since 1.0.0
     */
    public function start()
    {
        add_action('init', [$this, 'load']);
    }

    /**
     * Load plugin textdomain.
     *
     * @since 1.0.0
     */
    public function load()
    {
        load_plugin_textdomain('organizing', false, ORGANIZING_PLUGIN_PATH . '/languages');
    }
}
