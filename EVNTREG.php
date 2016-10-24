<?php
/*
Plugin Name: Event Registration
Plugin URI: http://www.wpeventregister.com
Description: This wordpress plugin is designed to run on a Wordpress webpage and provide registration for an event or class. It allows you to capture the registering persons contact information to a database and provides an association to an events database. It provides the ability to send the register to either a Paypal, Google Pay, or Authorize.net online payment site for online collection of event fees. Detailed payment management system to track and record event payments. Reporting features provide a list of events, list of attendees, and excel export. 
Version: 6.02.02
Author: David Fleming - Edge Technology Consulting
Author URI: http://www.wpeventregister.com
*/
/*  Copyright 2008 - 2016  DAVID_FLEMING  (email : support@wpeventregister.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
global $evr_date_format, $evr_ver, $wpdb;
$evr_date_format = "M j,Y";
$evr_ver = "6.02.02";

if( ! class_exists( 'wp_event_registration_Updater' ) ){
	include_once( plugin_dir_path( __FILE__ ) . 'wp_event_registration_updater.php' );
}

$updater = new wp_event_registration_Updater ( __FILE__ );
$updater->set_username( 'avdude' );
$updater->set_repository( 'event-registration' );
/*
	$updater->authorize( 'abcdefghijk1234567890' ); // Your auth code goes here for private repos
*/
$updater->initialize();

/**
 * To change date format in event listing display
 * Tuesday, Jan 23, 2011  -  "l, M j,Y"
 * January 23, 2011       -  "F j,Y"
 * Jan 23, 2011  -  "M j,Y"
 * 01/12/2011 (December 1, 2011) - "d/m/Y"
 * 
 * Month:
 * F - A full textual representation of a month, such as January or March January through December
 * m - Numeric representation of a month, with leading zeros 01 through 12
 * M - A short textual representation of a month, three letters Jan through Dec 
 * n - Numeric representation of a month, without leading zeros 1 through 12 
 * 
 * Day:
 * d - Day of the month, 2 digits with leading zeros 01 to 31
 * j - Day of the month without leading zeros 1 to 31
 * D - A textual representation of a day, three letters Mon through Sun 
 * l - (lowercase 'L') A full textual representation of the day of the week Sunday through Saturday 
 * 
 * Year:
 * Y - A full numeric representation of a year, 4 digits Examples: 1999 or 2003
 * y - A two digit representation of a year Examples: 99 or 03 
 * 
 */
/*********************************   ERROR REPORTING   ********************************/
if (get_option('evr_error_on')=="true"){
   error_reporting(E_ALL ^ E_NOTICE); 
}

function evr_save_error(){
    update_option('plugin_error',  ob_get_contents());
}
/*********************************   PATH VARIABLES   ********************************/
//Define path variables
define("EVR_PLUGINPATH", "/" . plugin_basename(dirname(__file__)) . "/");
define("EVR_PLUGINFULLURL", WP_PLUGIN_URL . EVR_PLUGINPATH);
//
//
//
/*********************************   DEPENDENCIES   ********************************/
//
require ("evr_admin_menu.php"); //holds function used in the admin menu
require ("evr_support_functions.php"); //holds functions used throughout the plugin
require ("evr_content.php"); //holds functions that replaces the content in main page
require ("evr_install.php"); //holds functions that install options and databases
require ("evr_company.php"); //holds function that has form and array posting for company
require ("evr_admin_event.php"); //holds function that creates/edits/manages event
require ("pagination.class.php"); //holds function that does paging on admin listings
require ("evr_admin_questions.php"); //holds function for the admin questions page
require ("evr_admin_category.php"); //holds function for the admin category page
require ("evr_admin_attendee.php"); //holds functions for the admin attendee management
require ("evr_admin_payments.php"); //holds function for event payments and admin payment management
require ("evr_public_registration.php"); //holds functions that display the registration page forms
require ("paypal.class.php"); //used for paypal IPN
require ("evr_ipn.php"); //used for paypal IPN
require ("evr_calendar.php"); //holds functions for calendar page
require ("evr_clean_db.php");
require ("evr_three_cal.php");
require ("evr_widgets.php");    // Class that holds new Widgets
require ("evr_attendee_widget.php");
require ("incl/settings.php");//for new admin menu
require ( "incl/evr_payfast_itn.php" );
include ( "evr_excel.php" );
//add new attendee widget
//require ('evr_payment_gateways.php');//used for payment gateways feature.
//require ("evrtest.php");
//require ("evr_pdf.php"); //creates pdf of reg details
//
//
/*********************************   HOOKS   ********************************/
//Install/Update Tables when plugin is activated
register_activation_hook(__file__, 'evr_install');
## uncomment the next line to completely remove the plugin including all data files when deactivated
//register_deactivation_hook( __FILE__, 'evr_uninstall' );
//
/*********************************   ACTIONS   ********************************/
//
add_action('activated_plugin','evr_save_error');
add_action('init', 'evr_init');
add_action('admin_menu', 'evr_admin_menu');
//add_action('plugins_loaded', 'evr_widgets');//replaced with new evr_widgets
//admin header
add_action('admin_head','evr_load_tiny_mce');
add_action('admin_head', 'evr_admin_header');
add_action('admin_print_styles', 'evr_admin_css_all_page');
add_action('admin_print_scripts', 'evr_admin_scripts_all_page');
//admin footer
add_action('admin_footer', 'evr_footer_text');
//public header
add_action('wp_head', 'evr_public_header');
add_action('wp_print_styles', 'evr_public_stylesheets');
add_action('wp_print_scripts', 'evr_public_scripts');
//add to wordpress dashboard
add_action('wp_dashboard_setup','evr_dashboard_upcomingevents');
add_action( 'widgets_init', 'evr_widgets' );//new sidebar widget
//
//
/*********************************   FILTERS   ********************************/
//
// Add a settings link to the Plugins page, so people can go straight from the plugin page to the settings page.
add_filter('plugin_action_links', 'evr_quick_action', 10, 2);
add_filter('the_content', 'evr_content_replace');
add_filter('the_content', 'evr_calendar_replace');
add_filter('the_content', 'evr_mini_cal_calendar_replace');
//add_filter('the_content', 'evr_rotator_replace');
add_filter('the_content', 'evr_upcoming_event_list');
//
/*********************************   SHORTCODES  *****************************/
//
add_shortcode('EVR_PAYMENT', 'evr_payment_page');
add_shortcode('EVR_CALENDAR', 'evr_calendar_page');
add_shortcode('EVR_SINGLE', 'evr_single_event');
add_shortcode('EVR_CATEGORY', 'evr_by_category');
add_shortcode('EVR_ATTENDEE', 'evr_attendee_short');
//Added shortcode [EVR_EVENTS] for events page to replace {EVRREGIS}
add_shortcode( 'EVR_EVENTS', 'evr_events_page' );
function evr_events_page($atts) {
    extract(shortcode_atts(array('event_id' => 'No ID Supplied'), $atts));
	$id = "{$event_id}";
	ob_start();
    evr_registration_main($id);
    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
}
//Function to search old filter for events and replace with new shortcode


//
/*********************************   STARTUP FUNCTIONS   ********************************/
//
function evr_init(){
    //register admin scripts
    wp_register_script($handle = 'evr_admin_script', $src = plugins_url('/scripts/evr.js', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
    wp_register_script($handle = 'evr_fancy', $src = plugins_url('/scripts/fancybox/jquery.fancybox-1.3.4.pack.js', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
    wp_register_script($handle = 'evr_tab_script', $src = plugins_url('/scripts/evr_tabs.js', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
    wp_register_script($handle = 'evr_tooltip_script', $src = plugins_url('/js/jquery.tooltip.js', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
    //register public scripts
    wp_register_script($handle = 'evr_public_script', $src = plugins_url('/evr_public_script.js', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
    wp_register_script($handle = 'evr_public_fancy', $src = plugins_url('/scripts/fancybox/jquery.fancybox-1.3.4.pack.js', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
    wp_register_script($handle = 'evr_public_easing', $src = plugins_url('/scripts/fancybox/jquery.easing-1.3.pack.js', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
    wp_register_script($handle = 'evr_public_mouswheel', $src = plugins_url('/scripts/fancybox/jquery.mousewheel-3.0.4.pack.js', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
    wp_register_script($handle = 'evr_public_colorbox', $src = plugins_url('/colorbox/js/jquery.colorbox.js', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
    //if (!is_admin()) {wp_enqueue_script('jquery');}
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-sortable'); 
    wp_enqueue_script('jquery-ui-draggable'); 
    wp_enqueue_script('jquery-ui-droppable');
    wp_enqueue_script('jquery-ui-selectable');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('thickbox'); 
    wp_enqueue_style('thickbox');
    wp_enqueue_script(array('tiny_mce','editor','editor-functions', 'thickbox', 'media-upload'));
    
    //added for new setup
    wp_enqueue_style('jquery-ui', plugins_url('/css/jquery-ui.css', __FILE__));
            wp_enqueue_script('jquery-ui-core');
    		wp_enqueue_script('jquery-ui-tabs');
            wp_enqueue_script('jquery');
    #Text Domain support for other languages
    load_plugin_textdomain('evr_language', false, dirname(plugin_basename(__file__)).'/lang/');
    if(empty($company_options['company_name'])) {
            evr_createCompanyArray();
        }

}
//
function evr_load_tiny_mce() {
    //for help with this http://dannyvankooten.com/450/tinymce-wysiwyg-editor-in-wordpress-plugin/
   global $wp_version;
      //for older versions we need this script.
   if (!version_compare($wp_version, '3.2', '>=')) {
       if (function_exists('wp_tiny_mce_preload_dialogs')) {
           add_action('admin_print_footer_scripts', 'wp_tiny_mce_preload_dialogs');
       }
    //wp_tiny_mce( false ); // true gives you a stripped down version of the editor
    wp_tiny_mce( false , array( "editor_selector" => "edit_class", 
    'height' => 200,
    'plugins' => 'inlinepopups,wpdialogs,wplink,media,wpeditimage,wpgallery,paste,tabfocus',        
    'forced_root_block' => false,        
    'force_br_newlines' => true,        
    'force_p_newlines' => false,        
    'convert_newlines_to_brs' => true));
  	}
}
//
//
/*********************************   ADMIN HEAD   ********************************/
//              
//function to enqueue styles in admin pages
function evr_admin_css_all_page() {
       wp_register_style($handle = 'evr_admin_css', $src = plugins_url('/evr_admin_style.css', __FILE__), $deps = array(), $ver = '6.01.00', $media = 'all');
       wp_register_style($handle = 'evr_fa_css', $src = plugins_url('/css/font-awesome.css', __FILE__), $deps = array(), $ver = '6.01.00', $media = 'all');
       wp_register_style($handle = 'evr_fancy_css', $src = plugins_url('/scripts/fancybox/jquery.fancybox-1.3.4.css', __FILE__), $deps = array(), $ver = '6.01.00', $media = 'all');
       wp_enqueue_style('evr_fancy_css');
       wp_enqueue_style('evr_fa_css');
       wp_enqueue_style('evr_admin_css');
       
       //added for new system
       wp_enqueue_style('jquery-ui', plugins_url('/css/jquery-ui.css', __FILE__));
}
//function to enqueue scripts in admin pages
function evr_admin_scripts_all_page() {
       wp_register_script($handle = 'evr_admin_script', $src = plugins_url('/scripts/evr.js', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
       wp_register_script($handle = 'evr_admin_fancy', $src = plugins_url('/scripts/fancybox/jquery.fancybox-1.3.4.pack.js', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
       wp_register_script($handle = 'evr_tab_script', $src = plugins_url('/scripts/evr_tabs.js', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
       wp_register_script($handle = 'evr_tooltip_script', $src = plugins_url('/js/jquery.tooltip.js', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
       wp_enqueue_script('evr_admin_script');
       wp_enqueue_script('evr_admin_fancy');
       wp_enqueue_script('evr_tab_script');
       wp_enqueue_script('evr_tooltip_script');  
      
        //added for new system
        wp_enqueue_script('jquery-ui-core');
    		wp_enqueue_script('jquery-ui-tabs');
            wp_enqueue_script('jquery');
       }
//function to load items to header of wordpress admin
function evr_admin_header(){
  //special code for header - should put all css in css admin and all script in script admin
}
/*********************************   END ADMIN HEAD   ********************************/
//
//
/*********************************   PUBLIC HEAD     ********************************/
//
#function to enqueue styles in public pages
function evr_public_stylesheets() {
    #register public styles
    wp_register_style($handle = 'evr_public', $src = plugins_url('/evr_public_style.css', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
    wp_register_style($handle = 'evr_calendar', $src = plugins_url('/evr_calendar.css', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
    wp_register_style($handle = 'evr_pop_style', $src = plugins_url('/evr_pop_style.css', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
    wp_register_style($handle = 'evr_fancy_style', $src = plugins_url('/scripts/fancybox/jquery.fancybox-1.3.4.css', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
    wp_register_style($handle = 'evr_colorbox_style', $src = plugins_url('/colorbox/css/colorbox.css', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
    wp_enqueue_style('evr_public');
    wp_enqueue_style('evr_calendar');
    wp_enqueue_style('evr_pop_style');
    wp_enqueue_style('evr_fancy_style');
    wp_enqueue_style('evr_colorbox_style');
    //wp_enqueue_style('thickbox');
}
#function to enqueue scripts in public pages
function evr_public_scripts() {
    wp_enqueue_script('evr_tooltip_script');    
    wp_enqueue_script('evr_public_script');
    wp_enqueue_script('evr_public_colorbox');
    //wp_enqueue_style('thickbox');
    wp_enqueue_script('evr_public_fancy');
    wp_enqueue_script('evr_public_easing');
    wp_enqueue_script('evr_public_mouswheel');
}
#function to load items to public pages of wordpress site
function evr_public_header(){
    echo '<!--Event Registration '.$evr_ver.'-->';

}
/*********************************   END PUBLIC HEAD   *****************************/
//
//
/*********************************   ADMIN MENUE   ********************************/
//
#function to load plugin admin menu to Admin sidebar
function evr_admin_menu(){
    global $evr_date_format, $evr_ver;
    $version = "EVNTRG_" . $evr_ver;
    $role = 'manage_options';
    #Create Admin Menus
    add_menu_page($version, $version, $role, __file__, 'evr_splash'); 
    add_submenu_page(__file__, 'Plugin Settings','<i class="fa fa-cogs"></i> '. __('Settings','evr_language'), $role, 'settings','evr_admin_company');
    add_submenu_page(__file__, 'Reports', '<i class="fa fa-file-excel-o"></i> '.__('Reports','evr_language'), $role, 'excel','evr_excel');
    add_submenu_page(__file__, 'Categories', '<i class="fa fa-sitemap"></i> '.__('Categories','evr_language'),$role, 'categories','evr_admin_categories');
    add_submenu_page(__file__, 'Manage Events', '<i class="fa fa-calendar"></i> '.__('Events','evr_language'), $role, 'events','evr_admin_events');
    add_submenu_page(__file__, 'Questions', '<i class="fa fa-question"></i><i class="fa fa-question"></i> '.__('Questions','evr_language'), $role, 'questions','evr_admin_questions');
    add_submenu_page(__file__, 'Manage Attendees', '<i class="fa fa-male"></i><i class="fa fa-female"></i> '.__('Attendees','evr_language'), $role, 'attendee','evr_attendee_admin');
    add_submenu_page(__file__, 'Manage Payments', '<i class="fa fa-bank"></i> '.__('Payments','evr_language'), $role, 'payments','evr_admin_payments');
    add_submenu_page(__file__, 'Register Plugin', '<i class="fa fa-paper-plane-o"></i> '.__('Register Plugin','evr_language'), $role, 'evr_register','evr_registration');
    if (get_option('evr_was_upgraded')== "Y") {add_submenu_page(__file__, 'Remove Old Data', __('Remove Old Data','evr_language'), $role, 'purge','evr_clean_old_db');}
    add_submenu_page(__file__, 'UnInstall Plugin', '<i class="fa fa-frown-o"></i> '.__('Uninstall','evr_language'), $role, 'uninstall','evr_remove_db_menu');

}
/*********************************   END ADMIN MENU   ********************************/
#function for testing functions
function evr_testing(){
    }
#Function to load widgets to the widgets menu
function evr_widgets(){
    register_widget( 'EVR_Widget_List_Events' );//new widget
    }

#Function to add custom tag to footer
function evr_footer_text(){
	echo "<p id='footer' style=\"text-align:center;\">Event Registration created by <a href='http://www.wpeventregister.com'>wpeventregister.com</a></p>";
} 
function get_evr_option($setting) {
	global $wpdb;
    $config_table = $wpdb->prefix . "evr_options";
	$option = $wpdb->get_var( $wpdb->prepare("SELECT evr_option_value FROM $config_table WHERE evr_option_name = %s", $setting ) );
	return maybe_unserialize( $option );
}

function evr_dashboard_link( $links ) {
    $url = get_admin_url() . 'admin.php?page=event-registration/EVNTREG.php';
    $settings_link = '<a href="' . $url . '">' . __('Dashboard', 'textdomain') . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
}
 
function evr_after_setup_theme() {
     add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'evr_dashboard_link');
}
add_action ('after_setup_theme', 'evr_after_setup_theme');
// add links
add_filter('plugin_row_meta', 'evr_addPluginPageLinks', 10, 2);

// links on the plugin page
function evr_addPluginPageLinks($links, $file){
  // current plugin ?
  if ($file == 'event-registration/EVNTREG.php'){
    //$links[] = '<a href="'.admin_url('options-general.php?page='.plugin_basename(__FILE__)).'">'.__('Settings', 'enlighter').'</a>';
    $links[] = '<a href="'.admin_url('admin.php?page='.plugin_basename(__FILE__)).'">' . __('Dashboard', 'textdomain') . '</a>';
    $links[] = '<a href="https://www.facebook.com/EventRegistration">'.__('Facebook', 'textdomain').'</a>';
  }
  
  return $links;
}
add_action( 'admin_enqueue_scripts', 'evr_color_picker_function' );

function evr_color_picker_function() {
   wp_enqueue_style( 'wp-color-picker' );
   wp_enqueue_script( 'script_handle', plugins_url('/scripts/evr.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}
add_action('wp_head', 'evr_style_incl_function', 10);

function evr_style_incl_function() {
    wp_enqueue_style( 'wp-color-picker' );
}
?>