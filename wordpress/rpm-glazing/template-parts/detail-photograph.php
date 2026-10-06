<?php
if (!defined('ABSPATH')) { exit; }
$photo_id = (int) rpm_get_field('detail_photo');
$gallery = rpm_get_field('project_gallery');
if (!$photo_id && !$gallery) { return; }
?>
<div class="rpm-detail-photograph rpm-wrap">
    <?php if ($photo_id) : ?>
    <figure class="rpm-detail-photograph__lead">
        <?php echo wp_get_attachment_image($photo_id, 'full', false, array('loading' => 'lazy', 'decoding' => 'async', 'sizes' => '(max-width: 760px) calc(100vw - 44px), (max-width: 1280px) 90vw, 1120px')); ?>
    </figure>
    <?php endif; ?>
    <?php if (is_array($gallery) && $gallery) : ?>
    <div class="rpm-project-gallery">
        <?php foreach ($gallery as $photo) : if (empty($photo['image'])) { continue; } ?>
        <figure>
            <?php echo wp_get_attachment_image((int) $photo['image'], 'large', false, array('loading' => 'lazy', 'decoding' => 'async', 'sizes' => '(max-width: 760px) calc(100vw - 44px), (max-width: 1280px) 44vw, 544px')); ?>
        </figure>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
