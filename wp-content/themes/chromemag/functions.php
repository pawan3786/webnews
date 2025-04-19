<?php
/*This file is part of HardNews child theme.

All functions of this file will be loaded before of parent theme functions.
Learn more at https://codex.wordpress.org/Child_Themes.

Note: this function loads the parent stylesheet before, then child theme stylesheet
(leave it in place unless you know what you are doing.)
*/

function chromemag_enqueue_child_styles()
{
    $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
    $parent_style = 'chromenews-style';

    $fonts_url = 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700';
    wp_enqueue_style('chromemag-google-fonts', $fonts_url, array(), null);
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap' . $min . '.css');
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    wp_enqueue_style(
        'chromemag',
        get_stylesheet_directory_uri() . '/style.css',
        array('bootstrap', $parent_style),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'chromemag_enqueue_child_styles');

function chromemag_override_banner_advertisment_function()
{
    remove_action('chromenews_action_front_page_main_section', 'chromenews_front_page_main_section', 40);
    remove_action('chromenews_action_header_section', 'chromenews_header_section', 40);
}

add_action('wp_loaded', 'chromemag_override_banner_advertisment_function');


if (!function_exists('chromemag_header_section')) :
    /**
     * Banner Slider
     *
     * @since ChromeNews 1.0.0
     *
     */
    function chromemag_header_section()
    { ?>

        <header id="masthead" class="header-layout-side chromenews-header">
            <?php
            chromenews_get_block('layout-default', 'header');

            ?>

        </header>

        <!-- end slider-section -->
    <?php
    }
endif;
add_action('chromenews_action_header_section', 'chromemag_header_section', 40);

if (!function_exists('chromemag_front_page_main_section')) :
    /**
     * Banner Slider
     *
     * @since ChromeNews 1.0.0
     *
     */
    function chromemag_front_page_main_section()
    {
        $chromenews_enable_main_slider = chromenews_get_option('show_main_news_section');




        $chromenews_banner_layout = chromenews_get_option('select_main_banner_layout_section');
        $chromenews_layout_class = 'aft-banner-' . $chromenews_banner_layout;
        if ($chromenews_banner_layout == 'layout-aligned') {
            $chromenews_banner_layout = 'layout-tabbed';
            $chromenews_layout_class = 'aft-banner-' . $chromenews_banner_layout;
        }

        $chromenews_banner_background = chromenews_get_option('main_banner_background_section');

        $chromenews_banner_background_color = chromenews_get_option('select_main_banner_background_color');
        $chromenews_layout_class .= ' aft-banner-' . $chromenews_banner_background_color;

        $chromenews_main_banner_order = chromenews_get_option('select_main_banner_order_3');
        $chromenews_layout_class .= ' aft-banner-' . $chromenews_main_banner_order;

        $chromenews_main_banner_url = '';

        if (!empty($chromenews_banner_background)) {
            $chromenews_banner_background = absint($chromenews_banner_background);
            $chromenews_main_banner_url = wp_get_attachment_url($chromenews_banner_background);
            $chromenews_layout_class .= ' data-bg';
        }

    ?>



        <section class="aft-blocks aft-main-banner-section banner-carousel-1-wrap bg-fixed  chromenews-customizer <?php echo esc_attr($chromenews_layout_class); ?>" data-background="<?php echo esc_attr($chromenews_main_banner_url); ?>">


            <?php do_action('chromenews_action_banner_exclusive_posts'); ?>

            <?php if ($chromenews_enable_main_slider) : ?>
                <div class="container-wrapper">
                    <div class="aft-main-banner-wrapper">

                        <?php

                        if ($chromenews_banner_layout == 'layout-aligned') {
                            chromenews_get_block('main-layout-tabbed', 'banner');
                        } else {
                            $chromenews_banner_block = 'main-' . $chromenews_banner_layout;
                            chromenews_get_block($chromenews_banner_block, 'banner');
                        }

                        ?>
                    </div>
                </div>
            <?php endif; ?>

        </section>
<?php
    }
endif;
add_action('chromenews_action_front_page_main_section', 'chromemag_front_page_main_section', 40);


function chromemag_filter_default_theme_options($defaults)
{    $defaults['site_title_font_size'] = 56;
    $defaults['site_title_uppercase'] = false;
    $defaults['header_layout'] = 'header-layout-default';
    $defaults['main_navigation_custom_background_color'] = '#0B2048';
    $defaults['secondary_color'] = '#0A72DB';
    $defaults['text_over_secondary_color'] = '#ffffff';
    $defaults['site_title_font']      = 'Roboto:100,300,400,500,700';
    $defaults['primary_font']      = 'Roboto:100,300,400,500,700';
    $defaults['secondary_font']    = 'Roboto:100,300,400,500,700';
    $defaults['chromenews_section_title_font_size']    = 20;
    return $defaults;
}
add_filter('chromenews_filter_default_theme_options', 'chromemag_filter_default_theme_options', 1);

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function chromemag_customize_register($wp_customize)
{
    // $wp_customize->remove_control('trending_post_panel_section_title');
    // $wp_customize->remove_control('main_trending_news_section_title');
    // $wp_customize->remove_control('select_trending_post_filterby');
    // $wp_customize->remove_control('select_trending_post_category');
    // $wp_customize->remove_control('select_trending_post_tag');
    // $wp_customize->remove_control('trending_post_autoplay');

    /**
     * Option Panel
     *
     * @package ChromeNews
     */

    $chromenews_default = chromenews_get_default_theme_options();
    /**
     * Latest Post Section
     * */

    //section title
    $wp_customize->add_setting(
        'banner_latest_post_panel_section_title',
        array(
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        new ChromeNews_Section_Title(
            $wp_customize,
            'banner_latest_post_panel_section_title',
            array(
                'label' => esc_html__("Latest Section", 'chromemag'),
                'section' => 'frontpage_main_banner_section_settings',
                'priority' => 100,
                'active_callback' => function ($control) {
                    return (chromenews_main_banner_section_status($control)
                    &&
                chromenews_main_banner_layout_tabs_status($control)
                    );
                },
            )
        )
    );



    $wp_customize->add_setting(
        'main_latest_news_section_title',
        array(
            'default' => $chromenews_default['main_latest_news_section_title'],
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    $wp_customize->add_control(
        'main_latest_news_section_title',
        array(
            'label' => esc_html__('Section Title ', 'chromemag'),
            'section' => 'frontpage_main_banner_section_settings',
            'type' => 'text',
            'priority' => 100,
            'active_callback' => function ($control) {
                return (chromenews_main_banner_section_status($control)
                &&
                chromenews_main_banner_layout_tabs_status($control)
                );
            },

        )

    );

    // Setting - select_main_banner_section_mode.
    $wp_customize->add_setting(
        'select_banner_latest_post_filterby',
        array(
            'default' => $chromenews_default['select_banner_latest_post_filterby'],
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'chromenews_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'select_banner_latest_post_filterby',
        array(
            'label' => esc_html__('Filter Posts By', 'chromemag'),
            'section' => 'frontpage_main_banner_section_settings',
            'type' => 'select',
            'choices' => array(
                'cat' => esc_html__("Category", 'chromemag'),
                'tag' => esc_html__("Tag", 'chromemag'),

            ),
            'priority' => 100,
            'active_callback' => function ($control) {
                return (chromenews_main_banner_section_status($control)
                &&
                chromenews_main_banner_layout_tabs_status($control)
                );
            },
        )
    );



    // Setting - drop down category for slider.
    $wp_customize->add_setting(
        'select_banner_latest_post_category',
        array(
            'default' => $chromenews_default['select_banner_latest_post_category'],
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'absint',
        )
    );


    $wp_customize->add_control(new ChromeNews_Dropdown_Taxonomies_Control(
        $wp_customize,
        'select_banner_latest_post_category',
        array(
            'label' => esc_html__('Select Category', 'chromemag'),
            'section' => 'frontpage_main_banner_section_settings',
            'type' => 'dropdown-taxonomies',
            'taxonomy' => 'category',
            'priority' => 100,
            'active_callback' => function ($control) {
                return (chromenews_main_banner_section_status($control)
                    &&
                    chromenews_banner_latest_post_section_filterby_cat_status($control)
                    &&
                chromenews_main_banner_layout_tabs_status($control)
                );
            },

        )
    ));


    // Setting - drop down category for slider.
    $wp_customize->add_setting(
        'select_latest_post_tag',
        array(
            'default' => $chromenews_default['select_latest_post_tag'],
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'absint',
        )
    );


    $wp_customize->add_control(new ChromeNews_Dropdown_Taxonomies_Control(
        $wp_customize,
        'select_latest_post_tag',
        array(
            'label' => esc_html__('Select Tag', 'chromemag'),
            'section' => 'frontpage_main_banner_section_settings',
            'type' => 'dropdown-taxonomies',
            'taxonomy' => 'post_tag',
            'priority' => 100,
            'active_callback' => function ($control) {
                return (chromenews_main_banner_section_status($control)
                    &&
                    chromenews_banner_latest_post_section_filterby_tag_status($control)
                    &&
                chromenews_main_banner_layout_tabs_status($control)

                );
            },

        )
    ));


    /**
     * Popular Post Section
     * */

    //section title
    $wp_customize->add_setting(
        'popular_post_panel_section_title',
        array(
            'sanitize_callback' => 'sanitize_text_field',
        )
    );

    $wp_customize->add_control(
        new ChromeNews_Section_Title(
            $wp_customize,
            'popular_post_panel_section_title',
            array(
                'label' => esc_html__("Popular Section", 'chromemag'),
                'section' => 'frontpage_main_banner_section_settings',
                'priority' => 100,
                'active_callback' => function ($control) {
                    return (chromenews_main_banner_section_status($control)
                    &&
                chromenews_main_banner_layout_tabs_status($control)

                    );
                },
            )
        )
    );


    $wp_customize->add_setting(
        'main_popular_news_section_title',
        array(
            'default' => $chromenews_default['main_popular_news_section_title'],
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    $wp_customize->add_control(
        'main_popular_news_section_title',
        array(
            'label' => esc_html__('Section Title ', 'chromemag'),
            'section' => 'frontpage_main_banner_section_settings',
            'type' => 'text',
            'priority' => 100,
            'active_callback' => function ($control) {
                return (chromenews_main_banner_section_status($control)
                &&
                chromenews_main_banner_layout_tabs_status($control)
                );
            },

        )

    );

    // Setting - select_main_banner_section_mode.
    $wp_customize->add_setting(
        'select_popular_post_filterby',
        array(
            'default' => $chromenews_default['select_popular_post_filterby'],
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'chromenews_sanitize_select',
        )
    );

    $wp_customize->add_control(
        'select_popular_post_filterby',
        array(
            'label' => esc_html__('Filter Posts By', 'chromemag'),
            'section' => 'frontpage_main_banner_section_settings',
            'type' => 'select',
            'choices' => array(

                'tag' => esc_html__("Tag", 'chromemag'),
                'comment' => esc_html__("Comment Count", 'chromemag'),

            ),
            'priority' => 100,
            'active_callback' => function ($control) {
                return (chromenews_main_banner_section_status($control)
                &&
                chromenews_main_banner_layout_tabs_status($control)

                );
            },
        )
    );



    // Setting - drop down category for slider.
    $wp_customize->add_setting(
        'select_popular_post_category',
        array(
            'default' => $chromenews_default['select_popular_post_category'],
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'absint',
        )
    );


    $wp_customize->add_control(new ChromeNews_Dropdown_Taxonomies_Control(
        $wp_customize,
        'select_popular_post_category',
        array(
            'label' => esc_html__('Select Category', 'chromemag'),
            'section' => 'frontpage_main_banner_section_settings',
            'type' => 'dropdown-taxonomies',
            'taxonomy' => 'category',
            'priority' => 100,
            'active_callback' => function ($control) {
                return (chromenews_main_banner_section_status($control)
                    &&
                    chromenews_popular_post_section_filterby_cat_status($control)
                    &&
                chromenews_main_banner_layout_tabs_status($control)

                );
            },

        )
    ));

    // Setting - drop down category for slider.
    $wp_customize->add_setting(
        'select_popular_post_tag',
        array(
            'default' => $chromenews_default['select_popular_post_tag'],
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'absint',
        )
    );


    $wp_customize->add_control(new ChromeNews_Dropdown_Taxonomies_Control(
        $wp_customize,
        'select_popular_post_tag',
        array(
            'label' => esc_html__('Select Tag', 'chromemag'),
            'section' => 'frontpage_main_banner_section_settings',
            'type' => 'dropdown-taxonomies',
            'taxonomy' => 'post_tag',
            'priority' => 100,
            'active_callback' => function ($control) {
                return (chromenews_main_banner_section_status($control)
                    &&
                    chromenews_popular_post_section_filterby_tag_status($control)
                    &&
                chromenews_main_banner_layout_tabs_status($control)

                );
            },

        )
    ));
}
add_action('customize_register', 'chromemag_customize_register', 99999);

function chromenews_main_banner_layout_tabs_status($control)
    {

        if (
            'layout-aligned' == $control->manager->get_setting('select_main_banner_layout_section')->value()
            ) {
            return true;
        } else {
            return false;
        }

    }

    function chromenews_main_banner_layout_trending_status($control)
    {

        if (
            
                      
            ('layout-tiled-2' == $control->manager->get_setting('select_main_banner_layout_section')->value()) ||            
            ('layout-vertical' == $control->manager->get_setting('select_main_banner_layout_section')->value())
            ) {
            return true;
        } else {
            return false;
        }
        
    }

function chromemag_filter_custom_header_args($header_args)
{
    $header_args['default-image'] = get_stylesheet_directory_uri() . '/assets/img/default-header-image.jpeg';
    $header_args['default-text-color'] = 'ffffff';
    return $header_args;
}

add_filter('chromenews_custom_header_args', 'chromemag_filter_custom_header_args', 1);
