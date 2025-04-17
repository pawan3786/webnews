<?php
/*This file is part of HardNews child theme.

All functions of this file will be loaded before of parent theme functions.
Learn more at https://codex.wordpress.org/Child_Themes.

Note: this function loads the parent stylesheet before, then child theme stylesheet
(leave it in place unless you know what you are doing.)
*/

function chromegrid_enqueue_child_styles()
{
    $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
    $parent_style = 'chromenews-style';
    
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap' . $min . '.css');
    wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
    wp_enqueue_style(
        'chromegrid',
        get_stylesheet_directory_uri() . '/style.css',
        array('bootstrap', $parent_style),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'chromegrid_enqueue_child_styles');


function chromegrid_setup(){
    // Set up the WordPress core custom background feature.
    add_theme_support('custom-background', apply_filters('chromenews_custom_background_args', array(
        'default-color' => 'fdf7f6',
        'default-image' => '',
    )));
    }
    add_action('after_setup_theme', 'chromegrid_setup');
    
    function chromegrid_filter_custom_header_args($header_args)
    {
        $header_args['default-image'] = get_stylesheet_directory_uri() . '/assets/img/default-header-image.jpeg';
        $header_args['default-text-color'] = 'ffffff';
        return $header_args;
    }
    
    add_filter('chromenews_custom_header_args', 'chromegrid_filter_custom_header_args', 1);

function chromegrid_filter_default_theme_options($defaults)
{   
    $defaults['select_main_banner_layout_section'] = 'layout-tiled-2';
    $defaults['show_featured_posts_section'] = 0;
    $defaults['aft_custom_title'] = __('Follow', 'chromegrid');
    $defaults['chromenews_section_title_font_size']    = 16;
    $defaults['global_excerpt_length']    = 10;
    $defaults['main_navigation_custom_background_color'] = '#FF3C10';
    $defaults['secondary_color'] = '#FF3C10';
    $defaults['text_over_secondary_color'] = '#ffffff';       
    $defaults['global_single_content_mode'] = 'single-content-mode-compact';       
    $defaults['single_show_tags_list'] = 0;       
    $defaults['archive_layout'] = 'archive-layout-grid';       
    return $defaults;
}
add_filter('chromenews_filter_default_theme_options', 'chromegrid_filter_default_theme_options', 1);