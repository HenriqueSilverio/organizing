<?php
/**
 * Rewrite rules actions
 *
 * @package HenriqueSilverio\Organizing\Utils
 * @since 1.0.0
 */
namespace HenriqueSilverio\Organizing\Utils;

/**
 * Rewrites class.
 *
 * @since 1.0.0
 */
class Rewrites
{
    /**
     * Hook actions and filters.
     *
     * @since 1.0.0
     */
    public function start()
    {
        add_action('init', [$this, 'maybe_flush_rewrite'], 20);
        add_action('query_vars', [$this, 'register_query_vars']);
    }

    /**
     * Flush rewrite rules after a fresh Organizing install.
     *
     * @since 1.0.0
     */
    public function maybe_flush_rewrite()
    {
        if (get_option('organizing_need_flush_rewrite')) {
            flush_rewrite_rules();
            delete_option('organizing_need_flush_rewrite');
        }
    }

    /**
     * Registers query vars to be used on listing filters.
     *
     * @since 1.0.0
     */
    public function register_query_vars($vars)
    {
        $vars[] = 'organizing-category';
        $vars[] = 'organizing-year';
        $vars[] = 'organizing-keyword';

        return $vars;
    }
}
