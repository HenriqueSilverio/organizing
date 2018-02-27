<?php
/**
 * Documents repository
 *
 * @package HenriqueSilverio\Organizing
 * @since 1.0.0
 */
namespace HenriqueSilverio\Organizing;

/**
 * DocsRepository class
 *
 * Handles communication with WordPress database to get `attachment`s data.
 *
 * @since 1.0.0
 */
class DocsRepository
{
    /**
     * @since 1.0.0
     * @var \WP_Query $query Stores a instance of \WP_Query class.
     */
    private $query = null;

    /**
     * Sets repository storage property.
     *
     * @since 1.0.0
     * @param \WP_Query $query Instance of \WP_Term_Query class.
     */
    public function __construct(\WP_Query $query)
    {
        $this->query = $query;
    }

    /**
     * Gets max number of pages, useful for pagination of results.
     *
     * @since 1.0.0
     * @return int Number of pages based on last executed query.
     */
    public function getMaxNumPages()
    {
        return $this->query->max_num_pages;
    }

    /**
     * Find all `attachment`s for the given arguments.
     *
     * @since 1.0.0
     * @param array $arguments
     * @return \WP_Post[]
     */
    private function find(array $arguments = [])
    {
        $defaults = [
            'post_type'              => 'attachment',
            'post_mime_type'         => 'application/pdf',
            'post_status'            => 'any',
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false
        ];

        $query = array_merge($defaults, $arguments);

        return $this->query->query($query);
    }

    /**
     * Finds one specific `attachment`.
     *
     * @since 1.0.0
     * @param array $arguments A list of \WP_Query arguments.
     * @return \WP_Post|null The matched `attachment` post or null.
     */
    private function findOne(array $arguments)
    {
        $defaults = [
            'posts_per_page' => 1,
            'no_found_rows'  => true
        ];

        $query = array_merge($arguments, $defaults);

        $results = $this->find($query);

        return empty($results[0]) ? null : $results[0];
    }

    /**
     * Finds a `attachment` by its ID.
     *
     * @since 1.0.0
     * @param int $id Post `attachment` ID.
     * @return \WP_Post|null
     */
    public function findById(int $id = 0)
    {
        return $this->findOne(['p' => $id]);
    }

    /**
     * Find all `attachment` posts.
     *
     * @since 1.0.0
     * @param array $arguments
     * @return \WP_Post[]|null
     */
    public function findAll(array $arguments = [])
    {
        $results = $this->find($arguments);

        return empty($results[0]) ? null : $results;
    }

    /**
     * Find a list of years, useful to create a "Yearly Archive" dropdown.
     *
     * @since 1.0.0
     * @global $wpdb Object used to interact with WordPress database.
     * @return array A list of years that have `attachment`s posted in.
     * @see https://codex.wordpress.org/Class_Reference/wpdb
     */
    public function findYears()
    {
        global $wpdb;

        $query = "
            SELECT
                YEAR(`post_date`) AS `year`
            FROM
                $wpdb->posts
            WHERE
                `post_type` = 'attachment' AND `post_mime_type` = 'application/pdf'
            GROUP BY
                YEAR(`post_date`)
            ORDER BY
                `post_date` DESC
        ";

        return $wpdb->get_col(trim($query));
    }
}
