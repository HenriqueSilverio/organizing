<?php
/**
 * Organizing PDF meta box
 *
 * @since 1.0.0
 *
 * Available variables:
 *
 *     $attributes {
 *         @type array|bool $savedImage      Array if already has a image, false otherwise.
 *         @type string     $savedImageId    ID of `attachment` image cover.
 *         @type string     $imageOptionName Name of option saved into database.
 *     }
 */
?>

<div data-organizing-upload>
    <div class="<?php echo empty($attributes['savedImage']) ? '' : 'hidden'; ?>" data-organizing-view-empty>
        <p>
            <a data-organizing-upload-link href="#">
                <?php _e('Set listing image', 'organizing'); ?>
            </a>
        </p>
    </div>

    <div class="<?php echo empty($attributes['savedImage']) ? 'hidden' : ''; ?>" data-organizing-view-selected>
        <p>
            <a data-organizing-upload-link href="#">
                <img
                    data-organizing-upload-image
                    src="<?php esc_attr_e(empty($attributes['savedImage']) ? '' : $attributes['savedImage'][0]); ?>"
                    style="max-width: 100%;"
                >
            </a>
        </p>
        <p class="howto">
            <?php _e('Click the image to edit or update.', 'organizing'); ?>
        </p>
        <p>
            <a data-organizing-remove-link href="#">
                <?php _e('Remove listing image.', 'organizing'); ?>
            </a>
        </p>
    </div>
</div>

<input
    id="<?php esc_attr_e($attributes['imageOptionName']); ?>"
    name="<?php esc_attr_e($attributes['imageOptionName']); ?>"
    value="<?php esc_attr_e($attributes['savedImageId']); ?>"
    data-organizing-image-input
    type="hidden"
>
