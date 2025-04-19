<div class="af-main-banner-tabbed-posts aft-posts-tabs-panel">
    <div class="section-wrapper">
        <div class="small-grid-style clearfix">
            <?php

            $dir = 'ltr';
            if (is_rtl()) {
                $dir = 'rtl';
            }
            $tab_id = 'aft-main-banner-latest-trending-popular';

            $latest_title = chromenews_get_option('main_latest_news_section_title');
            $latest_post_filterby = chromenews_get_option('select_banner_latest_post_filterby');
            $latest_category = 0;
            if ($latest_post_filterby == 'tag') {
                $latest_category = chromenews_get_option('select_latest_post_tag');
            } elseif($latest_post_filterby == 'cat') {
                $latest_category = chromenews_get_option('select_banner_latest_post_category');
            }

            $popular_title = chromenews_get_option('main_popular_news_section_title');
            $popular_post_filterby = chromenews_get_option('select_popular_post_filterby');
            $popular_category = 0;
            if ($popular_post_filterby == 'tag') {
                $popular_category = chromenews_get_option('select_popular_post_tag');
            } elseif ($popular_post_filterby == 'cat') {
                $popular_category = chromenews_get_option('select_popular_post_category');
            }

            
            chromenews_render_tabbed_posts($tab_id, $latest_title, $latest_post_filterby, $latest_category, $popular_title, $popular_post_filterby, $popular_category, 5);
            ?>
        </div>
    </div>
</div>
<!-- Editors Pick line END -->