<?php
include_once(dirname(__FILE__) . "/Model.php");
include(ABSPATH . "wp-includes/pluggable.php");

class WCP_FrontEnd_StudentClass_Controller
{

    public function __construct()
    {
        ## class room list
        add_shortcode('wcp_classes_list', array($this, 'render_wcp_classes_list'));
        ## make class room view
        add_shortcode('wcp_add_class_form', array($this, 'render_wcp_add_class_form'));
        
        ## assign class students to class room
        add_shortcode('wcp_assign_to_classRoom', array($this, 'render_wcp_assign_studentClassRoom'));
        ## details of class room, which contains class room details and class enrolment
        add_shortcode('wcp_classRoom_details', array($this, 'render_wcp_classRoomDetails'));

        ## Class Room ajax request
        add_action('wp_ajax_wcp_class_room', array($this, 'classRoomHub'));
        add_action('wp_ajax_nopriv_wcp_class_room', array($this, 'classRoomHub'));

    }


    public function classRoomHub()
    {
        $WCPFrontEndStudentClassModel = new WCPFrontEndStudentClassModel();
        $target = !empty($_REQUEST['target']) ? $_REQUEST['target'] : null;
        $response = null;

        switch ($target) {
            case 'makeClassRoom':
                ## add or edit class room
                $response = $WCPFrontEndStudentClassModel->saveClassRoom($_REQUEST);
                break;
            case 'deleteClass':
                ## delete Class
                $response = $WCPFrontEndStudentClassModel->deleteClassRoom($_REQUEST['ref']);
                break;
            case 'assignStudentClassRoom':
                $response = $WCPFrontEndStudentClassModel->assignStudentToClassRoom($_REQUEST);
                $response = $_REQUEST;
                break;
            case 'deleteClassEnrolment':
                ## Delete Class enrolment
                $response = $WCPFrontEndStudentClassModel->deleteClassRoomEnrolment($_REQUEST['ref']);
                break;
            default:
                $response = $_REQUEST;
                break;
        }
        echo json_encode($response);
        wp_die();
    }

    ## class room details
    public function render_wcp_classRoomDetails()
    {
        $this->add_front_css_and_js_extended();

//        wp_enqueue_style('wcp-datatable-css', 'https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css');
//        wp_enqueue_script('wcp-datatable-js', 'https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js');

        ob_start();
        include_once dirname(__FILE__) . "/View/classRoomDetail.php";
        $content = ob_get_clean();
        return $content;
    }

    ## Css and js file for this module
    public function add_front_css_and_js_extended()
    {
         wp_enqueue_script('wcp-common');
        wp_enqueue_script('wcp-classes-custom-js', WCP_PLUGIN_URL . '/WCP/FrontEnd/StudentClasses/js/classes.js', false, time());
    }

    ## Make Class room
    public function render_wcp_add_class_form()
    {

        $this->add_front_css_and_js_extended();
        ob_start();
        include_once dirname(__FILE__) . "/View/class-form.php";
        $content = ob_get_clean();
        return $content;
    }

    ## Class list
    public function render_wcp_classes_list()
    {
        $this->add_front_css_and_js_extended();

        ob_start();
        include_once dirname(__FILE__) . "/View/classes-list.php";
        $content = ob_get_clean();
        return $content;
    }

    ## Assign students in class room
    public function render_wcp_assign_studentClassRoom()
    {
        $this->add_front_css_and_js_extended();
        ob_start();
        include_once dirname(__FILE__) . "/View/assignStudentToClass.php";
        $content = ob_get_clean();
        return $content;
    }

}


## Create class instance
if (class_exists("WCP_FrontEnd_StudentClass_Controller")) {
    $WCP_FrontEnd_StudentClass_Controller = new WCP_FrontEnd_StudentClass_Controller();
    $WCP_FrontEnd_Signup_Controller = new WCP_FrontEnd_Dashboard_Controller();
    $WCP_FrontEnd_Signup_Controller->add_front_css_and_js();

}