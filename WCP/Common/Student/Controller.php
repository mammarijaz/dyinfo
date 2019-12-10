<?php
include_once(dirname(__FILE__)."/Model.php");
include(ABSPATH . "wp-includes/pluggable.php");
class WCP_Common_Student_Controller {

    public function __construct() {
        
    }

}

if(class_exists("WCP_Common_Student_Controller")) {
    $WCP_Common_Student_Controller = new WCP_Common_Student_Controller();

    
}
