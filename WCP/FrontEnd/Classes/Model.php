<?php
class WCP_Frontend_Class_Modal
{
    public $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;

        add_action('wp_ajax_WCP_Frontend_Class_Modal::get_classes', Array($this, 'get_classes'));
        add_action('wp_ajax_nopriv_WCP_Frontend_Class_Modal::get_classes', array($this, 'get_classes'));

        add_action('wp_ajax_WCP_Frontend_Class_Modal::get_class_by_id', Array($this, 'get_class_by_id'));
        add_action('wp_ajax_nopriv_WCP_Frontend_Class_Modal::get_class_by_id', array($this, 'get_class_by_id'));

        add_action('wp_ajax_WCP_Frontend_Class_Modal::edit_class', Array($this, 'edit_class'));
        add_action('wp_ajax_nopriv_WCP_Frontend_Class_Modal::edit_class', array($this, 'edit_class'));

        add_action('wp_ajax_WCP_Frontend_Class_Modal::add_class', Array($this, 'add_class'));
        add_action('wp_ajax_nopriv_WCP_Frontend_Class_Modal::add_class', array($this, 'add_class'));

        add_action('wp_ajax_WCP_Frontend_Class_Modal::delete_class', Array($this, 'delete_class'));
        add_action('wp_ajax_nopriv_WCP_Frontend_Class_Modal::delete_class', array($this, 'delete_class'));

    }

    public function get_classes(){
        global $WCP_Common_Class_Model;
        $requestData = $_REQUEST;
        $json_data = $WCP_Common_Class_Model->get_classes($requestData);
        echo json_encode($json_data);
        exit(0);
    }
    public function get_class_by_id(){
        global $WCP_Common_Class_Model;
        $requestData = $_REQUEST["id"];
        $json_data = $WCP_Common_Class_Model->get_class_by_id($requestData);
        if(is_array($json_data)){
            echo json_encode($json_data);    
        }else{
            echo $json_data;
        }
        exit(0);
    }
    public function edit_class(){
        global $WCP_Common_Class_Model;
        $requestData = $_REQUEST;
        $json_data = $WCP_Common_Class_Model->wcp_edit_class($requestData);
        echo json_encode($json_data);
        exit(0);
    }
    public function add_class(){
        global $WCP_Common_Class_Model;
        $requestData = $_REQUEST;
        $json_data = $WCP_Common_Class_Model->wcp_add_class($requestData);
        echo json_encode($json_data);
        exit(0);
    }
    public function delete_class(){
    	global $WCP_Common_Class_Model;
    	$requestData = $_REQUEST["id"];
    	echo $json_data = $WCP_Common_Class_Model->wcp_delete_class($requestData);
    	//echo json_encode($json_data);
        exit(0);
    }



}
if(class_exists("WCP_Frontend_Class_Modal")) {
    $WCP_Frontend_Class_Modal = new WCP_Frontend_Class_Modal();

    
}