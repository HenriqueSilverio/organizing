<?php
/**
 * Installation actions.
 *
 * @package HenriqueSilverio\Organizing\Utils
 * @since 1.0.0
 */
namespace HenriqueSilverio\Organizing\Utils;

/**
 * Installer class.
 *
 * @since 1.0.0
 */
class Installer
{
    /**
     * Install Organizing.
     *
     * @since 1.0.0
     */
    public static function install()
    {
        if (false === get_option('organizing_need_flush_rewrite', false)) {
            add_option('organizing_need_flush_rewrite', 1);
        }
    }
}
