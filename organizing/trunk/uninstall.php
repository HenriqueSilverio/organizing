<?php
/**
 * Organizing uninstall
 *
 * @package HenriqueSilverio\Organizing
 * @since 1.0.0
 */

/**
 * Prevents direct file access.
 */
if (false === defined('WP_UNINSTALL_PLUGIN')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    die();
}

/**
 * Removes Organizing options.
 *
 * @since 1.0.0
 */
delete_option('organizing_settings');
delete_option('organizing_children');


/**
 * Removes Organizing `attachment`s categories.
 *
 * @since 1.0.0
 */
global $wpdb;

$query = "
    SELECT
        `t`.`term_id`,
        `tt`.`term_taxonomy_id`
    FROM
        $wpdb->terms AS `t`
    INNER JOIN
        $wpdb->term_taxonomy AS `tt`
    ON
        `t`.`term_id` = `tt`.`term_id`
    WHERE
        `tt`.`taxonomy` = 'organizing'
";

$results = $wpdb->get_results(trim($query));

if ($results) {
    foreach ($results as $result) {
        $wpdb->delete($wpdb->term_relationships, ['term_taxonomy_id' => $result->term_taxonomy_id]);
        $wpdb->delete($wpdb->term_taxonomy, ['term_taxonomy_id' => $result->term_taxonomy_id]);
        $wpdb->delete($wpdb->terms, ['term_id' => $result->term_id]);
    }
}
