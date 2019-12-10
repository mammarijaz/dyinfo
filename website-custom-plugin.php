<?php
/**
 * Plugin Name: Website Custom Plugin
 * Plugin URI:
 * Description: A Custom Plugin as per your requirement
 * Version: 1.0.0
 * Author: Dino Bartolome
 * License: GPL2
 */
define('WCP_PLUGIN_VERSION', '1.0.0');
define('WCP_PLUGIN_DOMAIN', 'website-custom-plugin');
define('WCP_PLUGIN_URL', WP_PLUGIN_URL . '/website-custom-plugin');
// make it for 170 instead of 200 for frontend charts
//define( 'WCP_TOTAL_SCORE_OFFSET_FOR_PERCENT', 30); 

include_once(dirname(__FILE__) . "/functions.php");
include_once(dirname(__FILE__) . "/WCP/Common/Signup/Controller.php");
include_once(dirname(__FILE__) . "/WCP/Common/School/Controller.php");
include_once(dirname(__FILE__) . "/WCP/Common/Teacher/Controller.php");
include_once(dirname(__FILE__) . "/WCP/Common/Student/Controller.php");
include_once(dirname(__FILE__) . "/WCP/Common/Classes/Controller.php");

## Dashboard controller
include_once(dirname(__FILE__) . "/WCP/FrontEnd/Dashboard/Controller.php");

## Class Room Controller
include_once(dirname(__FILE__) . "/WCP/FrontEnd/Classes/Controller.php");
include_once(dirname(__FILE__) . "/WCP/FrontEnd/StudentClasses/Controller.php");
include_once(dirname(__FILE__) . "/WCP/BackEnd/School/Controller.php");
include_once(dirname(__FILE__) . "/WCP/FrontEnd/Signup/Controller.php");
include_once(dirname(__FILE__) . "/WCP/FrontEnd/Student/Controller.php");


global $wcp_db_version;
$wcp_db_version = '1.0';


add_action('wp_enqueue_scripts',"wcp_scripts");
function wcp_scripts(){

   /* wp_register_style('wcp-datatable', '//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css');

    wp_register_script('wcp-datatable', '//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js', false);*/
    if(!is_admin()){
    wp_register_style('wcp-custom-css', WCP_PLUGIN_URL . '/assets/css/wcp-custom.css');
    wp_register_style('wcp-datatable-css', 'https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css');
    wp_enqueue_style('wcp-font-roboto', 'https://fonts.googleapis.com/css?family=Roboto:400,500,700&display=swap');
    wp_register_script('wcp-datatable-js', 'https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js',array( 'jquery' ));
    wp_enqueue_script("wcp-datatable-js");

    wp_enqueue_style("wcp-datatable-css");
    wp_enqueue_style("wcp-custom-css");
    
    wp_register_script('wcp-common', WCP_PLUGIN_URL . '/WCP/Common/common.js', false);
    
    }
}

/**
 * ON Wordpress plugin activation
 * */
function wcp_install()
{

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    global $wpdb;
    global $wcp_db_version;
    $charset_collate = $wpdb->get_charset_collate();

    // school table
    $sql = "CREATE TABLE IF NOT EXISTS wcp_schools (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        school_name varchar(70) NOT NULL,
        school_phone varchar(30) NOT NULL,
        school_address varchar(250) DEFAULT '' NOT NULL,
        school_city varchar(50) NOT NULL,
        school_state varchar(70) NOT NULL,
        school_zipcode varchar(30) NOT NULL,
        school_country varchar(60) NOT NULL,
        wp_user_id mediumint(9) NOT NULL,
        is_deleted INT(11) DEFAULT '0' NOT NULL,
        created_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        updated_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    dbDelta($sql);

    // teacher table
    $SchoolSql = "CREATE TABLE IF NOT EXISTS wcp_teachers (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        full_name varchar(70) NOT NULL,
        school_id mediumint(9) NOT NULL,
        wp_user_id mediumint(9) NOT NULL,
        is_deleted INT(11) DEFAULT '0' NOT NULL,
        created_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        updated_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    dbDelta($SchoolSql);

    // student table.
    $studentSql = "CREATE TABLE IF NOT EXISTS wcp_students (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        full_name varchar(70) NOT NULL,
        school_id mediumint(9) NOT NULL,
        teacher_id mediumint(9) NOT NULL,
        wp_user_id mediumint(9) NOT NULL,
        is_deleted INT(11) DEFAULT '0' NOT NULL,
        created_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        updated_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    dbDelta($studentSql);

    // student table.
    $class_room = "CREATE TABLE IF NOT EXISTS wcp_class_room (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        class_room_name varchar(70) NOT NULL,
        school_id mediumint(9) NOT NULL,
        teacher_id mediumint(9) NOT NULL,
        class_room_purpose varchar(250) NOT NULL,
        class_room_descrption varchar(250),
        class_in_a_week INT(2),
        class_duration varchar (50),

        class_sun_start_time time DEFAULT '00:00:00',
        class_sun_end_time time DEFAULT '00:00:00',
        
        class_mon_start_time time DEFAULT '00:00:00',
        class_mon_end_time time DEFAULT '00:00:00',

        class_tue_start_time time DEFAULT '00:00:00',
        class_tue_end_time time DEFAULT '00:00:00',

        class_wed_start_time time DEFAULT '00:00:00',
        class_wed_end_time time DEFAULT '00:00:00',

        class_thu_start_time time DEFAULT '00:00:00',
        class_thu_end_time time DEFAULT '00:00:00',

        class_fri_start_time time DEFAULT '00:00:00',
        class_fri_end_time time DEFAULT '00:00:00',

        class_sat_start_time time DEFAULT '00:00:00',
        class_sat_end_time time DEFAULT '00:00:00',
        
        class_start_date date DEFAULT '0000-00-00',
        class_end_date date DEFAULT '0000-00-00',


        is_deleted INT(11) DEFAULT '0' NOT NULL,
        created_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        updated_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    dbDelta($class_room);


    // Class enrolment.
    $studentSql = "CREATE TABLE IF NOT EXISTS wcp_class_enrolment (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        class_room_id mediumint(9) NOT NULL,
        wp_user_id mediumint(9) NOT NULL,
        is_deleted INT(11) DEFAULT '0' NOT NULL,
        created_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        updated_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    dbDelta($studentSql);

    add_option('wcp_db_version', $wcp_db_version);
}

register_activation_hook(__FILE__, 'wcp_install');

function add_roles_on_wcp_activation()
{
    add_role('wcp_school', 'School', array('read' => true, 'edit_posts' => true));
    add_role('wcp_teacher', 'Teacher', array('read' => true, 'edit_posts' => true));
    add_role('wcp_student', 'Student', array('read' => true, 'edit_posts' => true));
}

register_activation_hook(__FILE__, 'add_roles_on_wcp_activation');


/*Admin Menus*/
add_action('admin_menu', 'wcp_admin_menu');
function wcp_admin_menu()
{
    add_menu_page('Schools', 'Schools', 'manage_options', 'wcp-schools', 'wcp_schools_html');
    /*  add_submenu_page( '', 'Questions', 'Questions', 'manage_options', 'wcp-questions', 'wcp_questions_admin_html');*/

}

function wcp_schools_html()
{
    echo do_shortcode("[wcp_schools_table]");
}
/*End Admin Menus*/

function wcp_ajaxurl()
{
    echo '<script type="text/javascript">
           var ajaxurl  = "' . admin_url('admin-ajax.php') . '";
         </script>';
}

add_action('wp_head', 'wcp_ajaxurl');

function wcp_exception_handler($exception)
{
    echo "<h2 style='background: red; color:white;'> Uncaught exception: ", $exception->getMessage(), "\n </h2>";
    echo 'error';
}

set_exception_handler('wcp_exception_handler');

function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting, so let it fall
        // through to the standard PHP error handler
        return false;
    }

    switch ($errno) {
        case E_USER_ERROR:
            echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
            echo "  Fatal error on line $errline in file $errfile";
            echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
            echo "Aborting...<br />\n";
            exit(1);
            break;

        case E_USER_WARNING:
            echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
            break;

        case E_USER_NOTICE:
            echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
            break;

        default:
            echo "Unknown error type: [$errno] $errstr";
            echo "  \n ";
            echo '  at file -> ' . $errfile;
            echo "  \n ";
            echo '  at line -> ' . $errline;
            echo "  \n ";
            echo '  No -> ' . $errno;
            echo "  \n ";
            break;
    }

    /* Don't execute PHP internal error handler */
    return true;
}

$old_error_handler = set_error_handler("myErrorHandler");

function admin_default_page($redirect_to, $request, $user)
{
    $redirectionPage = '/wp-admin/';
    if (is_array($user->roles)) {
        if (in_array('wcp_school', $user->roles)) {
            $redirectionPage = '/school-dashboard/';
        } else if (in_array('wcp_teacher', $user->roles)) {
            $redirectionPage = '/teacher-dashboard/';
        } else if (in_array('wcp_student', $user->roles)) {
            $redirectionPage = '/student-dashboard/';
        }
        
        //Redirect to BP Dashboard
        $redirectionPage = '/members/me/dashboard/';

        return home_url($redirectionPage);
    }
}

add_filter('login_redirect', 'admin_default_page', 1000000000, 3);


// Change Register link Top menu
add_filter('wplms_buddypress_registration_link', function($url){
    return site_url("signup-now");
}, 1 );

// Update dashboard Page according to User role

add_action("bp_before_dashboard_body" ,  "wcp_dashboard_content");
function wcp_dashboard_content(){
    $user = wp_get_current_user();
    if (in_array('wcp_school', $user->roles) || in_array('wcp_school', $user->roles) || in_array('wcp_teacher', $user->roles) || in_array('wcp_student', $user->roles) || in_array('wcp_student', $user->roles) ) {
        echo "<div class='col-sm-12'>";
        echo '<div class="dash-widget">';
        
        if (in_array('wcp_school', $user->roles)) {
            echo do_shortcode("[wcp_school_admin__dashbaord]");
        } else if (in_array('wcp_teacher', $user->roles)) {
            //echo do_shortcode("[wcp_teacher__dashbaord]");
        } else if (in_array('wcp_student', $user->roles) || in_array('student', $user->roles)) {
            echo do_shortcode("[wcp_student__dashbaord]");
        }
        echo "</div>";
        echo "</div>";
    }
}