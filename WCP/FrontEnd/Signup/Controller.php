<?php
include_once(dirname(__FILE__) . "/Model.php");
include(ABSPATH . "wp-includes/pluggable.php");

class WCP_FrontEnd_Signup_Controller
{
    public function __construct()
    {
        add_shortcode('wcp_signup_form', array($this, 'render_wcp_signup_form'));

        ## Signup ajax request.
        add_action('wp_ajax_wcp_signup', array($this, 'wcp_do_signup'));
        add_action('wp_ajax_nopriv_wcp_signup', array($this, 'wcp_do_signup'));
    }

    public function add_front_css_and_js()
    {
        wp_enqueue_style('wcp-datatable-css');

        wp_enqueue_script('wcp-common');
        wp_enqueue_script('wcp-datatable-js');
        wp_enqueue_script('wcp-signup-custom-js', WCP_PLUGIN_URL . '/WCP/FrontEnd/Signup/js/wcp-signup.js');
    }

    public function wcp_do_signup()
    {
       

        global $WCP_Common_School_Model, $WCP_Common_Teacher_Model, $WCP_Common_Student_Model;
        $valid_post_fields = array('input_first_name', 'input_email', 'input_pass');

        $user_type = isset($_REQUEST["user_type"]) ? $_REQUEST["user_type"] : "wcp_school";

        if ($user_type == "wcp_school") {
            $valid_post_fields = array('input_first_name', 'input_email', 'input_pass', 'input_school_name',
                'input_address', 'input_city', 'input_state', 'input_zip', 'input_country', 'input_phone');
        }
        if ($user_type == "wcp_teacher") {
            $valid_post_fields = array('input_first_name', 'input_email', 'input_pass', 'school_id');
        }

        if ($user_type == "wcp_student") {
            $valid_post_fields = array('input_first_name', 'input_email', 'input_pass', 'school_id', 'teacher_id');
        }


        if ($this->is_valid_post_request($valid_post_fields)) {
            global $wpdb;
            $first_name = $_REQUEST['input_first_name'];
            $last_name = isset($_REQUEST["input_last_name"]) ? $_REQUEST["input_last_name"] : "";
            $full_name = $first_name . " " . $last_name;
            $full_name = trim($full_name);

            $email = $_REQUEST['input_email'];
            $pass = $_REQUEST['input_pass'];
            $dob = "";
            /// Get the core wp registration file
            require_once(ABSPATH . WPINC . '/registration.php');

            if (!is_email($email)) {

                $response = array('status' => 'no', 'error' => 'Invalid Email.');

            } elseif (email_exists($email)) {

                $response = array('status' => 'no', 'error' => 'Email already exists.');

            } elseif ($pass == '') {

                $response = array('status' => 'no', 'error' => 'Password is required');

            } else {

                $new_user_id = wp_create_user($email, $pass, $email);


                update_user_meta($new_user_id, "first_name", $first_name);
                update_user_meta($new_user_id, "last_name", $last_name);

                if ($new_user_id) {

                    //Do Login
                    $this->auto_login_new_user($new_user_id);
                    /*wp_set_current_user($new_user_id, $email);
                    wp_set_auth_cookie($new_user_id);
                    do_action('wp_login', $email);*/

                    //$url = site_url() . "/school-dashboard";
                    $url = site_url() . '/members/me/dashboard/';
                    $user = get_userdata($new_user_id);

                    $response = array('status' => 'ok', 'msg' => 'Registered successfully. Please wait...', 'url' => $url);

                    if ($user && $user->exists()) {
                        $formdata = $_REQUEST;
                        $formdata["wp_user_id"] = $new_user_id;
                        $formdata["full_name"] = $full_name;

                        if ($user_type == "wcp_school") {
                            $school = $WCP_Common_School_Model->wcp_add_school($formdata);
                            $response["schoolres"] = $school;
                            $user->set_role('wcp_school');
                            $user->remove_role('student');
                        }
                        if ($user_type == "wcp_teacher") {
                            $teacher = $WCP_Common_Teacher_Model->wcp_add_teacher($formdata);
                            $response["teacherres"] = $teacher;
                            $user->set_role('wcp_teacher');
                            $user->remove_role('student');
                            //$response['url'] = site_url() . '/teacher-dashboard';
                        }

                        if ($user_type == "wcp_student") {
                            $student = $WCP_Common_Student_Model->wcp_add_student($formdata);
                            $response["studentres"] = $student;
                            //$response['url'] = site_url() . '/student-dashboard';
                            $user->set_role('wcp_student');
                        }
                    }


                } else {

                    $response = array('status' => 'no', 'error' => 'Registration problem. Please contact administrator.');
                }

            }

        } else {

            $response = array('status' => 'no', 'error' => 'Invalid request');
        }

        echo json_encode($response);
        wp_die();

    }

    private function auto_login_new_user( $user_id ) {
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);
        //$user = get_user_by( 'id', $user_id );
        //do_action( 'wp_login', $user->user_login );
    }

    public function is_valid_post_request($valid_post_fields)
    {


        foreach ($valid_post_fields as $key) {
            if (!array_key_exists($key, $_REQUEST) || $_REQUEST[$key] == '') {
                return false;
                exit();
            }
        }
        return true;
    }

    public function render_wcp_signup_form($atts)
    {
        $this->add_front_css_and_js();
        ob_start();
        $user_type = "wcp_school";
        if (isset($_REQUEST["user_type"])) {
            $input_user_type = $_REQUEST["user_type"];
            if ($input_user_type == "school") {
                $user_type = "wcp_school";
            }
            if ($input_user_type == "teacher") {
                $user_type = "wcp_teacher";
            }
            if ($input_user_type == "student") {
                $user_type = "wcp_student";
            }
        }
        include_once dirname(__FILE__) . "/View/signup.php";
        $content = ob_get_clean();
        return $content;
    }
}


if (class_exists("WCP_FrontEnd_Signup_Controller")) {
    $WCP_FrontEnd_Signup_Controller = new WCP_FrontEnd_Signup_Controller();
}
