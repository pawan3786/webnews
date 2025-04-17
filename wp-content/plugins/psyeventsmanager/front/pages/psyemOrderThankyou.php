<?php

/**
 * Template Name: Psyem Events Thanks
 */
?>
<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
get_header();
while (have_posts()) : the_post();
    $REQData            = (isset($_GET) && !empty($_GET)) ? $_GET : [];
    $booking_order_id   = (isset($REQData['checkkey']) && !empty($REQData['checkkey'])) ? $REQData['checkkey'] :  '';
?>
    <main id="content" <?php post_class('site-main'); ?> style="max-width: 100%; overflow: hidden;">
        <div class="container">
            <div class="row">
                <?php if (apply_filters('hello_elementor_page_title', true)) : ?>
                    <div class="page-header col-md-12">
                        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                    </div>
                <?php endif; ?>

                <div class="page-content col-md-12 mb-5">
                    <?php the_content(); ?>
                </div>

                <div class="post-thankyou col-md-12 mb-5">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">
                                        <?= __('Booking confirmed', 'psyeventsmanager') ?>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="alert alert-success mb-5" role="alert">
                                                <?= __('We are delighted to inform you that your booking has been successfully confirmed, and your payment has been processed. We look forward to serving you and ensuring you have a wonderful experience', 'psyeventsmanager') ?>
                                            </div>
                                            <div class="alert alert-success" role="alert">
                                                <strong> <?= __('REFERENCE ID', 'psyeventsmanager') ?>: <?= @$booking_order_id ?> </strong>
                                                <br />
                                                <?= __('Click', 'psyeventsmanager') ?>
                                                <a href="<?= psyem_GetPageLinkBySlug('psyem-events-list') ?>" class="alert-link">
                                                    <?= __('here', 'psyeventsmanager') ?>
                                                </a>
                                                <?= __('to view more events or book new tickets.', 'psyeventsmanager') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
<?php
endwhile;
get_footer();
