<?php
/**
 * Listing Image meta box
 *
 * @package HenriqueSilverio\Organizing\Admin
 * @since 1.0.0
 */
namespace HenriqueSilverio\Organizing\Admin;

/**
 * MetaBox class.
 *
 * @since 1.0.0
 */
class MetaBox
{
    /**
     * @var string $nonceAction Action used in nonce field.
     * @since 1.0.0
     */
    private $nonceAction = '';

    /**
     * @var string $nonceName Name used in nonce field.
     * @since 1.0.0
     */
    private $nonceName = '_listing_image_nonce';

    /**
     * @var string $imageOptionName Option name as stored in database.
     * @since 1.0.0
     */
    private $imageOptionName = '_organizing_listing_image';

    /**
     * Sets default values.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->nonceAction = plugin_basename(__FILE__);
    }

    /**
     * Hook actions and filters.
     *
     * @since 1.0.0
     */
    public function start()
    {
        add_action('edit_attachment', [$this, 'save']);
        add_action('add_meta_boxes_attachment', [$this, 'register'], 10, 1);
        add_action('add_meta_boxes_attachment', [$this, 'maybeHideCategoryBox'], 11, 1);
        add_action('attachment_fields_to_edit', [$this, 'maybeHideCategoryField'], 10, 2);
    }

    /**
     * Remove meta box if attachment type is other than PDF.
     *
     * @since 1.0.0
     */
    public function maybeHideCategoryBox(\WP_Post $attachment)
    {
        if ('application/pdf' === $attachment->post_mime_type) {
            return;
        }

        remove_meta_box('organizingdiv', 'attachment', 'side');
    }

    /**
     * Remove "Category field" in "Attachment Details" if attachment type is other than PDF.
     *
     * @since 1.0.0
     */
    public function maybeHideCategoryField($fields, $attachment)
    {
        if ('application/pdf' === $attachment->post_mime_type) {
            return $fields;
        }

        unset($fields['organizing']);

        return $fields;
    }

    /**
     * Add Listing Image meta box to PDF `attachment`s.
     *
     * @since 1.0.0
     */
    public function register(\WP_Post $attachment)
    {
        if ('application/pdf' !== $attachment->post_mime_type) {
            return;
        }

        add_meta_box(
            'organizing-listing-image',
            _x('Listing Image', 'Meta box title', 'organizing'),
            [$this, 'render'],
            'attachment',
            'side'
        );
    }

    /**
     * Renders the Listing Image meta box.
     *
     * @since 1.0.0
     */
    public function render($attachment)
    {
        wp_enqueue_media();

        wp_enqueue_script(
            'organizing-admin',
            ORGANIZING_JS_URL . '/organizing.min.js',
            ['jquery'],
            null,
            true
        );

        $listingImageId = get_post_meta($attachment->ID, $this->imageOptionName, true);
        $savedImage     = wp_get_attachment_image_src($listingImageId, 'full');

        $attributes = [
            'savedImage'      => $savedImage,
            'savedImageId'    => $listingImageId,
            'imageOptionName' => $this->imageOptionName
        ];

        wp_nonce_field($this->nonceAction, $this->nonceName);

        require ORGANIZING_TEMPLATE_PATH . '/admin/metabox.php';
    }

    /**
     * Updates option in database.
     *
     * @since 1.0.0
     */
    public function save($attachmentId)
    {
        if (false === $this->canSaveData()) {
            return;
        }

        $cleanId = sanitize_text_field($_POST[$this->imageOptionName]);

        update_post_meta($attachmentId, $this->imageOptionName, $cleanId);
    }

    /**
     * Checks if a request to save meta box data is valid.
     *
     * @since 1.0.0
     */
    private function canSaveData()
    {
        $hasNonce     = isset($_POST[$this->nonceName]);
        $isValidNonce = wp_verify_nonce($_POST[$this->nonceName], $this->nonceAction);

        return $hasNonce && $isValidNonce;
    }
}
