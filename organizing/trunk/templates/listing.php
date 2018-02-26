<?php
/**
 * Organizing "Document Library" listing
 *
 * @since 1.0.0
 */

/**
 * @todo Add docs.
 * @since 1.0.0
 */
do_action('organizing_before_begin_listing_container'); ?>

<div class="organizing">

    <?php
        /**
         * @todo Add docs.
         * @since 1.0.0
         */
        do_action('organizing_before_begin_listing_filter');
    ?>

    <form class="organizing-filter" method="GET" action="<?php esc_attr_e(get_permalink(get_queried_object_id())); ?>">
        <div class="organizing-filter__column">
            <label class="organizing-form-label" for="organizing-category">
                <?php _ex('Category', 'Input label', 'organizing'); ?>
            </label>
            <select id="organizing-category" class="organizing-form-control" name="organizing-category">
                <option value="" <?php selected($attributes['selectedCategory'], ''); ?>>
                    <?php _ex('All Categories', 'Default select option', 'organizing'); ?>
                </option>

                <?php if (false === empty($attributes['categories'])) : ?>
                    <?php foreach($attributes['categories'] as $category) : ?>
                        <option value="<?php esc_attr_e($category->term_id); ?>" <?php selected($attributes['selectedCategory'], $category->term_id); ?>>
                            <?php esc_html_e($category->name, 'organizing'); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="organizing-filter__column">
            <label class="organizing-form-label" for="organizing-year">
                <?php _ex('Year', 'Input label', 'organizing'); ?>
            </label>
            <select id="organizing-year" class="organizing-form-control organizing-form-control--year" name="organizing-year">
                <option value="" <?php selected($attributes['selectedYear'], ''); ?>>
                    <?php _ex('Any', 'Default select option', 'organizing'); ?>
                </option>

                <?php if (false === empty($attributes['years'])) : ?>
                    <?php foreach ($attributes['years'] as $year) : ?>
                        <option value="<?php esc_attr_e($year); ?>" <?php selected($attributes['selectedYear'], $year); ?>>
                            <?php esc_html_e($year); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="organizing-filter__column">
            <label class="organizing-form-label organizing-sr-only" for="organizing-keyword">
                <?php _ex('Subject', 'Input label', 'organizing'); ?>
            </label>
            <input
                id="organizing-keyword"
                class="organizing-form-control"
                name="organizing-keyword"
                type="text"
                placeholder="<?php _ex('Keywords', 'Input placeholder', 'organizing'); ?>"
                value="<?php esc_attr_e($attributes['selectedKeyword']); ?>"
            >
        </div>

        <div class="organizing-filter__column">
            <button class="organizing-button" type="submit">
                <?php _ex('Search', 'Filter submit button', 'organizing'); ?>
            </button>
        </div>
    </form>

    <?php
        /**
         * @todo Add docs.
         * @since 1.0.0
         */
        do_action('organizing_before_begin_listing');
    ?>

    <div class="organizing-results <?php echo empty($attributes['attachments']) ? 'organizing-results--empty' : ''; ?>">
        <?php if (false === empty($attributes['attachments'])) : ?>

            <ul class="organizing-list">
                <?php foreach($attributes['attachments'] as $file) : ?>
                    <li class="organizing-list-item">
                        <a
                            class="organizing-list-item__link"
                            title="<?php esc_attr_e('Download', 'organizing'); ?>"
                            href="<?php esc_attr_e(wp_get_attachment_url($file->ID)); ?>"
                            download
                        >
                            <div class="organizing-list-item__thumb-mask">
                                <?php
                                    $src = wp_get_attachment_image_src(
                                        get_post_meta($file->ID, '_organizing_listing_image', true),
                                        'organizing-listing'
                                    );
                                ?>
                                <img
                                    class="organizing-list-item__thumb"
                                    src="<?php esc_attr_e(empty($src) ? '' : $src[0]); ?>"
                                    title="<?php esc_attr_e($file->post_title); ?>"
                                    alt="<?php esc_attr_e($file->post_title); ?>"
                                >
                            </div>
                            <div class="organizing-list-item__info">
                                <?php if ('' !== $file->post_title) : ?>
                                    <span class="organizing-list-item__title">
                                        <?php esc_html_e($file->post_title); ?>
                                    </span>
                                <?php endif; ?>

                                <span class="organizing-list-item__metada">
                                    <?php
                                        $timestamp = strtotime($file->post_date);
                                        $date      = date_i18n($attributes['dateFormat'], $timestamp);

                                        esc_html_e(sprintf('Published in: %s', $date), 'organizing');
                                    ?>
                                </span>

                                <?php if ('' !== $file->post_excerpt) : ?>
                                    <span class="organizing-list-item__excerpt">
                                        <?php esc_html_e($file->post_excerpt); ?>
                                    </span>
                                <?php endif; ?>

                                <span class="organizing-list-item__download">
                                    <?php _ex('Download', 'Download link', 'organizing'); ?>
                                </span>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

        <?php else: ?>

            <p class="organizing-alert organizing-alert--warning">
                <?php _e('No documents found.', 'organizing'); ?>
            </p>

        <?php endif; ?>
    </div>

    <?php
        /**
         * @todo Add docs.
         * @since 1.0.0
         */
        do_action('organizing_after_end_listing', $attributes);
    ?>

    <?php if (false === empty($attributes['pagination'])) : ?>

        <nav class="organizing-pagination">
           <?php echo $attributes['pagination']; ?>
        </nav>

    <?php endif; ?>

    <?php
        /**
         * @todo Add docs.
         * @since 1.0.0
         */
        do_action('organizing_after_end_listing_pagination', $attributes);
    ?>

</div>

<?php
    /**
     * @todo Add docs.
     * @since 1.0.0
     */
    do_action('organizing_after_end_listing_container', $attributes);
?>
