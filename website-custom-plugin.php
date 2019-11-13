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

## Dashboard controller
include_once(dirname(__FILE__) . "/WCP/FrontEnd/Dashboard/Controller.php");

## Class Room Controller
include_once(dirname(__FILE__) . "/WCP/FrontEnd/StudentClasses/WCP_FrontEnd_StudentClass_Controller.php");
include_once(dirname(__FILE__) . "/WCP/BackEnd/School/Controller.php");
include_once(dirname(__FILE__) . "/WCP/FrontEnd/Signup/Controller.php");


global $wcp_db_version;
$wcp_db_version = '1.0';


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


/**
 *
 * */

/*Admin Menus*/

add_action('admin_menu', 'wcp_admin_menu');
function wcp_admin_menu()
{
    add_menu_page('Schools', 'Schools', 'manage_options', 'wcp-schools', 'wcp_schools_html');
    /*  add_submenu_page( '', 'Questions', 'Questions', 'manage_options', 'wcp-questions', 'wcp_questions_admin_html');
      add_submenu_page( '', 'Answers', 'Answers', 'manage_options', 'wcp-answers', 'wcp_answers_admin_html');
      add_submenu_page( 'wcp-quizzes', 'Import Quiz ', 'Import Quiz', 'manage_options', 'wcp-import-questions', 'wcp_import_questions_admin_html');*/

}

function wcp_schools_html()
{
    echo do_shortcode("[wcp_schools_table]");
}/*
function wcp_questions_admin_html() {
    echo do_shortcode("[wcp_questions_table]");
}
function wcp_answers_admin_html() {
    echo do_shortcode("[wcp_answers_table]");
}
function wcp_import_questions_admin_html() {
    echo do_shortcode("[wcp_quizzes_import_table]");
}*/
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

//## School registration ajax request.
//add_action('wp_ajax_wcp_school_registration', 'school_registration_ajax_request__');
//add_action('wp_ajax_nopriv_wcp_school_registration', 'school_registration_ajax_request__');
//
//## school registration
//function school_registration_ajax_request__()
//{
//
//    print_r($_POST);
//    return;
//}


## School registration ajax request.
//add_action('wp_ajax_wcp_school_registration', school_registration_ajax_request());
//add_action('wp_ajax_nopriv_wcp_school_registration', school_registration_ajax_request());
//function school_registration_ajax_request()
//{
//    if ($_POST['action'] == 'wcp_school_registration') {
//        $response = null;
//        $wp_user_name = null;
//        if (!empty($_POST['school_admin_frist_name']) || !empty($_POST['school_admin_last_name'])) {
//            $wp_user_name = $_POST['school_admin_frist_name'] . $_POST['school_admin_last_name'];
//        } else {
//            $response = 'User name is missing in account details';
//        }
//
//        if (!empty($response)) {
//            echo $response;
//            exit;
//        }
//
//        ## if both password are not matched
//        if (empty($_POST['school_admin_password']) || empty($_POST['school_admin_confirm_password'])) {
//            $response = 'User password and confirm password should not be empty';
//        } else {
//            if ($_POST['school_admin_password'] <> $_POST['school_admin_confirm_password']) {
//                $response = 'Password and confirm password should be same';
//            }
//        }
//
//        if (!empty($response)) {
//            echo $response;
//            exit;
//        }
//
//        ## email check
//        if (empty($_POST['school_admin_email_address'])) {
//            $response = 'School admin email address is missing';
//        }
//
//
//        if (!empty($response)) {
//            echo $response;
//            exit;
//        }
//
//        ## then register school in database.
//
//        //$Model = new WCP_FrontEnd_Signup_Model();
//        $postData = [
//            'school_name' => $_POST['school_name'],
//            'school_address' => $_POST['school_address'],
//            'school_city' => $_POST['school_city'],
//            'school_state' => $_POST['school_state'],
//            'school_zipcode' => $_POST['school_zipcode'],
//            'school_country' => $_POST['school_coutry'],
//            'school_phone' => $_POST['school_phone'],
//        ];
//
//        ## school name is missing
//        if (empty($postData['school_name'])) {
//            exit ('School name is missing');
//        }
//
//        ## school phone is missing.
//        if (empty($postData['school_phone'])) {
//            exit($returnResponse = ('School phone is missing'));
//        }
//
//        ## school address
//        if (empty($postData['school_address'])) {
//            exit ('School address is missing');
//        }
//
//        ## school city
//        if (empty($postData['school_city'])) {
//            exit ('School city is missing');
//        }
//
//
//        ## school state
//        if (empty($postData['school_state'])) {
//            exit ('School state is missing');
//        }
//
//
//        ## school zipcode
//        if (empty($postData['school_zipcode'])) {
//            exit ('School Zip Code is missing');
//        }
//
//        ## school country
//        if (empty($postData['school_country'])) {
//            exit ('School country is missing');
//        }
//
//        ## school phone
//        if (empty($postData['school_phone'])) {
//            exit ('School phone is missing');
//        }
//        ## create wp user first.
//        $user_create_response = wp_create_user($wp_user_name, $_POST['school_admin_password'], $_POST['school_admin_email_address']);
//
//        if (is_wp_error($user_create_response)) {
//            $response = $user_create_response->get_error_message();
//        } else {
//            ## user role to the school
//            wp_update_user(array('ID' => $user_create_response, 'role' => 'wcp_school'));
//        }
//        if (!empty($response)) {
//            echo $response;
//            exit();
//        }
//
//        $postData['wp_user_id'] = $user_create_response;
//
//        global $wpdb;
//        $wpdb->insert('wp_wcp_schools', $postData);
//        if (!empty($wpdb->last_error)) {
//            echo $wpdb->last_error;
//            exit;
//        } else {
//            echo 'OK';
//            exit;
//        }
//
//    }
//}
//

## teacher registration ajax request.
//add_action('wp_ajax_teacher_sign_up', teacher_registration_ajax_request());
//add_action('wp_ajax_nopriv_teacher_sign_up', teacher_registration_ajax_request());
//function teacher_registration_ajax_request()
//{
//    if ($_POST['action'] == 'teacher_sign_up') {
//
//        $response = null;
//        $wp_user_name = null;
//        if (!empty($_POST['teacher_first_name']) || !empty($_POST['teacher_last_name'])) {
//            $wp_user_name = $_POST['teacher_first_name'] . $_POST['teacher_last_name'];
//        } else {
//            $response = 'User name is missing in account details';
//        }
//
//        if (!empty($response)) {
//            echo $response;
//            exit;
//        }
//
//        ## if both password are not matched
//        if (empty($_POST['teacher_password']) || empty($_POST['teacher_confirm_password'])) {
//            $response = 'User password and confirm password should not be empty';
//        } else {
//            if ($_POST['input_pass'] <> $_POST['input_con_pass']) {
//                $response = 'Password and confirm password should be same';
//            }
//        }
//
//        if (!empty($response)) {
//            echo $response;
//            exit;
//        }
//
//        ## email check
//        if (empty($_POST['teacher_email'])) {
//            $response = 'Teacher email address is missing';
//        }
//
//        if (empty($_POST['school_ref'])) {
//            $response = 'School not found';
//        }
//
//        if (!empty($response)) {
//            echo $response;
//            exit;
//        }
//
//
//        ## create wp user first.
//        $user_create_response = wp_create_user($wp_user_name, $_POST['teacher_password'], $_POST['teacher_email']);
//        if (is_wp_error($user_create_response)) {
//            $response = $user_create_response->get_error_message();
//        } else {
//            ## user role to the school
//            wp_update_user(array('ID' => $user_create_response, 'role' => 'wcp_teacher'));
//        }
//        if (!empty($response)) {
//            echo $response;
//            exit();
//        }
//
//        ## then register teacher in database.
//        $postData = [
//            'school_id' => $_POST['school_ref'],
//            //            'school_state' => $_POST['school_state'],
//            //            'school_zipcode' => $_POST['school_zipcode'],
//            //            'school_country' => $_POST['school_coutry'],
//            //            'school_phone' => $_POST['school_phone'],
//            'wp_user_id' => $user_create_response,
//        ];
//
//        global $wpdb;
//        $wpdb->insert('wp_wcp_school_teacher', $postData);
//        if (!empty($wpdb->last_error)) {
//            echo $wpdb->last_error;
//            exit;
//        } else {
//            echo 'OK';
//            exit;
//        }
//
//    }
//}

## Student registraiton
## School registration ajax request.
//add_action('wp_ajax_student_sign_up', student_registration_ajax_request());
//add_action('wp_ajax_nopriv_student_sign_up', student_registration_ajax_request());
//function student_registration_ajax_request()
//{
//    if ($_POST['action'] == 'student_sign_up') {
//
//        $response = null;
//        $wp_user_name = null;
//        if (!empty($_POST['student_first_name']) || !empty($_POST['student_last_name'])) {
//            $wp_user_name = $_POST['student_first_name'] . $_POST['student_last_name'];
//        } else {
//            $response = 'User name is missing in account details';
//        }
//
//        if (!empty($response)) {
//            echo $response;
//            exit;
//        }
//
//        ## if both password are not matched
//        if (empty($_POST['student_pass']) || empty($_POST['student_confr_pass'])) {
//            $response = 'User password and confirm password should not be empty';
//        } else {
//            if ($_POST['student_pass'] <> $_POST['student_confr_pass']) {
//                $response = 'Password and confirm password should be same';
//            }
//        }
//
//        if (!empty($response)) {
//            echo $response;
//            exit;
//        }
//
//        ## email check
//        if (empty($_POST['student_email'])) {
//            $response = 'Teacher email address is missing';
//        }
//
//        if (empty($_POST['school_ref'])) {
//            $response = 'School not found';
//        }
//
//        if (empty($_POST['teacher_ref'])) {
//            $response = 'Teacher not found';
//        }
//
//        if (!empty($response)) {
//            echo $response;
//            exit;
//        }
//
//
//        ## create wp user first.
//        $user_create_response = wp_create_user($wp_user_name, $_POST['student_pass'], $_POST['student_email']);
//        if (is_wp_error($user_create_response)) {
//            $response = $user_create_response->get_error_message();
//        } else {
//            ## user role to the school
//            wp_update_user(array('ID' => $user_create_response, 'role' => 'wcp_student'));
//        }
//        if (!empty($response)) {
//            echo $response;
//            exit();
//        }
//
//        ## then register teacher in database.
//        $postData = [
//            'school_id' => $_POST['school_ref'],
//            'teacher_id' => $_POST['teacher_ref'],
//            //            'school_state' => $_POST['school_state'],
//            //            'school_zipcode' => $_POST['school_zipcode'],
//            //            'school_country' => $_POST['school_coutry'],
//            //            'school_phone' => $_POST['school_phone'],
//            'wp_user_id' => $user_create_response,
//        ];
//
//        global $wpdb;
//        $wpdb->insert('wp_wcp_school_teacher_student', $postData);
//        if (!empty($wpdb->last_error)) {
//            echo $wpdb->last_error;
//            exit;
//        } else {
//            echo 'OK';
//            exit;
//        }
//
//    }
//}


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
        return home_url($redirectionPage);
    }
}

add_filter('login_redirect', 'admin_default_page', 1000000000, 3);


//add_action('wp_login', afterLoginRedirectToPageAccordingToRole());

//add_filter('login_redirect', function () {
//    $current_user = wp_get_current_user();
//    $redirectionPage = null;
//    if (in_array('wcp_school', (array)$current_user->roles)) {
//        $redirectionPage = 'school-dashboard/';
//    } else if (in_array('wcp_teacher', (array)$current_user->roles)) {
//        $redirectionPage = 'teacher-dashboard/';
//    } else if (in_array('wcp_student', (array)$current_user->roles)) {
//        $redirectionPage = 'student-dashboard/';
//    }
//
//
//    if (!empty($redirectionPage)) {
//        wp_redirect(get_site_url() . '/' . $redirectionPage);
//    }
//
//}, 100);
