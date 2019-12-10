<?php

class WCP_Common_School_Model {

    public function __construct() {

        $table_name = 'wcp_schools';
        $this->table_name =  $table_name;
    }

    public function wcp_add_school($postData) {
        global $wpdb;
        $table_name = $this->table_name;
        $errorArray = [];
        $errorMessage = "Please fill the '%' field.";
        $response = array();
        $response['status'] = 0;
        $response['error'] = 0;
        $response['errors'] = array();
        $response['message'] = "";
        if (!isset($postData['input_school_name']) || trim($postData['input_school_name']) == "") {
            $error = array(
                "key" => "input_school_name",
                "message" => sprintf($errorMessage, __("School Name", "wcp"))
            );
            $errorArray[] = $error;
        }

        if (!empty($errorArray)) {
            $response['error'] = 1;
            $response['status'] = 0;
            $response['errors'] = $errorArray;
        } else {
           
            $school_name = isset($postData["input_school_name"]) ? esc_sql($postData["input_school_name"]) :"";
            $school_phone = isset($postData["input_phone"]) ? esc_sql($postData["input_phone"]) :"";
            $school_address = isset($postData["input_address"]) ? esc_sql($postData["input_address"]) :"";
            $school_city = isset($postData["input_city"]) ? esc_sql($postData["input_city"]) :"";
            $school_state = isset($postData["input_state"]) ? esc_sql($postData["input_state"]) :"";
            $school_zipcode = isset($postData["input_zip"]) ? esc_sql($postData["input_zip"]) :"";
            $school_country = isset($postData["input_country"]) ? esc_sql($postData["input_country"]) :"";
            $wp_user_id = isset($postData["wp_user_id"]) ? esc_sql($postData["wp_user_id"]) :0;
            $createddate = current_time("Y-m-d H:i:s");
            $updateddate = current_time("Y-m-d H:i:s");

            $wpdb->insert( 
                $table_name, 
                array( 
                    'school_name' => $school_name, 
                    'school_phone' => $school_phone, 
                    'school_address' => $school_address, 
                    'school_city' => $school_city, 
                    'school_state' => $school_state,
                    'school_zipcode' => $school_zipcode,
                    'school_country' => $school_country,
                    'wp_user_id' => $wp_user_id,
                    'created_date' => $createddate, 
                    'updated_date' => $updateddate
                )
            );
            
            $id = $wpdb->insert_id;
			
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
                    $response['object_id'] = $id;
                    $response['status'] = 1;
                    $response['urlredirect'] = '';
            } else {
                $response['error'] = 1;
                $response['status'] = 0;
            }
        }
        return $response;
    }
    public function wcp_edit_school($postData) {
        global $wpdb;
        $table_name = $this->table_name;
        $errorArray = [];
        $errorMessage = "Please fill the '%' field.";
        $response = array();
        $response['status'] = 0;
        $response['error'] = 0;
        $response['errors'] = array();
        $response['message'] = "";
        if (!isset($postData['input_school_name']) || trim($postData['input_school_name']) == "") {
            $error = array(
                "key" => "input_school_name",
                "message" => sprintf($errorMessage, __("School Name", "wcp"))
            );
            $errorArray[] = $error;
        }
        if (!empty($errorArray)) {
            $response['error'] = 1;
            $response['status'] = 0;
            $response['errors'] = $errorArray;
        } else {
            
            $edit_id = isset($postData["edit_id"]) ? $postData["edit_id"] :"";

            $school_name = isset($postData["input_school_name"]) ? esc_sql($postData["input_school_name"]) :"";
            $school_phone = isset($postData["input_phone"]) ? esc_sql($postData["input_phone"]) :"";
            $school_address = isset($postData["input_address"]) ? esc_sql($postData["input_address"]) :"";
            $school_city = isset($postData["input_city"]) ? esc_sql($postData["input_city"]) :"";
            $school_state = isset($postData["input_state"]) ? esc_sql($postData["input_state"]) :"";
            $school_zipcode = isset($postData["input_zip"]) ? esc_sql($postData["input_zip"]) :"";
            $school_country = isset($postData["input_country"]) ? esc_sql($postData["input_country"]) :"";
            $wp_user_id = isset($postData["wp_user_id"]) ? esc_sql($postData["wp_user_id"]) :0;

            $updateddate = current_time("Y-m-d H:i:s");

            $wpdb->update(
                $table_name, 
                array( 
                    'school_name' => $school_name, 
                    'school_phone' => $school_phone, 
                    'school_address' => $school_address, 
                    'school_city' => $school_city, 
                    'school_state' => $school_state,
                    'school_zipcode' => $school_zipcode,
                    'school_country' => $school_country,
                    'wp_user_id' => $wp_user_id,
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
        return $response;
    }
    

    public function get_schools($requestData) {

        global $wpdb,$wp;
        $data = array();
        $obj_result = new \stdclass();
        $obj_result->is_success = false;
        //$requestData = $_REQUEST;
        //This is for Search
        $where = " WHERE 1 = 1 AND is_deleted = 0 ";
        $table_name = $this->table_name;
        $sql = "SELECT * FROM $table_name ".$where ;
        $result = $wpdb->get_results($sql);
        // print_r($result);
        $totalData = 0;
        $totalFiltered = 0;
        if (count($result) > 0) {
            $totalData = count($result);
            $totalFiltered = count($result);
        }

        //This is for pagination
        if (isset($requestData['start']) && $requestData['start'] != '' && isset($requestData['length']) && $requestData['length'] != '') {
            $sql .= " LIMIT " . $requestData['start'] . "," . $requestData['length'];
        }

        $service_price_list = $wpdb->get_results($sql, "OBJECT");
        $arr_data = array();
        $arr_data = $result;

        foreach ($service_price_list as $row) {
            $temp['id'] = $row->id;
            $temp['school_name'] = stripslashes_deep(stripslashes_deep($row->school_name));
            $temp['school_phone'] = stripslashes_deep(stripslashes_deep($row->school_phone));
            $temp['school_address'] = stripslashes_deep(stripslashes_deep($row->school_address));
            $temp['school_city'] = stripslashes_deep(stripslashes_deep($row->school_city));
            $temp['school_state'] = stripslashes_deep(stripslashes_deep($row->school_state));
            $temp['school_country'] = stripslashes_deep(stripslashes_deep($row->school_country));
            $temp['wp_user_id'] = $row->wp_user_id;
            $temp['school_zipcode'] = $row->school_zipcode;
            $temp['is_trending'] = $is_trending;
            $temp['content'] = html_entity_decode(stripslashes_deep($row->content));
            $created_date = date( "m/d/Y", strtotime( $row->created_date));
            $temp['created_date'] = $created_date;
            $data[] = $temp;            
        }

        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
            "sql" => $sql
        );
        return $json_data;
    }

    public function get_schools_array() {
        global $wpdb;
        $table_name = $this->table_name;
        $sql = "SELECT * FROM $table_name";
        $rows = $wpdb->get_results($sql);
        return $rows;
    }
    
    
    
    public function wcp_delete_school($id) {
        $table_name = $this->table_name;
        global $wpdb;
        if(isset($id)){
			$wpdb->update(
                $table_name, 
                array( 
                    'is_deleted' => 1
                ),
                array( 'id' => $id)
            );
            /*$wpdb->delete($table_name,
                 [ 'id' => $_REQUEST['id'] ],
                 [ '%d' ] );*/
        }
        echo "success";
        exit();
    }

    public function get_school_by_id($id) {
        global $wpdb;
        $table_name = $this->table_name;
        $response = array();
        $response['status'] = 0;
        $response['row'] = array();
        if(isset($id)){
            $response['status'] = 1;
            $response['row'] = $this->get_school($id);
        }
        echo json_encode($response);
        exit();
    }
    public function get_school_by_wp_user_id($user_id)
    {
        global $wpdb;
        $json = [];
        $categories_str = [];
        $table_name = $this->table_name;
        $query = "SELECT * FROM {$table_name} WHERE wp_user_id = " . esc_sql(trim($user_id));
        $row = $wpdb->get_row($query);
        return $row;
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

if (class_exists("WCP_Common_School_Model")) {
    $WCP_Common_School_Model = new WCP_Common_School_Model();
}
