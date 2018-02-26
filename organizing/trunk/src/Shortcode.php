<?php
/**
 * Listing Shortcode
 *
 * @since 1.0.0
 * @package HenriqueSilverio\Organizing
 */
namespace HenriqueSilverio\Organizing;

/**
 * Shortcode class.
 *
 * @since 1.0.0
 */
class Shortcode
{
    /**
     * @since 1.0.0
     * @var string $tag Stores the shortcode slug/ID.
     */
    private $tag = 'organizing';

    /**
     * @since 1.0.0
     * @var DocsRepository $docsRepository Stores a instance of DocsRepository class.
     */
    private $docsRepository = null;

    /**
     * @since 1.0.0
     * @var TermsRepository $termsRepository Stores a instance of TermsRepository class.
     */
    private $termsRepository = null;

    /**
     * Sets Shortcode class properties.
     *
     * @since 1.0.0
     * @param DocsRepository  $docsRepository  Instance of DocsRepository class.
     * @param TermsRepository $termsRepository Instance of TermsRepository class.
     */
    public function __construct(DocsRepository $docsRepository, TermsRepository $termsRepository)
    {
        $this->docsRepository  = $docsRepository;
        $this->termsRepository = $termsRepository;
    }

    /**
     * Hook actions and filters.
     *
     * @since 1.0.0
     */
    public function start()
    {
        add_action('wp_enqueue_scripts', [$this, 'registerAssets']);
        add_action('init', [$this, 'registerShortCode']);
    }

    /**
     * Registers shortcode with WordPress API.
     *
     * @since 1.0.0
     */
    public function registerShortCode()
    {
        add_shortcode($this->tag, [$this, 'render']);
    }

    /**
     * Register styles used in default listing theme.
     *
     * @since 1.0.0
     */
    public function registerAssets()
    {
        wp_register_style(
            $this->tag,
            ORGANIZING_CSS_URL . '/organizing.css',
            [],
            null,
            'all'
        );
    }

    /**
     * Fetches data from repositories, with filter criteria, and outputs the HTML.
     *
     * @since 1.0.0
     * @return string HTML used to `echo`s the shortcode.
     */
    public function render($attributes)
    {
        if (get_query_var('paged')) {
            $page = sanitize_text_field(get_query_var('paged'));
        } else if (get_query_var('page')) {
            $page = sanitize_text_field(get_query_var('page'));
        } else {
            $page = 1;
        }

        $selectedCategory = sanitize_text_field(get_query_var('organizing-category'));
        $selectedYear     = sanitize_text_field(get_query_var('organizing-year'));
        $typedKeyword     = sanitize_text_field(get_query_var('organizing-keyword'));

        $taxQuery = [];

        if (false === empty($selectedCategory)) {
            $taxQuery[] = [
                'taxonomy' => Taxonomy::TAXONOMY_SLUG,
                'field'    => 'term_id',
                'terms'    => $selectedCategory
            ];
        }

        $categories = $this->termsRepository->findAll();

        $years = $this->docsRepository->findYears();

        $attachments = $this->docsRepository->findAll([
            'paged'          => $page,
            'tax_query'      => $taxQuery,
            's'              => $typedKeyword,
            'year'           => $selectedYear,
            'posts_per_page' => get_option('organizing_settings')
        ]);

        $total = $this->docsRepository->getMaxNumPages();

        $pagination = paginate_links([
            'type'      => 'list',
            'total'     => $total,
            'prev_text' => '&laquo;',
            'next_text' => '&raquo;'
        ]);

        $defaults = [
            'selectedCategory' => $selectedCategory,
            'selectedYear'     => $selectedYear,
            'selectedKeyword'  => $typedKeyword,
            'categories'       => $categories,
            'years'            => $years,
            'dateFormat'       => get_option('date_format'),
            'attachments'      => $attachments,
            'pagination'       => $pagination
        ];

        $attributes = shortcode_atts($defaults, $attributes, $this->tag);

        return $this->getOutput($attributes);
    }

    /**
     * Renders the shortcode template with data fetched from repositories.
     *
     * @since 1.0.0
     * @return string Processed template structure.
     */
    private function getOutput(array $attributes = [])
    {
        wp_enqueue_style($this->tag);

        ob_start();

        require ORGANIZING_TEMPLATE_PATH . '/listing.php';

        return ob_get_clean();
    }
}
