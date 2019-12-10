<?php
class WCP_Frontend_Student_Modal
{
    public $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;

        add_action('wp_ajax_WCP_Frontend_Student_Modal::get_students', Array($this, 'get_students'));
        add_action('wp_ajax_nopriv_WCP_Frontend_Student_Modal::get_students', array($this, 'get_students'));

        add_action('wp_ajax_WCP_Frontend_Student_Modal::get_student_by_id', Array($this, 'get_student_by_id'));
        add_action('wp_ajax_nopriv_WCP_Frontend_Student_Modal::get_student_by_id', array($this, 'get_student_by_id'));

        add_action('wp_ajax_WCP_Frontend_Student_Modal::add_student', Array($this, 'add_student'));
        add_action('wp_ajax_nopriv_WCP_Frontend_Student_Modal::add_student', array($this, 'add_student'));

        add_action('wp_ajax_WCP_Frontend_Student_Modal::edit_student', Array($this, 'edit_student'));
        add_action('wp_ajax_nopriv_WCP_Frontend_Student_Modal::edit_student', array($this, 'edit_student'));

        add_action('wp_ajax_WCP_Frontend_Student_Modal::delete_student', Array($this, 'delete_student'));
        add_action('wp_ajax_nopriv_WCP_Frontend_Student_Modal::delete_student', array($this, 'delete_student'));

    }

    public function get_students(){
        global $WCP_Common_Student_Model;
        $requestData = $_REQUEST;
        $json_data = $WCP_Common_Student_Model->get_students($requestData);
        echo json_encode($json_data);
        exit(0);
    }

    public function get_student_by_id(){
        global $WCP_Common_Student_Model;
        $requestData = $_REQUEST["id"];
        $json_data = $WCP_Common_Student_Model->get_student_by_id($requestData);
        echo json_encode($json_data);
        exit(0);
    }

    public function edit_student(){
        global $WCP_Common_Student_Model;
        $requestData = $_REQUEST;
        
        if(isset($_REQUEST["wp_user_id"])){
            $wp_user_id = $_REQUEST["wp_user_id"];
            $args = array(
                'ID'         => $wp_user_id,
                'user_email' => esc_attr( $_REQUEST['input_email'] )
            );
            $email = esc_attr( $_REQUEST['input_email'] );
            if (!is_email($email)) {
                $response = array('status' => 0, 'error' => 'Invalid Email.');
            }else {
                wp_update_user( $args );
                $response = $WCP_Common_Student_Model->wcp_edit_student($requestData);
                if (isset($_REQUEST["input_first_name"])) {
                    $first_name = $_REQUEST["input_first_name"];
                    update_user_meta($wp_user_id, "first_name", $first_name);
                }
                if (isset($_REQUEST["input_last_name"])) {
                    $last_name = $_REQUEST["input_last_name"];
                    update_user_meta($wp_user_id, "last_name", $last_name);
                }
            }
                

        }else{
            $response = array('status' => 0, 'error' => 'Invalid Request');
        }
            
        echo json_encode($response);
        exit(0);
    }

    public function add_student(){
        global $WCP_Common_Student_Model, $WCP_FrontEnd_Signup_Controller;
        $requestData = $_REQUEST;
        $valid_post_fields = array('input_first_name', 'input_email', 'school_id', 'teacher_id');
        if ($WCP_FrontEnd_Signup_Controller->is_valid_post_request($valid_post_fields)) {
            $first_name = $_REQUEST['input_first_name'];
            $last_name = isset($_REQUEST["input_last_name"]) ? $_REQUEST["input_last_name"] : "";
            $full_name = $first_name . " " . $last_name;
            $full_name = trim($full_name);

            $email = $_REQUEST['input_email'];

            /// Get the core wp registration file
            require_once(ABSPATH . WPINC . '/registration.php');

            if (!is_email($email)) {

                $response = array('status' => 0, 'error' => 'Invalid Email.');

            } elseif (email_exists($email)) {

                $response = array('status' => 0, 'error' => 'Email already exists.');

            }  else {
                $pass = "test@pass".rand(0,999);
                $new_user_id = wp_create_user($email, $pass, $email);

                update_user_meta($new_user_id, "first_name", $first_name);
                update_user_meta($new_user_id, "last_name", $last_name);

                if ($new_user_id) {

                    $user = get_userdata($new_user_id);

                    $response = array('status' => 1, 'msg' => 'Registered successfully. Please wait...');

                    if ($user && $user->exists()) {
                        $formdata = $_REQUEST;
                        $formdata["wp_user_id"] = $new_user_id;
                        $formdata["full_name"] = $full_name;

                        $student = $WCP_Common_Student_Model->wcp_add_student($formdata);
                        $response["studentres"] = $student;
                        $user->set_role('wcp_student');
                    }


                } else {

                    $response = array('status' => 0, 'error' => 'Registration problem. Please contact administrator.');
                }
            }
        }else {
            $response = array('status' => 0, 'error' => 'Please fill required fields');
        }

        echo json_encode($response);
        exit(0);
    }

    public function delete_student(){
    	global $WCP_Common_Student_Model;
    	$requestData = $_REQUEST["id"];
    	echo $json_data = $WCP_Common_Student_Model->wcp_delete_student($requestData);
    	//echo json_encode($json_data);
        exit(0);
    }



}
if(class_exists("WCP_Frontend_Student_Modal")) {
    $WCP_Frontend_Student_Modal = new WCP_Frontend_Student_Modal();

    
}