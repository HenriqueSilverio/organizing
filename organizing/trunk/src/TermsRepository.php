<?php
/**
 * Terms repository
 *
 * @package HenriqueSilverio\Organizing
 * @since 1.0.0
 */
namespace HenriqueSilverio\Organizing;

/**
 * TermsRepository class
 *
 * Handles communication with WordPress database to get terms data.
 *
 * @since 1.0.0
 */
class TermsRepository
{
    /**
     * @since 1.0.0
     * @var \WP_Term_Query $query Stores a instance of \WP_Term_Query class.
     */
    private $query = null;

    /**
     * Sets repository storage property.
     *
     * @since 1.0.0
     * @param \WP_Term_Query $query Instance of \WP_Term_Query class.
     */
    public function __construct(\WP_Term_Query $query)
    {
        $this->query = $query;
    }

    /**
     * Find all terms for the given arguments.
     *
     * @since 1.0.0
     * @param array $arguments
     * @return \WP_Term[]|null
     * @see Taxonomy::TAXONOMY_SLUG
     */
    private function find(array $arguments = [])
    {
        $defaults = [
            'taxonomy'               => Taxonomy::TAXONOMY_SLUG,
            'hide_empty'             => false,
            'update_post_term_cache' => false
        ];

        $query = array_merge($defaults, $arguments);

        return $this->query->query($query);
    }

    /**
     * Find all terms.
     *
     * @since 1.0.0
     * @return \WP_Term[]|null
     */
    public function findAll()
    {
        return $this->find();
    }

    /**
     * Find one specific term.
     *
     * @since 1.0.0
     * @param array $arguments \WP_Term_Query arguments.
     * @return \WP_Term|null
     */
    public function findOne(array $arguments = [])
    {
        if (empty($arguments['term_id'])) {
            return null;
        }

        $query = ['term_id' => $arguments['term_id']];

        $results = $this->find($query);

        return $results[0];
    }

    /**
     * Find a term by its ID.
     *
     * @since 1.0.0
     * @param int $id Term ID
     * @return \WP_Term|null
     */
    public function findById(int $id)
    {
        return $this->findOne(['term_id' => $id]);
    }
}
