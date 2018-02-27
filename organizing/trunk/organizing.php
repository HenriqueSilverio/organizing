<?php
/**
 * The plugin bootstrap file.
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package HenriqueSilverio\Organizing
 *
 * @wordpress-plugin
 * Plugin Name:       Organizing
 * Plugin URI:        https://github.com/HenriqueSilverio/organizing
 * Description:       Easily create a Document Library and show a filterable listing with download links.
 * Version:           1.0.0
 * Author:            Henrique Silvério
 * Author URI:        https://henriquesilverio.github.io
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       organizing
 * Domain Path:       /languages
 */
use HenriqueSilverio\Organizing;

/**
 * Prevents direct file access.
 */
if (false === defined('ABSPATH')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    die();
}

/** Requires the classes autoloader. */
require_once 'vendor/autoload.php';

/** Gets the main plugin instance. */
$plugin = Organizing\Plugin::get_instance();

/** Starts the plugin when we are ready. */
add_action('plugins_loaded', [$plugin, 'start']);

/** Run needed activation routines. */
register_activation_hook(__FILE__, ['HenriqueSilverio\Organizing\Utils\Installer', 'install']);
