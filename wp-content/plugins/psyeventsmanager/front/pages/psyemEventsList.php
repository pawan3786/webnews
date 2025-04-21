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
    $REQData        = (isset($_GET) && !empty($_GET)) ? $_GET : [];
    $searchq        = (isset($REQData['search_key'])) ? $REQData['search_key'] : '';
    $search_filter  = (isset($REQData['search_filter'])) ? $REQData['search_filter'] : '';
    $cpage          = (isset($REQData['cpage'])) ? $REQData['cpage'] : '';

    $REQData['limit']        = 19;
    $REQData['search_filter'] = $search_filter;
    $REQData['search_key']   = $searchq;
    $REQData['cpage']        = $cpage;
    $REQData['from_date']    = '';
    $REQData['to_date']      = '';
    $REQData['signup_type']  = '';

    $AllEventsInfo  = psyem_GetAllEventsForOrder($REQData);
    $AllEventsData  = @$AllEventsInfo['data'];
    $PaginationData = @$AllEventsInfo['pagination'];
    $MasterSettings = psyem_GetOptionsWithPrefix();
    $page_id        = get_the_ID();
    $featured_image_url = get_the_post_thumbnail_url($page_id, 'full');
    $excerpt        = get_post_field('post_excerpt', $page_id);
?>
    <main id="content" <?php post_class('site-main psyemEventsListPageTemp'); ?> style="max-width: 100%; overflow: hidden;">
        <div class="page-header page-headerNewEvent" style="background-image: url(<?= $featured_image_url ?>);">
            <?php the_title('<h1 class="entry-title text-center">', '</h1>'); ?>
            <?= $excerpt; ?>
        </div>

        <div class="psyemPageEditorContent">
            <?php the_content(); ?>
        </div>

        <div class="searchSection">
            <div class="container-fuild">
                <div class="row align-items-center justify-content-center">
                    <div class="col-md-6">
                        <form action="" method="get" class="psyemPostListSearchForm">
                            <input type="submit" value="" class="search-submit">
                            <input type="hidden" name="search_filter" value="<?= $search_filter ?>" class="search_filter">
                            <input type="search" name="search_key" class="search-text" <?= (!empty($searchq)) ? 'autofocus="autofocus"' : '' ?> placeholder="<?= __('Search', 'psyeventsmanager') ?>..." autocomplete="off" value="<?= $searchq ?>">
                        </form>
                    </div>
                    <div class="col-md-6">
                        <ul class="categoryTabs">
                            <li class="psyemApplyFilterBtn" data-filter="upcoming">
                                <a href="javascript:void(0);" class="<?= ($search_filter == 'upcoming') ? 'actual-active' : '' ?>">
                                    <?= __('UPCOMING EVENTS', 'psyeventsmanager') ?>
                                </a>
                            </li>
                            <li class="psyemApplyFilterBtn" data-filter="past">
                                <a href="javascript:void(0);" class="<?= ($search_filter == 'past') ? 'actual-active' : '' ?>">
                                    <?= __('PAST EVENTS', 'psyeventsmanager') ?>
                                </a>
                            </li>
                            <li class="psyemApplyFilterBtn" data-filter="all">
                                <a href="javascript:void(0);" class="<?= ($search_filter == 'all') ? 'actual-active' : '' ?>">
                                    <?= __('VIEW ALL', 'psyeventsmanager') ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $largeBlock  = '';
        $firstBlock  = '';
        $secondBlock = '';
        $RowBlocks   = '';
        $postBlockType = '';

        /* firstSectionBlog - BGN */
        if (!empty($AllEventsData) && is_array($AllEventsData)):
            $pcount = 0;
            $ccount = 0;
            foreach ($AllEventsData as $cpostid => $currentPostData):
                $pcount++;
                $postBlockType = '';

                if (($pcount == 1)) {
                    $postBlockType = 'EventLargeImage';
                    $pfFilePath = PSYEM_PATH . 'front/pages/psyemSinglePost.php';
                    if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
                        $largeBlock = require $pfFilePath;
                        $largeBlock = trim($largeBlock);
                    }
                }

                if (($pcount == 2)) {
                    $postBlockType = 'EventLeftImage';
                    $pfFilePath = PSYEM_PATH . 'front/pages/psyemSinglePost.php';
                    if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
                        $firstBlock = require $pfFilePath;
                        $firstBlock = trim($firstBlock);
                    }
                }

                if (($pcount == 3)) {
                    $postBlockType = 'EventRightImage';
                    $pfFilePath = PSYEM_PATH . 'front/pages/psyemSinglePost.php';
                    if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
                        $secondBlock = require $pfFilePath;
                        $secondBlock = trim($secondBlock);
                    }
                    break;
                }
            endforeach;

            echo '
            <div class="firstSectionBlog">
                <div class="row margin0 w-100">
                    ' . $largeBlock . '
                    <div class="col-md-12 col-lg-6 padding0">
                        ' . $firstBlock . '
                        ' . $secondBlock . '
                    </div>
                </div>
            </div>';
        endif;
        /* firstSectionBlog - END */

        /*  secondSectionFold - BGN */
        echo '<div class="secondSectionFold">
        <div class="row margin0 w-100">';
        if (!empty($AllEventsData) && is_array($AllEventsData)):
            $pcount = 0;
            $ccount = 0;
            foreach ($AllEventsData as $cpostid => $currentPostData):
                $pcount++;
                $postBlockType = '';
                if (($pcount == 1) || ($pcount == 2) || ($pcount == 3)) {
                    continue;
                }
                if ($pcount > 3) {
                    $ccount++;
                    if (($ccount == 1)  || ($ccount == 2)) {
                        $postBlockType = 'EventLeftImageBlock';
                        $pfFilePath = PSYEM_PATH . 'front/pages/psyemSinglePost.php';
                        if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
                            $firstBlockHtml = require $pfFilePath;
                            echo $firstBlockHtml = trim($firstBlockHtml);
                        }
                    }

                    if (($ccount == 3)  || ($ccount == 4)) {
                        $postBlockType = 'EventRightImageBlock';
                        $pfFilePath = PSYEM_PATH . 'front/pages/psyemSinglePost.php';
                        if (@is_file($pfFilePath) && @file_exists($pfFilePath)) {
                            $secondBlockHtml = require $pfFilePath;
                            echo $secondBlockHtml = trim($secondBlockHtml);
                        }
                    }

                    if (($ccount == 4)) {
                        $ccount = 0;
                    }
                }
            endforeach;
        endif;
        echo '</div></div>';
        ?>

        <?php if (!empty($AllEventsData) && is_array($AllEventsData) && count($AllEventsData) > 0): else: ?>
            <div class="secondSectionFold">
                <div class="row margin0 w-100">
                    <div class="col-md-12 text-center pAll-0">
                        <div class="alert alert-danger">
                            <?= __('No records found', 'psyeventsmanager') ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row text-center mt-4 mb-4">
            <div class="col-md-12 text-center">
                <div class="psyPagination page_nav pagination" style="justify-content: normal; margin: 0px;">
                    <?php
                    echo paginate_links(array(
                        'base'      => add_query_arg('cpage', '%#%'),
                        'prev_text' => __('<<'),
                        'next_text' => __('>>'),
                        'total'     => @$PaginationData['total_page'] ?? 0,
                        'current'   => max(1, get_query_var('cpage'))
                    ));
                    ?>
                </div>
            </div>
        </div>

        <div class="page-content">
            <section class="paddSection pt-0 pb-0">
                <div class="row justify-content-between mrAll-0">
                    <div class="col-md-6 pAll-0">
                        <div class="event-block">
                            <div class="field field-name-field-volunteer-block field-type-entityreference field-label-hidden">
                                <div class="field-items">
                                    <div class="field-item even">
                                        <div class="block entity entity-bean bean-become-a-volunteer">
                                            <div class="block-bg" style="background:url(<?= PSYEM_ASSETS . '/images/volunteer.png' ?>);"></div>
                                            <div class="block-wrapper">
                                                <h2 class="block-title">
                                                    <?= __('Become a Volunteer', 'psyeventsmanager') ?>
                                                </h2>
                                                <div class="block-text">
                                                    <?php
                                                    $tText = 'KLF is always looking for dedicated volunteers who are passionate about fighting women’s cancers or who simply want to assist the local community';
                                                    esc_html_e($tText, 'psyeventsmanager');
                                                    ?>.                                                    
                                                </div>
                                                <a href="<?= (isset($MasterSettings['psyem_volunteer_url']) && !empty($MasterSettings['psyem_volunteer_url'])) ? $MasterSettings['psyem_volunteer_url'] : 'javascript:void(0);' ?>" class="linkcontact text-decoration-none">
                                                    <?= __('Contact Us', 'psyeventsmanager') ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 pAll-0">
                        <div class="event-block purpleColor">
                            <div class="field field-name-field-volunteer-block field-type-entityreference field-label-hidden">
                                <div class="field-items">
                                    <div class="field-item even">
                                        <div class="block entity entity-bean bean-become-a-volunteer">
                                            <div class="block-bg" style="background:url(<?= PSYEM_ASSETS . '/images/block.png' ?>);"></div>
                                            <div class="block-wrapper">
                                                <h2 class="block-title text-white">
                                                    <?= __('Get the Latest Update', 'psyeventsmanager') ?>
                                                </h2>
                                                <div class="block-text">
                                                    <?= __('KLF is always looking for dedicated volunteers who are passionate about fighting women’s cancers or who simply want to assist the local community', 'psyeventsmanager') ?>
                                                </div>
                                                <a href="<?= (isset($MasterSettings['psyem_contact_us_url']) && !empty($MasterSettings['psyem_contact_us_url'])) ? $MasterSettings['psyem_contact_us_url'] : 'javascript:void(0);' ?>" class="linkcontact text-white text-decoration-none" style="color:#fff !important;">
                                                    <?= __('Subscribe', 'psyeventsmanager') ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="paddSection pt-0 pb-0 sponsor">
                <div class="row justify-content-between mrAll-0">
                    <div class="col-md-12 pAll-0">
                        <div class="event-block-large" style="background:url(<?= PSYEM_ASSETS . '/images/about-us_1.jpg' ?>);">
                            <div class="block-wrapper">
                                <h2 class="block-title text-white">
                                    <?= __('Become a Sponsor', 'psyeventsmanager') ?>
                                </h2>
                                <div class="block-text">
                                    <?= __('All our sponsorship opportunities include a range of attractive benefits', 'psyeventsmanager') ?>
                                </div>
                                <a href="<?= (isset($MasterSettings['psyem_sponsor_url']) && !empty($MasterSettings['psyem_sponsor_url'])) ? $MasterSettings['psyem_sponsor_url'] : 'javascript:void(0);' ?>" class="linksponsor">
                                    <?= __('More', 'psyeventsmanager') ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <?php wp_link_pages(); ?>

        <?php if (has_tag()) : ?>
            <div class="post-tags">
                <?php the_tags('<span class="tag-links">' . esc_html__('Tagged ', 'psyeventsmanager'), ', ', '</span>'); ?>
            </div>
        <?php endif; ?>
    </main>
<?php
endwhile;
get_footer();
