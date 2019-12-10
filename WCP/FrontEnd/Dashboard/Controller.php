<?php
include_once(dirname(__FILE__) . "/Model.php");
include(ABSPATH . "wp-includes/pluggable.php");

class WCP_FrontEnd_Dashboard_Controller
{

    public function __construct()
    {
        ## dashboard init
        add_shortcode('wcp_school_admin__dashbaord', array($this, 'render_wcp_dashboard'));
        add_shortcode('wcp_teacher__dashbaord', array($this, 'render_wcp_teacher_dashboard'));
        add_shortcode('wcp_student__dashbaord', array($this, 'render_wcp_student_dashboard'));
        add_shortcode('wcp_invite_student', array($this, 'render_wcp_invite_student'));

        ## dashboard ajax request.
        add_action('wp_ajax_wcp_dashboardHub', array($this, 'dashboardHub'));
        add_action('wp_ajax_nopriv_wcp_dashboardHub', array($this, 'dashboardHub'));
       // add_action('wp_enqueue_scripts', array($this, 'add_front_css_and_js'));
    }


    public function add_front_css_and_js__base()
    {
        wp_enqueue_script('wcp-common');
        wp_enqueue_script("wcp-datatable");

        wp_enqueue_style("wcp-datatable");
        
    }

    public function add_front_css_and_js()
    {


         $this->add_front_css_and_js__base();

        /*
         *
         *       wp_register_script('bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css', false, '3.1.1');
        wp_enqueue_script('bootstrap-css');

        wp_deregister_script('jquery');
        wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js', false, '1.11.3');
        wp_enqueue_script('jquery');


        wp_register_style('bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js', false, '3.1.1');
        wp_enqueue_style('bootstrap-js');
         * */


        /*wp_enqueue_style('wcp-datatable-css', 'https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css');
        wp_enqueue_script('wcp-datatable-js', 'https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js',array( 'jquery' )); */
        
        wp_enqueue_script('wcp-dashboard-js', WCP_PLUGIN_URL . '/WCP/FrontEnd/Dashboard/js/wcp-dashboard.js',array( 'jquery', 'wcp-datatable-js' ));
        

    }

   

    ## where to take this request.
    public function dashboardHub()
    {
        $WCP_Common_Teacher_Model = new WCP_Common_Teacher_Model();
        $WCP_Common_Student_Model = new WCP_Common_Student_Model();
        global $WCP_Common_School_Model;
        $target = !empty($_REQUEST['target']) ? $_REQUEST['target'] : null;
        $response = null;

        switch ($target) {
            case 'teacherInvitation':
                $response = $WCP_Common_Teacher_Model->teacher_invitation($_REQUEST);
                if ($response) {
                     if($response == "Sent"){
                        $response = array('status' => true, 'msg' => 'Email has been sent to the user.');
                     }
                     if($response == "userExists"){
                        $response = array('status' => false, 'msg' => 'Email already exists.' );
                     }
                }else{
                    $response = array('status' => false, 'msg' => 'Something is wrong, Unable to send email.' );
                }
                echo json_encode($response);
                exit();
                break;
            case 'teacherUpdate':
                $response = $WCP_Common_Teacher_Model->wcp_edit_teacher($_REQUEST);
                if (!empty($response['success']) && $response['success'] == 1) {
                    $response = 'OK';
                }
                break;
            case 'teacher_delete':
                $response = $WCP_Common_Teacher_Model->wcp_delete_teacher($_REQUEST['id']);
                break;
            case 'studentInvitation':
                $response = $WCP_Common_Student_Model->student_invitation($_REQUEST);
                if ($response) {
                     if($response == "Sent"){
                        $response = array('status' => true, 'msg' => 'Email has been sent to the user.');
                     }
                     if($response == "userExists"){
                        $response = array('status' => false, 'msg' => 'Email already exists.' );
                     }
                }else{
                    $response = array('status' => false, 'msg' => 'Something is wrong, Unable to send email.' );
                }
                echo json_encode($response);
                exit();
                break;
            case 'studentUpdate':
                $response = $WCP_Common_Student_Model->wcp_edit_student($_REQUEST);
                if (!empty($response['success']) && $response['success'] == 1) {
                    $response = 'OK';
                }
                break;
            case 'student_delete':
                $response = $WCP_Common_Student_Model->wcp_delete_student($_REQUEST['id']);
                break;
            default:
                $target = null;
                break;
        }

        
        echo json_encode($response);
        exit();
    }

    ## school dashboard.
    public function render_wcp_dashboard()
    {
        $this->add_front_css_and_js__base();
        ob_start();
        include_once dirname(__FILE__) . "/View/school-dashboard.php";
        $content = ob_get_clean();
        return $content;
    }

    ## Teacher Dashboard
    public function render_wcp_teacher_dashboard()
    {
        $this->add_front_css_and_js__base();
        ob_start();
        include_once dirname(__FILE__) . "/View/teacher-dashboard.php";
        $content = ob_get_clean();
        return $content;
    }

    ## Invite Student
    public function render_wcp_invite_student($atts)
    {
        $this->add_front_css_and_js__base();
        ob_start();
        $a = shortcode_atts( array(
        'school_id' => '0',
        'teacher_id'  =>  '0'
         ), $atts );
        $school_id = $a["school_id"];
        $teacher_id = $a["teacher_id"];
        include_once dirname(__FILE__) . "/View/form-invite-student.php";
        $content = ob_get_clean();
        return $content;
    }

    ## Student Dashboard
    public function render_wcp_student_dashboard()
    {
        $this->add_front_css_and_js__base();
        ob_start();
        include_once dirname(__FILE__) . "/View/student-dashboard.php";
        $content = ob_get_clean();
        return $content;
    }

}

## Create class instance
if (class_exists("WCP_FrontEnd_Dashboard_Controller")) {
    $WCP_FrontEnd_Signup_Controller = new WCP_FrontEnd_Dashboard_Controller();
}

add_action( 'bp_setup_nav', 'wcp_register_nav', 50 );
 
function wcp_register_nav() {

    if(is_user_logged_in() && !is_admin()){
        ## get user from the wp_user table
        $current_user = wp_get_current_user();
        if (!empty($current_user->id) && in_array('wcp_teacher', (array)$current_user->roles)) {
            global $bp;
            bp_core_new_nav_item(
            array(
                'name'                => __( 'Classes', 'buddypress' ),
                'slug'                => 'wcp-classes',
                'position'            => 5,
                'screen_function'     => 'wcp_classes_template',
                'default_subnav_slug' => 'wcp-classes',
                'parent_url'          => $bp->loggedin_user->domain . $bp->slug . '/',
                'parent_slug'         => $bp->slug
            ) );
            bp_core_new_nav_item(
            array(
                'name'                => __( 'Students', 'buddypress' ),
                'slug'                => 'wcp-students',
                'position'            => 5,
                'screen_function'     => 'wcp_students_template',
                'default_subnav_slug' => 'wcp-students',
                'parent_url'          => $bp->loggedin_user->domain . $bp->slug . '/',
                'parent_slug'         => $bp->slug
            ) );
        }
    }
}
 
function wcp_classes_template() {
    add_action( 'bp_template_title', 'wcp_classes_title' );
    add_action( 'bp_template_content', 'wcp_classes_action_content' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}
function wcp_classes_title() {
    echo 'Manage Classes';
}
function wcp_classes_action_content() {
    echo do_shortcode("[wcp_class_list]");
}

function wcp_students_template() {
    add_action( 'bp_template_title', 'wcp_students_title' );
    add_action( 'bp_template_content', 'wcp_students_action_content' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}
function wcp_students_title() {
    echo 'Manage Students';
}
function wcp_students_action_content() {
    echo do_shortcode("[wcp_students_list]");
}