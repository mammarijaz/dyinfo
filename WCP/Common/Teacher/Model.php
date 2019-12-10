<?php

class WCP_Common_Teacher_Model
{

    public function __construct()
    {

        $table_name = 'wcp_teachers';
        $this->table_name = $table_name;
    }

    public function wcp_add_teacher($postData)
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
                "message" => sprintf($errorMessage, __("Teacher Name", "wcp"))
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
            $createddate = current_time("Y-m-d H:i:s");
            $updateddate = current_time("Y-m-d H:i:s");

            $wpdb->insert(
                $table_name,
                array(
                    'full_name' => $full_name,
                    'school_id' => $school_id,
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

    public function wcp_edit_teacher($postData)
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
                "message" => sprintf($errorMessage, __("Teacher Name", "wcp"))
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
            $dataToUpdate['full_name'] = $full_name;

            ## if wp user id is given
            if (!empty($postData["wp_user_id"])) {
                //$wp_user_id = isset($postData["wp_user_id"]) ? esc_sql($postData["wp_user_id"]) : 0;
                $dataToUpdate['wp_user_id'] = esc_sql($postData["wp_user_id"]);
            }

            ## if school id is missing
            if (!empty($postData["school_id"])) {
                //$school_id = isset($postData["school_id"]) ? esc_sql($postData["school_id"]) : 0;
                $dataToUpdate['school_id'] = esc_sql($postData["school_id"]);
            }

            //$updateddate = current_time("Y-m-d H:i:s");
            $dataToUpdate['updated_date'] = current_time("Y-m-d H:i:s");


            $wpdb->update(
                $table_name,
//                array(
//                    'full_name' => $full_name,
//                    'school_id' => $school_id,
//                    'wp_user_id' => $wp_user_id,
//                    'updated_date' => $updateddate
//                ),
                $dataToUpdate,
                array('id' => $edit_id)
            );
            $id = $edit_id;

            $_REQUEST['object_id'] = $edit_id;

            if (!empty($wpdb->last_error)) {

                $str = htmlspecialchars($wpdb->last_result, ENT_QUOTES);
                $query = htmlspecialchars($wpdb->last_query, ENT_QUOTES);

                print "<div id='error'>
                <p class='wpdberror'><strong>WordPress database error:</strong> [$str]<br />
                <code>$query</code></p>
                </div>";

            } else {
                $response['success'] = 1;
                $response['status'] = 1;
                $response['urlredirect'] = '';
            }

//            if ($id) {
//                $response['success'] = 1;
//                $response['status'] = 1;
//                $response['urlredirect'] = '';
//            } else {
//                $response['error'] = 1;
//                $response['status'] = 0;
//            }
        }
        return $response;
    }


    public function get_teachers($requestData)
    {

        global $wpdb, $wp;
        $data = array();
        $obj_result = new \stdclass();
        $obj_result->is_success = false;
        //$requestData = $_REQUEST;
        //This is for Search
        $where = " WHERE 1 = 1 AND is_deleted = 0 ";
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

    public function get_teachers_array()
    {
        global $wpdb;
        $table_name = $this->table_name;
        $sql = "SELECT * FROM $table_name";
        $rows = $wpdb->get_results($sql);
        return $rows;
    }


    public function wcp_delete_teacher($id)
    {
        $table_name = $this->table_name;
        global $wpdb;
        if (!empty($id)) {
            $wpdb->update(
                $table_name,
                array(
                    'is_deleted' => 1
                ),
                array('id' => $id)
            );
        }

        ## return error if we have it.
        if (!empty($wpdb->last_error)) {
            $response =  $wpdb->last_error;
        } else {
            $response=  'success';
        }
        return $response;
    }

    public function get_teacher_by_id($id, $responseINArray = false)
    {
        global $wpdb;
        $table_name = $this->table_name;
        $response = array();
        $response['status'] = 0;
        $response['row'] = array();
        if (isset($id)) {
            $response['status'] = 1;
            $response['row'] = $this->get_teacher($id);
        }
        if ($responseINArray) {
            return $response['row'];
        } else {
            return json_encode($response);
        }
    }

    public function get_teacher_by_wp_user_id($id)
    {
        global $wpdb;
        $json = [];
        $categories_str = [];
        $table_name = $this->table_name;
        $query = "SELECT * FROM {$table_name} WHERE wp_user_id = " . esc_sql(trim($id));
        $row = $wpdb->get_row($query);
        $row->content = html_entity_decode(stripslashes_deep($row->content));
        $row->title = stripslashes_deep(stripslashes_deep($row->title));
        return $row;
    }

    public function get_teacher_by_school_id($school_id)
    {
        global $wpdb;
        $table_name = $this->table_name;
        $sql = "SELECT * FROM $table_name where school_id = " . $school_id;
        $rows = $wpdb->get_results($sql);
        return $rows;
    }


    public function get_teacher($id)
    {
        global $wpdb;
        $json = [];
        $categories_str = [];
        $table_name = $this->table_name;
        $query = "SELECT * FROM {$table_name} WHERE id = " . esc_sql(trim($id));
        $row = $wpdb->get_row($query);
        if (!empty($row)) {
            $row->content = html_entity_decode(stripslashes_deep($row->content));
            $row->title = stripslashes_deep(stripslashes_deep($row->title));
        }
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
    public function teacher_invitation()
    {
        $validation = [
            $_REQUEST['school_reference'],
            $_REQUEST['teacher_email']
        ];
        $response = $this->teacher_invitation_validation($validation);

        if ($response) {
            $body = get_site_url() . '/' . 'signup-now/' . '?query=' . $_REQUEST['school_reference'] . '&user_type=teacher&user_email='.$_REQUEST['teacher_email'];
            //$body = get_site_url() . '/' . 'teacher-registration/' . '?query=' . $_REQUEST['school_reference'] . '&ref=' . $_REQUEST['teacher_email'];
             $sent = wcp_send_mail($_POST['teacher_email'], 'School Invite', $body);
             if(email_exists($_REQUEST['teacher_email'])) {
                return "userExists";
             }
             return "Sent";
        } else {
            return 'Missing Arguments';
        }
    }

    public function teacher_invitation_validation($validationCheck)
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

if (class_exists("WCP_Common_Teacher_Model")) {
    $WCP_Common_Teacher_Model = new WCP_Common_Teacher_Model();
}
