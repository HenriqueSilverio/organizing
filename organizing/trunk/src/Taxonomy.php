<?php
/**
 * Organizing Taxonomy
 *
 * @since 1.0.0
 * @package HenriqueSilverio\Organizing
 */
namespace HenriqueSilverio\Organizing;

/**
 * Taxonomy class.
 *
 * @since 1.0.0
 */
class Taxonomy
{
    /**
     * @since 1.0.0
     * @var string TAXONOMY_SLUG Taxonomy slug
     */
    const TAXONOMY_SLUG = 'organizing';

    /**
     * Hook actions and filters.
     *
     * @since 1.0.0
     */
    public function start()
    {
        add_action('init', [$this, 'register'], 10);
        add_action('after_setup_theme', [$this, 'addImageSize']);
        add_filter('post_mime_types', [$this, 'filterMimeTypes']);
    }

    /**
     * Registers a custom taxonomy to `attachment`s post type..
     * By default, `Category` labels are used for hierarchical taxonomies.
     *
     * @since 1.0.0
     * @see https://developer.wordpress.org/reference/functions/register_taxonomy/
     */
    public static function register()
    {
        $rewrite = [
            'slug'         => _x('organizing', 'Taxonomy slug', 'organizing'),
            'hierarchical' => true
        ];

        $arguments = [
            'public'                => true,
            'hierarchical'          => true,
            'show_admin_column'     => false,
            'rewrite'               => $rewrite,
            'update_count_callback' => '_update_generic_term_count'
        ];

        register_taxonomy(self::TAXONOMY_SLUG, 'attachment', $arguments);
    }

    /**
     * Registers listing image size.
     *
     * @since 1.0.0
     */
    public function addImageSize()
    {
        add_image_size('organizing-listing', 130, 184);
    }

    /**
     * Add `application/pdf` mime type to attachment filter dropdown.
     *
     * @since 1.0.0
     * @param array $mimeTypes
     * @return array $mimeTypes
     */
    function filterMimeTypes(array $mimeTypes) {
        $mimeTypes['application/pdf'] = [
            'PDF',
            'Manage PDFs',
            _n_noop('PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>', 'organizing')
        ];

        return $mimeTypes;
    }
}
