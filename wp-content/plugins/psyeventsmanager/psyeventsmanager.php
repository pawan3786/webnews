<?php
/* 
* Plugin Name: PSY Events Manager
* Plugin URI: https://smtlabs.io
* Description: Custom Plugin for Events, Tickets, Sales and Orders Managements
* Author: Pawan Kumar
* Author URI: https://smtlabs.io
* Version: 1.0.0
* Text Domain: psyeventsmanager
* Domain Path: /languages
* Plugin Slug: psyeventsmanager
* Requires at least: 6.7
* Requires PHP: 8.0 
*/


if (!defined('ABSPATH')) {
    exit;
}
if (!defined('PSYEM_URL')) {
    define('PSYEM_URL', plugin_dir_url(__FILE__));
}
if (!defined('PSYEM_PATH')) {
    define('PSYEM_PATH', plugin_dir_path(__FILE__));
}
if (!defined('PSYEM_PREFIX')) {
    define('PSYEM_PREFIX', 'psyem_');
}
if (!defined('PSYEM_VERSION')) {
    define('PSYEM_VERSION', '1.0.0');
}
if (!defined('PSYEM_ASSETS_PATH')) {
    define('PSYEM_ASSETS_PATH', PSYEM_PATH . 'assets');
}
if (!defined('PSYEM_ASSETS')) {
    define('PSYEM_ASSETS', PSYEM_URL . 'assets');
}
if (!defined('PSYEM_PLUGINS')) {
    define('PSYEM_PLUGINS', PSYEM_URL . 'plugins');
}
/* @Plugin Initial setup codes BGN */
register_activation_hook(__FILE__,     array('psyemEventsManagerInitials',    'psyemEventsManagerInitial_checkDependency'));
register_activation_hook(__FILE__,     array('psyemEventsManagerInitials',    'psyemEventsManagerInitial_CreateAdminPages'));
register_deactivation_hook(__FILE__,   array('psyemEventsManagerInitials',    'psyemEventsManagerInitial_DeactivateCallback'));
register_uninstall_hook(__FILE__,      array('psyemEventsManagerInitials',    'psyemEventsManagerInitial_UninstallCallback'));
/* INCLUDE HELPERS */
require PSYEM_PATH . 'helpers/psyemHelper.php';
require PSYEM_PATH . 'helpers/psyemValidationsHelper.php';
require PSYEM_PATH . 'helpers/psyemStripeHelper.php';

/* EVENT MANAGER INITIALS BGN */
class psyemEventsManagerInitials
{
    public static function psyemEventsManagerInitial_checkDependency()
    {
        global $wpdb;
        ob_start();
        self::psyemEventsManagerInitial_CreateDbtables();
        self::psyemEventsManagerInitial_InsertDbTableData();
    }

    public static function psyemEventsManagerInitial_CreateDbtables()
    {
        global $wpdb;
    }

    public static function psyemEventsManagerInitial_InsertDbTableData()
    {
        global $wpdb;
        ob_clean();
    }

    public static function psyemEventsManagerInitial_CreateAdminPages()
    {
        global $wpdb;
        global $post;
        $user_id = get_current_user_id();
        global $user_ID;

        // create event list page
        if (!get_option('psyem_events_list_page_id')) :
            $psyemEventPageArr = array(
                'post_title'        => 'Events List',
                'post_content'      => '',
                'post_status'       => 'publish',
                'post_type'         => 'page',
                'post_name'         => 'psyem-events-list',
                'post_author'       => $user_id,
                'post_date'         => date('Y-m-d H:i:s')
            );
            $psyemEventPageID = wp_insert_post($psyemEventPageArr);
            psyem_updateOption('psyem_events_list_page_id', $psyemEventPageID);
        endif;
        // create event checkout page
        if (!get_option('psyem_event_checkout_page_id')) :
            $psyemEventCheckoutPageArr = array(
                'post_title'        => 'Event Checkout',
                'post_content'      => '',
                'post_status'       => 'publish',
                'post_type'         => 'page',
                'post_name'         => 'psyem-checkout',
                'post_author'       => $user_id,
                'post_date'         => date('Y-m-d H:i:s')
            );
            $psyemEventCheckoutPageID = wp_insert_post($psyemEventCheckoutPageArr);
            psyem_updateOption('psyem_event_checkout_page_id', $psyemEventCheckoutPageID);
        endif;
        // create event thankyou page
        if (!get_option('psyem_event_thankyou_page_id')) :
            $psyemEventThankyouPageArr = array(
                'post_title'        => 'Event Thank You',
                'post_content'      => '',
                'post_status'       => 'publish',
                'post_type'         => 'page',
                'post_name'         => 'psyem-thankyou',
                'post_author'       => $user_id,
                'post_date'         => date('Y-m-d H:i:s')
            );
            $psyemEventThankyouPageID = wp_insert_post($psyemEventThankyouPageArr);
            psyem_updateOption('psyem_event_thankyou_page_id', $psyemEventThankyouPageID);
        endif;
        // create event verify qr page
        if (!get_option('psyem_event_verifyqr_page_id')) :
            $psyemEventVerifyqrPageArr = array(
                'post_title'        => 'Verify Event QR',
                'post_content'      => '',
                'post_status'       => 'publish',
                'post_type'         => 'page',
                'post_name'         => 'psyem-verifyqr',
                'post_author'       => $user_id,
                'post_date'         => date('Y-m-d H:i:s')
            );
            $psyemEventVerifyqrPageID = wp_insert_post($psyemEventVerifyqrPageArr);
            psyem_updateOption('psyem_event_verifyqr_page_id', $psyemEventVerifyqrPageID);
        endif;
    }

    public static function psyemEventsManagerInitial_DeactivateCallback()
    {
        global $wpdb;
    }

    public static function psyemEventsManagerInitial_UninstallCallback()
    {
        global $wpdb;
        if (!defined('WP_UNINSTALL_PLUGIN')) {
            wp_die('You are not authorized to do this operation.');
        }
    }
}
$psyemEventsManagerInitials = new psyemEventsManagerInitials();
/* EVENT MANAGER INITIALS END */

/* EVENT MANAGER BGN */
class psyemEventsManager
{
    function __construct()
    {
        global $wpdb, $post;
        $this->psyem_InitEventsManagerActions();
    }

    function psyem_InitEventsManagerActions()
    {
        add_action('plugins_loaded',     array(&$this, PSYEM_PREFIX . 'loadTextdomain'));
    }

    function psyem_loadTextdomain()
    {
         load_plugin_textdomain('psyeventsmanager', false, dirname(plugin_basename(__FILE__)) . '/languages');       
    }
}
/* EVENT MANAGER END */
require 'admin/psyemAdmin.php';
require 'front/psyemFront.php';
$psyemEventsManager = new psyemEventsManager();
