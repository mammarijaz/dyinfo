<?php

class WCP_Common_Student_Model
{

    public function __construct()
    {

        $table_name = 'wcp_students';
        $this->table_name = $table_name;
    }

    public function wcp_add_student($postData)
    {
        global $wpdb;
        $table_name = $this->table_name;
        $errorArray = [];
        $errorMessage = "Please fill the '%' field.";
        $response = array();
        $response['status'] = 0;
        $response['error'] = 0;
        $response['errors'] = array();
        $response['message'] = "";
        if (!isset($postData['full_name']) || trim($postData['full_name']) == "") {
            $error = array(
                "key" => "full_name",
                "message" => sprintf($errorMessage, __("Student Name", "wcp"))
            );
            // $errorArray[] = $error;
        }

        if (!empty($errorArray)) {
            $response['error'] = 1;
            $response['status'] = 0;
            $response['errors'] = $errorArray;
        } else {

            $full_name = isset($postData["full_name"]) ? esc_sql($postData["full_name"]) : "";
            $wp_user_id = isset($postData["wp_user_id"]) ? esc_sql($postData["wp_user_id"]) : 0;
            $school_id = isset($postData["school_id"]) ? esc_sql($postData["school_id"]) : 0;
            $teacher_id = isset($postData["teacher_id"]) ? esc_sql($postData["teacher_id"]) : 0;
            $createddate = current_time("Y-m-d H:i:s");
            $updateddate = current_time("Y-m-d H:i:s");

            $wpdb->insert(
                $table_name,
                array(
                    'full_name' => $full_name,
                    'school_id' => $school_id,
                    'teacher_id' => $teacher_id,
                    'wp_user_id' => $wp_user_id,
                    'created_date' => $createddate,
                    'updated_date' => $updateddate
                )
            );

            $id = $wpdb->insert_id;

            if ($wpdb->last_error !== '') :

                $str = htmlspecialchars($wpdb->last_result, ENT_QUOTES);
                $query = htmlspecialchars($wpdb->last_query, ENT_QUOTES);

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

    public function wcp_edit_student($postData)
    {
        global $wpdb;
        $table_name = $this->table_name;
        $errorArray = [];
        $errorMessage = "Please fill the '%' field.";
        $response = array();
        $response['status'] = 0;
        $response['error'] = 0;
        $response['errors'] = array();
        $response['message'] = "";
        if (!isset($postData['input_first_name']) || trim($postData['input_first_name']) == "") {
            $error = array(
                "key" => "input_first_name",
                "message" => sprintf($errorMessage, __("Student Name", "wcp"))
            );
            $errorArray[] = $error;
        }
        if (!empty($errorArray)) {
            $response['error'] = 1;
            $response['status'] = 0;
            $response['errors'] = $errorArray;
        } else {

            $dataToUpdate = [];
            $edit_id = isset($postData["edit_id"]) ? $postData["edit_id"] : "";

            $full_name = isset($postData["full_name"]) ? esc_sql($postData["full_name"]) : "";
            

            $first_name = $postData['input_first_name'];
            $last_name = isset($postData["input_last_name"]) ? $postData["input_last_name"] : "";
            $full_name = $first_name . " " . $last_name;
            $full_name = trim($full_name);

            $dataToUpdate['full_name'] = $full_name;

            if (!empty($postData["wp_user_id"])) {
                $wp_user_id = esc_sql($postData["wp_user_id"]);
                $dataToUpdate['wp_user_id'] = $wp_user_id;
            }

            if (!empty($postData['teacher_id'])) {
                $dataToUpdate['teacher_id'] = esc_sql($postData["teacher_id"]);
            }

            if (!empty($postData['school_id'])) {
                $dataToUpdate['school_id'] = esc_sql($postData["school_id"]);
            }

            $updateddate = current_time("Y-m-d H:i:s");
            $dataToUpdate['updated_date'] = $updateddate;

            $wpdb->update(
                $table_name,
                $dataToUpdate,
                array('id' => $edit_id)
            );
            $id = $edit_id;

            $_REQUEST['object_id'] = $edit_id;

            if ($wpdb->last_error !== '') :

                $str = htmlspecialchars($wpdb->last_result, ENT_QUOTES);
                $query = htmlspecialchars($wpdb->last_query, ENT_QUOTES);

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


    public function get_students($requestData)
    {

        global $wpdb, $wp;
        $data = array();
        $obj_result = new \stdclass();
        $obj_result->is_success = false;
        //$requestData = $_REQUEST;
        //This is for Search
        $where = " WHERE 1 = 1 AND s.is_deleted = 0 ";
       // $table_name = $this->table_name;
        $sql = "SELECT s.*,IFNULL(e.class_room_id,'0') as \"class_room_id\" FROM wcp_students s "."  LEFT JOIN wcp_class_enrolment e on s.wp_user_id=e.wp_user_id". $where;
        //This is for pagination
        if (isset($requestData['teacher_id']) && $requestData['teacher_id'] != '' && $requestData['teacher_id'] != 0) {
            $sql .= " And teacher_id =" . $requestData['teacher_id'];
        }
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
            $id = $row->id;
            $temp['id'] = $row->id;
            $temp['full_name'] = stripslashes_deep(stripslashes_deep($row->full_name));
            $temp['wp_user_id'] = $row->wp_user_id;
            $temp['school_id'] = $row->school_id;
            $temp['teacher_id'] = $row->teacher_id;
            $created_date = date("m/d/Y", strtotime($row->created_date));
            $temp['created_date'] = $created_date;
            $action = '<div style="display: flex;" class="wcp-actions">';
            
            $action .= '<input type="button" value="Edit" class="btn btn-info"  onclick="wcp_edit_row(' . $id . ')">&nbsp; &nbsp;';
            $action .= "<input type='button' value='Delete' class='btn btn-danger' onclick='wcp_delete_row(" . $id . ")'>&nbsp;";
            $action .= "<input type='button' value='Assign' class='btn btn-danger' onclick='wcp_assign_row(" . $id . ",".$row->class_room_id.")'>&nbsp;";
            $action .= '</div>';
            
            $temp['action'] = $action;

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

    public function get_students_by_class_and_teacher($school_id, $teacher_id, $is_deleted = 1)
    {
        if (empty($school_id) && empty($teacher_id)) {
            return $this->get_students();
        } else {
            global $wpdb, $wp;
            $data = array();
            $obj_result = new \stdclass();
            $obj_result->is_success = false;
            //$requestData = $_REQUEST;
            //This is for Search
            $where = " WHERE school_id = " . $school_id . ' AND teacher_id = ' . $teacher_id;

            if ($is_deleted == 2) {
                $where .= ' and is_deleted = 0';
            } else if ($is_deleted == 1) {
                $where .= ' and is_deleted = 1';
            }

            $table_name = $this->table_name;
            $sql = "SELECT * FROM $table_name " . $where;
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
                $temp['full_name'] = stripslashes_deep(stripslashes_deep($row->full_name));
                $temp['wp_user_id'] = $row->wp_user_id;
                $temp['school_id'] = $row->school_id;
                $temp['teacher_id'] = $row->teacher_id;
                $temp['is_deleted'] = $row->is_deleted;
                $created_date = date("m/d/Y", strtotime($row->created_date));
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
    }


    public function get_students_array()
    {
        global $wpdb;
        $table_name = $this->table_name;
        $sql = "SELECT * FROM $table_name";
        $rows = $wpdb->get_results($sql);
        return $rows;
    }


    public function wcp_delete_student($id)
    {
        $table_name = $this->table_name;
        global $wpdb;
        if (isset($id)) {
            $wpdb->update(
                $table_name,
                array(
                    'is_deleted' => 1
                ),
                array('id' => $id)
            );
        }

        if (!empty($wpdb->last_error)) {
            return $wpdb->last_error;
        } else {
            return 'success';
        }
    }

    public function get_student_by_id($id, $inArray = false)
    {
        global $wpdb;
        $table_name = $this->table_name;
        $response = array();
        $response['status'] = 0;
        $response['row'] = array();
        if (isset($id)) {
            $response['status'] = 1;
            $row = $this->get_student($id);
            $response['row'] = $row;
            if($row){
                $wp_user_id = $row->wp_user_id;
                $user =  get_userdata($wp_user_id);
                if($user){
                    $response['row']->user_email = $user->data->user_email;
                    $response['row']->first_name = get_user_meta( $user->data->ID, 'first_name', true );
                    $response['row']->last_name = get_user_meta( $user->data->ID, 'last_name', true );
                    $response['row']->wp_user_id = $user->data->ID;
                }
                
            }
        }

        if ($inArray) {
            return $response['row'];
        } else {
            return json_encode($response);
        }
    }


    public function get_student($id)
    {
        global $wpdb;
        $json = [];
        $categories_str = [];
        $table_name = $this->table_name;
        $query = "SELECT * FROM {$table_name} WHERE id = " . esc_sql(trim($id));
        $row = $wpdb->get_row($query);
        $row->content = html_entity_decode(stripslashes_deep($row->content));
        $row->title = stripslashes_deep(stripslashes_deep($row->title));
        return $row;
    }

    public function get_student_by_wp_user_id($id)
    {
        global $wpdb;
        $table_name = $this->table_name;
        $query = "SELECT * FROM {$table_name} WHERE wp_user_id = " . esc_sql(trim($id));
        $row = $wpdb->get_row($query);
        return $row;
    }

    public function get_select2_json()
    {
        global $wpdb;
        $json = [];
        $query = 'SELECT * From ' . $this->table_name . ' WHERE  is_deleted=0 ';
        if (isset($_GET['q'])) {
            $query .= ' AND  title LIKE "%' . esc_sql($_GET['q']) . '%"';
        }
        $result = $wpdb->get_results($query, "ARRAY_A");
        if ($result) {
            foreach ($result as $val) {
                $json[] = ['id' => $val['id'], 'text' => stripslashes_deep($val['title'])];
            }
        }
        echo json_encode($json);
        exit();
    }


    ## Send invitation to teacher for enrolment in school
    public function student_invitation()
    {
        $validation = [
            $_REQUEST['school_reference'],
            $_REQUEST['teacher_reference'],
            $_REQUEST['student_email']
        ];
        $response = $this->student_invitation_validation($validation);

        if ($response) {
            //$body = get_site_url() . '/' . 'student-registration/' . '?school_ref=' . $_REQUEST['school_reference'] . '&teacher_ref=' . $_REQUEST['teacher_reference'] . '&email=' . $_REQUEST['student_email'];
            $body = get_site_url() . '/' . 'signup-now/' . '?school_ref=' . $_REQUEST['school_reference'] . '&query=' . $_REQUEST['teacher_reference'] .'&user_type=student&user_email='.$_REQUEST['student_email'];
            if(email_exists($_REQUEST['student_email'])) {
                return "userExists";
            }
            wcp_send_mail($_REQUEST['student_email'], 'Teacher Invite you to join school', $body);
            return "Sent";
            
        } else {
            return 'Missing Arguments';
        }
    }

    public function student_invitation_validation($validationCheck)
    {
        $response = true;
        foreach ($validationCheck as $value) {
            if (empty($value)) {
                $response = false;
                break;
            }
        }
        return $response;
    }

}

if (class_exists("WCP_Common_Student_Model")) {
    $WCP_Common_Student_Model = new WCP_Common_Student_Model();
}
