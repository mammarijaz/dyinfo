<?php

class WCP_BackEnd_Schools_Model {

    public function __construct() {

        $table_name = 'wcp_schools';
        $this->table_name =  $table_name;

        add_action('wp_ajax_nopriv_WCP_BackEnd_Schools_Model::add_school', array($this, 'wcp_add_school'));
        add_action('wp_ajax_WCP_BackEnd_Schools_Model::add_school', array($this, 'wcp_add_school'));
        
        add_action('wp_ajax_nopriv_WCP_BackEnd_Schools_Model::edit_school', array($this, 'wcp_edit_school'));
        add_action('wp_ajax_WCP_BackEnd_Schools_Model::edit_school', array($this, 'wcp_edit_school'));
        
        add_action('wp_ajax_nopriv_WCP_BackEnd_Schools_Model::delete_school', array($this, 'wcp_delete_school'));
        add_action('wp_ajax_WCP_BackEnd_Schools_Model::delete_school', array($this, 'wcp_delete_school'));

        add_action('wp_ajax_WCP_BackEnd_Schools_Model::get_schools', array($this, 'get_schools'));
        add_action('wp_ajax_nopriv_WCP_BackEnd_Schools_Model::get_schools', array($this, 'get_schools'));

        add_action('wp_ajax_WCP_BackEnd_Schools_Model::get_school_by_id', Array($this, 'get_school_by_id'));
        add_action('wp_ajax_nopriv_WCP_BackEnd_Schools_Model::get_school_by_id', array($this, 'get_school_by_id'));

        add_action( 'wp_ajax_WCP_BackEnd_Schools_Model::get_select2_json', array($this,'get_select2_json'));
        add_action( 'wp_ajax_nopriv_WCP_BackEnd_Schools_Model::get_select2_json', array($this,'get_select2_json'));
       
    }

    public function wcp_add_school() {
        global $wpdb;
        global $WCP_Common_School_Model;
        $errorArray = [];
        $errorMessage = "Please fill the '%' field.";
        $response = array();
        $response['status'] = 0;
        $response['error'] = 0;
        $response['errors'] = array();
        $response['message'] = "";
        if (!isset($_POST['input_email']) || trim($_POST['input_email']) == "") {
            $error = array(
                "key" => "input_email",
                "message" => sprintf($errorMessage, __("Email Address ", "wcp"))
            );
            $errorArray[] = $error;
        }else if(!is_email($_POST['input_email'])) {
            $error = array(
                "key" => "input_email",
                "message" =>  __("Invalid Email Address.", "wcp")
            );
            $errorArray[] = $error;

        } else if(email_exists($_POST['input_email'])) {
            $error = array(
                "key" => "input_email",
                "message" =>  __("Email already exists.", "wcp")
            );
            $errorArray[] = $error;

        } 

        if (!empty($errorArray)) {
            $response['error'] = 1;
            $response['status'] = 0;
            $response['errors'] = $errorArray;
        } else {
            $email = $_POST['input_email'];
            $pass = wcp_rand_string(8);
            $new_user_id = wp_create_user( $email, $pass, $email );
            if($new_user_id) {

                $user = get_userdata( $new_user_id );   
                $first_name = isset($_REQUEST["input_first_name"]) ? $_REQUEST["input_first_name"] :"";
                $last_name = isset($_REQUEST["input_last_name"]) ? $_REQUEST["input_last_name"] :"";
                $full_name = $first_name." ".$last_name;
                $full_name = trim($full_name);

                $formdata = $_REQUEST;
                $formdata["wp_user_id"] = $new_user_id;
                $formdata["full_name"] = $full_name;
                $response = $WCP_Common_School_Model->wcp_add_school($formdata);    
            }
        }
        
        echo json_encode($response);
        exit();
    }

    public function get_schools() {
        global $wpdb,$wp;
        $data = array();
        $obj_result = new \stdclass();
        $obj_result->is_success = false;
        $requestData = $_REQUEST;

        //This is for Search
        $where = " WHERE 1 = 1 AND is_deleted = 0 ";


        $table_name = $this->table_name;
        $sql = "SELECT * FROM $table_name ".$where ;
        $result = $wpdb->get_results($sql);
         

        $totalData = 0;
        $totalFiltered = 0;
        if (count($result) > 0) {
            $totalData = count($result);
            $totalFiltered = count($result);
        }

        
        

        if (isset($requestData['search']['value']) && $requestData['search']['value'] != '' ) {
            $search  = $requestData['search']['value'];
            $sql .= " AND (school_name LIKE '%$search%' OR school_address LIKE '%$search%')";

        }

        //This is for pagination
        if (isset($requestData['start']) && $requestData['start'] != '' && isset($requestData['length']) && $requestData['length'] != '') {
            $sql .= " LIMIT " . $requestData['start'] . "," . $requestData['length'];
        }
        $service_price_list = $wpdb->get_results($sql, "OBJECT");
        $arr_data = array();
        $temp = array();
        $arr_data = $result;

        foreach ($service_price_list as $row) {
            $id = $row->id;
            $temp['id'] = $row->id;
            $temp['school_name'] = stripslashes_deep(stripslashes_deep($row->school_name));
           
            $temp['school_address'] = html_entity_decode(stripslashes_deep($row->school_address));
            $created_date = date( "m/d/Y", strtotime( $row->created_date));
            $temp['created_date'] = $created_date;
            $temp['wp_user_id'] = $row->wp_user_id;
            $temp['wp_user_link'] = "<a href='users.php?user_id=".$row->wp_user_id."'>#".$row->wp_user_id."</a>";
             $user_info = get_userdata($row->wp_user_id);
            $temp['first_name'] = $user_info->first_name;
            $temp['last_name'] = $user_info->last_name;
            $temp['email'] = $user_info->user_email;
            $temp['school_phone'] = $row->school_phone;
            $action = '<div style="display: flex;">';
            
            $action .= '<input type="button" value="Edit" class="btn btn-info"  onclick="wcp_edit_row(' . $id . ')">&nbsp; &nbsp;';
            $action .= "<input type='button' value='Delete' class='btn btn-danger' onclick='wcp_delete_row(" . $id . ")'>&nbsp;";
            $action .= '</div>';
            
            $temp['action'] = $action;
            $data[] = $temp;
            $id = "";
        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
            "sql" => $sql
        );
        echo json_encode($json_data);
        exit(0);

        
    }

    public function get_schools_array() {
        global $wpdb;
        $table_name = $this->table_name;
        $sql = "SELECT * FROM $table_name";
        $rows = $wpdb->get_results($sql);
        return $rows;
    }
    
    public function wcp_edit_school() {
        global $wpdb;
        $table_name = $this->table_name;
        $postData = $_POST;
        $errorArray = [];
        $errorMessage = "Please fill the '%' field.";
        $response = array();
        $response['status'] = 0;
        $response['error'] = 0;
        $response['errors'] = array();
        $response['message'] = "";
        if (!isset($postData['title']) || trim($postData['title']) == "") {
            $error = array(
                "key" => "title",
                "message" => sprintf($errorMessage, __("Title", "wcp"))
            );
            $errorArray[] = $error;
        }
        if (!empty($errorArray)) {
            $response['error'] = 1;
            $response['status'] = 0;
            $response['errors'] = $errorArray;
        } else {
            
            $edit_id = isset($_REQUEST["edit_id"]) ? $_REQUEST["edit_id"] :"";

            $title = isset($_REQUEST["title"]) ? esc_sql($_REQUEST["title"]) :"";
            $is_trending = isset($_REQUEST["is_trending"]) ? esc_sql($_REQUEST["is_trending"]) :0;
            $is_poll = isset($_REQUEST["is_poll"]) ? esc_sql($_REQUEST["is_poll"]) :0;
            $is_featured = isset($_REQUEST["is_featured"]) ? esc_sql($_REQUEST["is_featured"]) :0;
            $content = isset($_REQUEST["content"]) ? esc_html($_REQUEST["content"]) :"";
            $updateddate = current_time("Y-m-d H:i:s");

            $wpdb->update(
                $table_name, 
                array( 
                    'title' => $title, 
                    'is_trending' => $is_trending, 
                    'is_poll' => $is_poll, 
                    'is_featured' => $is_featured, 
                    'content' => $content,
                    'updated_date' => $updateddate
                ),
                array( 'id' => $edit_id )
            );
            $id = $edit_id;
			
            $_REQUEST['object_id'] = $edit_id;

            if($wpdb->last_error !== '') :

                $str   = htmlspecialchars( $wpdb->last_result, ENT_QUOTES );
                $query = htmlspecialchars( $wpdb->last_query, ENT_QUOTES );

                print "<div id='error'>
                <p class='wpdberror'><strong>WordPress database error:</strong> [$str]<br />
                <code>$query</code></p>
                </div>";

            endif;
        
            if ($id) {
                    $response['success'] = 1;
                    $response['status'] = 1;
                    $response['urlredirect'] = '';
            } else {
                $response['error'] = 1;
                $response['status'] = 0;
            }
        }
        echo json_encode($response);
        exit();
    }
    
    
    public function wcp_delete_school() {
        //print_r($_REQUEST);
        $table_name = $this->table_name;
        global $wpdb;
        if(isset($_REQUEST["id"])){
			$wpdb->update(
                $table_name, 
                array( 
                    'is_deleted' => 1
                ),
                array( 'id' => $_REQUEST['id'] )
            );

            if($wpdb->last_error !== '') :
                $wpdb->print_error();
            endif;
            /*$wpdb->delete($table_name,
                 [ 'id' => $_REQUEST['id'] ],
                 [ '%d' ] );*/
        }
        echo "success";
        exit();
    }

    public function get_school_by_id() {
        global $wpdb;
        $table_name = $this->table_name;
        $response = array();
        $response['status'] = 0;
        $response['row'] = array();
        if(isset($_REQUEST["id"])){
            $response['status'] = 1;
            $response['row'] = $this->get_school($_REQUEST['id']);
        }
        echo json_encode($response);
        exit();
    }

    public function is_poll($id) {
      $school =  $this->get_school($id);
      if(!empty( $school )){
        if($school->is_poll == 1){
            return true;      
        }
      }
      return false;
    }
    
    public function get_school($id) {
        global $wpdb;
        $json = [];
        $categories_str = [];
        $table_name = $this->table_name;
        $query = "SELECT * FROM {$table_name} WHERE id = " . esc_sql(trim($id));
        $row = $wpdb->get_row($query);
        $row->content = html_entity_decode(stripslashes_deep ($row->content));
        $row->title = stripslashes_deep(stripslashes_deep($row->title));
        return $row;
    }

    public function get_select2_json(){
        global $wpdb;
        $json = [];
        $query = 'SELECT * From '.$this->table_name.' WHERE  is_deleted=0 ';
        if(isset($_GET['q'])){
            $query .= ' AND  title LIKE "%'.esc_sql($_GET['q']).'%"';
        } 
        $result = $wpdb->get_results($query, "ARRAY_A");
        if($result){
            foreach ($result as $val) {
                $json[] = ['id'=>$val['id'], 'text'=> stripslashes_deep($val['title'])];
            }
        }
        echo json_encode($json);
        exit();
    }

}

if (class_exists("WCP_BackEnd_Schools_Model")) {
    $WCP_BackEnd_Schools_Model = new WCP_BackEnd_Schools_Model();
}
