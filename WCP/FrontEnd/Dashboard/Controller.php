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

        ## dashboard ajax request.
        add_action('wp_ajax_wcp_dashboardHub', array($this, 'dashboardHub'));
        add_action('wp_ajax_nopriv_wcp_dashboardHub', array($this, 'dashboardHub'));
        add_action('wp_enqueue_scripts', [$this, 'add_front_css_and_js']);
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


        wp_enqueue_style('wcp-datatable-css', 'https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css');
        wp_enqueue_script('wcp-dashboard-custom-js', WCP_PLUGIN_URL . '/WCP/FrontEnd/Dashboard/js/wcp_dashboard_script.js');
        wp_enqueue_script('wcp-datatable-js', 'https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js');

    }

    public function add_front_css_and_js__base()
    {

        wp_deregister_script('jQuery');
        wp_register_script('jQuery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js', false, '1.11.3');
        wp_enqueue_script('jQuery');
        wp_deregister_script('jquery------');
        wp_register_script('jquery------', 'https://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js', false, '3.1.1');
        wp_enqueue_script('jquery------');
        wp_deregister_style('bootstrap');
        wp_register_style('bootstrap4--casefe', 'https://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css', false, '3.1.1');
        wp_enqueue_style('bootstrap4--casefe');
        wp_enqueue_script('wcp-signup-common-js', WCP_PLUGIN_URL . '/WCP/Common/common.js');
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
                if ($response <> 'Missing Arguments') {
                    $response = 'OK';
                }
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
                if ($response <> 'Missing Arguments') {
                    $response = 'OK';
                }
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
            ob_start();
        include_once dirname(__FILE__) . "/View/school_dashboard.php";
        $content = ob_get_clean();
        return $content;
    }

    ## Teacher Dashboard
    public function render_wcp_teacher_dashboard()
    {
        ob_start();
        include_once dirname(__FILE__) . "/View/teacher_dashboard.php";
        $content = ob_get_clean();
        return $content;
    }

    ## Student Dashboard
    public function render_wcp_student_dashboard()
    {
                ob_start();
        include_once dirname(__FILE__) . "/View/student_dashboard.php";
        $content = ob_get_clean();
        return $content;
    }

}

## Create class instance
if (class_exists("WCP_FrontEnd_Dashboard_Controller")) {
    $WCP_FrontEnd_Signup_Controller = new WCP_FrontEnd_Dashboard_Controller();
}
