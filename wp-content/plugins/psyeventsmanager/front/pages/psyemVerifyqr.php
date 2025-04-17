<?php

/**
 * Template Name: Psyem Events List
 */
?>
<?php
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
get_header();
while (have_posts()) : the_post();
    $REQData            = (isset($_GET) && !empty($_GET)) ? $_GET : [];
    $ticketinfoEnc      = (isset($REQData['ticketinfo']) && !empty($REQData['ticketinfo'])) ? $REQData['ticketinfo'] :  '';
    $order_id           = (isset($REQData['order']) && !empty($REQData['order'])) ? $REQData['order'] :  '';
    $participant_id     = (isset($REQData['participant']) && !empty($REQData['participant'])) ? $REQData['participant'] :  '';

    $ticketInfo         = (!empty($ticketinfoEnc)) ? psyem_safe_b64decode($ticketinfoEnc) : '';
    $ticketInfoArr      = (!empty($ticketInfo)) ? explode('@_@', $ticketInfo) : [];

    $ticket_participant = (isset($ticketInfoArr[0])) ? $ticketInfoArr[0] : 0;
    $ticket_order       = (isset($ticketInfoArr[1])) ? $ticketInfoArr[1] : 0;

    $is_valid           = false;
    $event_name         = '';
    $participant_name   = '';
    if (($ticket_participant == $participant_id) && ($ticket_order == $order_id)) {
        $order_data           = psyem_GetSinglePostWithMetaPrefix('psyem-orders', $order_id, 'psyem_order_');
        $order_meta           = @$order_data['meta_data'];

        $orderParticipantsArr = get_post_meta(@$order_id, 'psyem_order_participants', true);
        $orderParticipantsIds = (!empty($orderParticipantsArr) && is_array($orderParticipantsArr)) ? array_values($orderParticipantsArr) : [];
        $is_valid             = (!empty($orderParticipantsIds) && is_array($orderParticipantsIds) && in_array($participant_id, $orderParticipantsIds)) ? true : false;

        $order_event_id       = @$order_meta['psyem_order_event_id'];
        $event_data           = psyem_GetSinglePostWithMetaPrefix('psyem-events', $order_event_id, 'psyem_event_');
        $event_meta           = @$event_data['meta_data'];
        $event_name           = @$event_data['title'];

        $participant_data     = psyem_GetSinglePostWithMetaPrefix('psyem-participants', $participant_id, 'psyem_participant_');
        $participant_meta     = @$participant_data['meta_data'];
        $participant_name     = @$participant_data['title'];
        if ($is_valid) {
            psyem_UpdateOrderUsedSlotsCount($order_data, $event_data, $participant_data);
        }
    }
?>
    <main id="content" <?php post_class('site-main'); ?> style="max-width: 100%; overflow: hidden;">
        <?php if (apply_filters('hello_elementor_page_title', true)) : ?>
            <div class="page-header">
                <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
            </div>
        <?php endif; ?>

        <div class="page-content  mb-5">
            <?php the_content(); ?>
        </div>

        <div class="post-thankyou mb-5">
            <div class="row">
                <div class="col-md-12">
                    <?php if ($is_valid): ?>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <?= __('QR Scan successfully confirmed', 'psyeventsmanager') ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-success" role="alert">
                                            <strong> <?= __('Welcome', 'psyeventsmanager') ?> : <?= @$participant_name ?> </strong>
                                        </div>
                                        <div class="alert alert-success mb-5" role="alert">
                                            <?= __('Thank you, Your booking ticket scan for', 'psyeventsmanager') ?>
                                            <strong> <?= __('EVENT', 'psyeventsmanager') ?>: <?= @$event_name ?> </strong>
                                            <?= __('has been successfully confirmed.', 'psyeventsmanager') ?>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <?= __('Click', 'psyeventsmanager') ?>
                                        <a href="<?= get_site_url() ?>" class="alert-link">
                                            <?= __('here', 'psyeventsmanager') ?>
                                        </a>
                                        <?= __('to visit the site.', 'psyeventsmanager') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <?= __('The QR code scan was unsuccessful.', 'psyeventsmanager') ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-danger" role="alert">
                                            <strong>
                                                <?= __('The participant QR code scan has been failed to confirm.', 'psyeventsmanager') ?>
                                            </strong>
                                        </div>
                                        <div class="alert alert-danger mb-5" role="alert">
                                            <strong>
                                                <?= __('Participant QR code link is INVALID.', 'psyeventsmanager') ?>
                                            </strong>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <?= __('Click', 'psyeventsmanager') ?>
                                        <a href="<?= get_site_url() ?>" class="alert-link">
                                            <?= __('here', 'psyeventsmanager') ?>
                                        </a>
                                        <?= __('to visit the site.', 'psyeventsmanager') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
<?php
endwhile;
get_footer();
