<?php
include_once(dirname(__FILE__) . "/Model.php");
include(ABSPATH . "wp-includes/pluggable.php");

class WCP_FrontEnd_Student_Controller
{

    public function __construct()
    {
        add_shortcode( 'wcp_students_list', array($this, 'render_wcp_students_list') );
    }

    public function add_css_and_js() {

            wp_register_style( 'wcp-select2css', 'https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css' );
            wp_enqueue_style( 'wcp-select2css' );

            wp_enqueue_script( 'wcp-bootstrapcdn-js', 'https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js');
       
           /* // You need styling for the datepicker. For simplicity I've linked to Google's hosted jQuery UI CSS.
            wp_register_style( 'jquery-ui', '//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css' );
            wp_enqueue_style( 'jquery-ui' );
            wp_register_style( 'wcp-bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css' );
            wp_enqueue_style( 'wcp-bootstrap' ); 
            wp_register_style( 'wcp-timepicker', WCP_PLUGIN_URL.'/css/jquery-ui-timepicker-addon.css' );
            wp_enqueue_style( 'wcp-timepicker' );

            wp_enqueue_script( 'wcp-bootstrapcdn-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js');
            wp_enqueue_script( 'wcp-timepicker-js', WCP_PLUGIN_URL.'/js/jquery-ui-timepicker-addon.js');
            wp_enqueue_script( 'wcp-jquery-validate-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js');*/

        
        
    }

    public function render_wcp_students_list($atts) {
        $this->add_css_and_js();
        ob_start();
        ## get user from the wp_user table
        $current_user = wp_get_current_user();
        if (!empty($current_user->id) && array_intersect( array('wcp_teacher', 'wcp_school', 'administrator'), (array)$current_user->roles) ) {
            include_once dirname(__FILE__)."/View/students-list.php";
        }
        $content = ob_get_clean();
        return $content;
    }

}

## Create class instance
if (class_exists("WCP_FrontEnd_Student_Controller")) {
    $WCP_FrontEnd_Student_Controller = new WCP_FrontEnd_Student_Controller();
}