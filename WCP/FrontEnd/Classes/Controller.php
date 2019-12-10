<?php
include_once(dirname(__FILE__) . "/Model.php");
include(ABSPATH . "wp-includes/pluggable.php");

class WCP_FrontEnd_Class_Controller
{

    public function __construct()
    {
        add_shortcode( 'wcp_class_list', array($this, 'render_wcp_class_list') );
    }

    public function add_css_and_js() {
        wp_enqueue_script("wcp-common");
    }

    public function render_wcp_class_list($atts) {
        $this->add_css_and_js();
        ob_start();
        ## get user from the wp_user table
        $current_user = wp_get_current_user();
        if (!empty($current_user->id) && array_intersect( array('wcp_teacher', 'wcp_school', 'administrator'), (array)$current_user->roles) ) {
            include_once dirname(__FILE__)."/View/class-list.php";
        }
        $content = ob_get_clean();
        return $content;
    }

}

## Create class instance
if (class_exists("WCP_FrontEnd_Class_Controller")) {
    $WCP_FrontEnd_Class_Controller = new WCP_FrontEnd_Class_Controller();
}