<?php
/**
 * Organizing application
 *
 * @package HenriqueSilverio\Organizing
 * @since   1.0.0
 */
namespace HenriqueSilverio\Organizing;

/**
 * Main Plugin class.
 *
 * @since 1.0.0.
 */
final class Plugin
{
    /**
     * @since 1.0.0
     * @var string VERSION Current Organizing version.
     */
    const VERSION = '1.0.0';

    /**
     * @since 1.0.0
     * @var Plugin $instance A single instance of main Plugin class.
     */
    private static $instance = null;

    /**
     * Gets Plugin instance.
     *
     * Ensure only one instance of Plugin class is is loaded.
     *
     * @since 1.0.0
     * @return Plugin The single instance of main Plugin class.
     */
    public static function get_instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Hooks actions and filters.
     *
     * @since 1.0.0
     */
    public function start()
    {
        $this->startCommon();

        is_admin() ? $this->startBackEnd() : $this->startFrontEnd();
    }

    /**
     * Define Organizing constants.
     *
     * @since 1.0.0
     */
    private function defineConstants()
    {
        define('ORGANIZING_PLUGIN_PATH', dirname(__FILE__, 2));
        define('ORGANIZING_TEMPLATE_PATH', ORGANIZING_PLUGIN_PATH . '/templates');
        define('ORGANIZING_PLUGIN_URL', plugins_url('', ORGANIZING_PLUGIN_PATH . '/organizing.php'));
        define('ORGANIZING_CSS_URL', ORGANIZING_PLUGIN_URL . '/assets/dist/styles');
        define('ORGANIZING_JS_URL', ORGANIZING_PLUGIN_URL . '/assets/dist/scripts');
    }

    /**
     * Hook common actions and filters.
     *
     * @since 1.0.0
     */
    private function startCommon()
    {
        $this->defineConstants();

        $textdomain = new Utils\Textdomain();
        $textdomain->start();

        $rewrites = new Utils\Rewrites();
        $rewrites->start();

        $taxonomy = new Taxonomy();
        $taxonomy->start();
    }

    /**
     * Hook Back-End actions and filters.
     *
     * @since 1.0.0
     */
    private function startBackEnd()
    {
        $metaBox = new Admin\MetaBox();
        $metaBox->start();

        $settings = new Admin\Settings();
        $settings->start();
    }

    /**
     * Hook Front-End actions and filters.
     *
     * @since 1.0.0
     */
    private function startFrontEnd()
    {
        $filesQuery = new \WP_Query();
        $termsQuery = new \WP_Term_Query();

        $docsRepository = new DocsRepository($filesQuery);
        $termsRepository = new TermsRepository($termsQuery);

        $shortcode = new Shortcode($docsRepository, $termsRepository);
        $shortcode->start();
    }
}
