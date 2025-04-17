<?php ob_start(); ?>
<?php
$REQData                 = (isset($_GET) && !empty($_GET)) ? $_GET : [];
$searchq                 = (isset($REQData['search_key'])) ? $REQData['search_key'] : '';
$searchCat               = (isset($REQData['search_cat'])) ? $REQData['search_cat'] : 0;
$cpage                   = (isset($REQData['cpage'])) ? $REQData['cpage'] : '';

$REQData['limit']        = 20;
$REQData['search_key']   = $searchq;
$REQData['search_cat']   = $searchCat;
$REQData['cpage']        = $cpage;
$REQData['post_type']    = 'psyem-knowledges';
$REQData['meta_prefix']  = '';
$REQData['taxonomy']     = 'psyem-knowledges-category';

$AllPostsData            = psyem_GetAllListingPosts($REQData);
$AllPosts                = @$AllPostsData['data'];
$PaginationData          = @$AllPostsData['pagination'];
$AllNewsCategories       = psyem_GetAllCategoriesWithPosts('psyem-knowledges-category', true);
?>

<style>
    .site-main {
        overflow: hidden;
        max-width: 100% !important;
    }
</style>

<div class="psyemListingCont" style="display: none;">
    <div class="searchSection">
        <div class="container-fuild">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-6">
                    <form action="" method="get" class="psyemPostListSearchForm">
                        <input type="submit" value="" class="search-submit">
                        <input type="hidden" name="search_cat" value="" class="search_cat">
                        <input type="search" name="search_key" class="search-text" <?= (!empty($searchq)) ? 'autofocus="autofocus"' : '' ?> placeholder="<?= __('Search', 'psyeventsmanager') ?>..." autocomplete="off" value="<?= $searchq ?>">
                    </form>
                </div>
                <div class="col-md-6">
                    <?php if (!empty($AllNewsCategories) && is_array($AllNewsCategories) && count($AllNewsCategories) > 0): ?>
                        <ul class="categoryTabs">
                            <?php foreach ($AllNewsCategories as $ccatid => $ccatInfo): ?>
                                <li class="psyemShowCatCont" data-term="<?= @$ccatInfo['term_id'] ?>">
                                    <img src="<?= @$ccatInfo['term_image'] ?>" alt="<?= @$ccatInfo['term_name'] ?>" class="psyemCatImg rounded-circle" style="width: 30px;" />
                                    <a href="javascript:void(0);" class="<?=($searchCat == @$ccatInfo['term_id']) ? 'actual-active': ''?>"> 
                                        <?= @$ccatInfo['term_name'] ?> (<?= @$ccatInfo['term_count'] ?>)
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            <li class="psyemShowAllCats">
                                <a href="javascript:void(0);">
                                    <?= __('View All', 'psyeventsmanager') ?>
                                </a>
                            </li>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="knowledgeHubSection secondSectionFold">
        <div class="row margin0 w-100">
            <?php if (!empty($AllPosts) && is_array($AllPosts)): $pcount = 0; ?>
                <?php foreach ($AllPosts as $cpostid => $cpost): $pcount++; ?>
                    <?php if (($pcount == 1)  || ($pcount == 2)) { ?>
                        <div class="col-md-12 col-lg-6 padding0">
                            <div class="row margin0">
                                <div class="col-6 col-sm-6 col-md-6 padding0">
                                    <div class="contentLeftSide">
                                        <h3><?= @$cpost['title'] ?></h3>
                                        <a href="<?= @$cpost['link'] ?>" class="btnMore">
                                            <?= __('More', 'psyeventsmanager') ?>
                                            <span>
                                                <svg aria-hidden="true" class="e-font-icon-svg e-fas-play-circle" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm115.7 272l-176 101c-15.8 8.8-35.7-2.5-35.7-21V152c0-18.4 19.8-29.8 35.7-21l176 107c16.4 9.2 16.4 32.9 0 42z"></path>
                                                </svg>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 padding0">
                                    <div class="leftImageSide">
                                        <div class="imageInnerSide bgImageknow" style="background: url(<?= @$cpost['image'] ?>) no-repeat center;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="col-md-12 col-lg-6 padding0">
                            <div class="row margin0 imageOrderChange">
                                <div class="col-6 col-sm-6 col-md-6 padding0">
                                    <div class="leftImageSide">
                                        <div class="imageInnerSide bgImageknow" style="background: url(<?= @$cpost['image'] ?>) no-repeat center;"></div>
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6 padding0">
                                    <div class="contentLeftSide">
                                        <h3><?= @$cpost['title'] ?></h3>
                                        <a href="<?= @$cpost['link'] ?>" class="btnMore">
                                            <?= __('More', 'psyeventsmanager') ?>
                                            <span>
                                                <svg aria-hidden="true" class="e-font-icon-svg e-fas-play-circle" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm115.7 272l-176 101c-15.8 8.8-35.7-2.5-35.7-21V152c0-18.4 19.8-29.8 35.7-21l176 107c16.4 9.2 16.4 32.9 0 42z"></path>
                                                </svg>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        if (($pcount == 4)) {
                            $pcount = 0;
                        } ?>
                    <?php } ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-md-12 text-center pAll-0">
                    <div class="alert alert-danger">
                        <?= __('No records found.', 'psyeventsmanager') ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row text-center mt-4 mb-4">
        <div class="col-md-12 text-center">
            <div class="psyPagination page_nav pagination" style="justify-content: normal; margin: 0px;">
                <?php
                echo paginate_links(array(
                    'base'      => add_query_arg('cpage', '%#%'),
                    'prev_text' => __('<<'),
                    'next_text' => __('>>'),
                    'total'     => @$PaginationData['total_page']  ?? 0,
                    'current'   => max(1, get_query_var('cpage'))
                ));
                ?>
            </div>
        </div>
    </div>
</div>
<?php return ob_get_clean(); ?>