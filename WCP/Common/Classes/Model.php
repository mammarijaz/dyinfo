<?php

class WCP_Common_Class_Model
{

    public function __construct()
    {

        $table_name = 'wcp_class_room';
        $this->table_name = $table_name;
    }

    public function wcp_add_class($postData)
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
        if (!isset($postData['class_room_name']) || trim($postData['class_room_name']) == "") {
            $error = array(
                "key" => "class_room_name",
                "message" => sprintf($errorMessage, __("Class Name", "wcp"))
            );
            // $errorArray[] = $error;
        }

        if (!empty($errorArray)) {
            $response['error'] = 1;
            $response['status'] = 0;
            $response['errors'] = $errorArray;
        } else {

            $class_room_name = isset($postData["class_room_name"]) ? esc_sql($postData["class_room_name"]) : "";
            $class_room_descrption = isset($postData["class_room_descrption"]) ? esc_sql($postData["class_room_descrption"]) : "";
            $school_id = isset($postData["school_id"]) ? esc_sql($postData["school_id"]) : 0;
            $teacher_id = isset($postData["teacher_id"]) ? esc_sql($postData["teacher_id"]) : 0;

            $class_start_date =  date('Y-m-d', strtotime($postData['class_start_date']));
            $class_end_date =  date('Y-m-d', strtotime($postData['class_end_date']));

            $createddate = current_time("Y-m-d H:i:s");
            $updateddate = current_time("Y-m-d H:i:s");

            $wpdb->insert(
                $table_name,
                array(
                    'class_room_name' => $class_room_name,
                    'school_id' => $school_id,
                    'teacher_id' => $teacher_id,
                    'class_start_date' => $class_start_date,
                    'class_end_date' => $class_end_date,
                    'class_room_descrption' => $class_room_descrption,
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

    public function wcp_edit_class($postData)
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
        if (!isset($postData['class_room_name']) || trim($postData['class_room_name']) == "") {
            $error = array(
                "key" => "class_room_name",
                "message" => sprintf($errorMessage, __("Class Name", "wcp"))
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

            $class_room_name = isset($postData["class_room_name"]) ? esc_sql($postData["class_room_name"]) : "";
            $dataToUpdate['class_room_name'] = $class_room_name;

            if (!empty($postData["school_id"])) {
                $school_id = esc_sql($postData["school_id"]);
                $dataToUpdate['school_id'] = $school_id;
            }

            if (!empty($postData['teacher_id'])) {
                $dataToUpdate['teacher_id'] = esc_sql($postData["teacher_id"]);
            }

            if (!empty($postData['class_room_purpose'])) {
                $dataToUpdate['class_room_purpose'] = esc_sql($postData["class_room_purpose"]);
            }
            if (!empty($postData['class_room_descrption'])) {
                $dataToUpdate['class_room_descrption'] = esc_sql($postData["class_room_descrption"]);
            }
            if (!empty($postData['class_in_a_week'])) {
                $dataToUpdate['class_in_a_week'] = esc_sql($postData["class_in_a_week"]);
            }
            if (!empty($postData['class_duration'])) {
                $dataToUpdate['class_duration'] = esc_sql($postData["class_duration"]);
            }
            if (!empty($postData['class_sun_start_time'])) {
                $class_sun_start_time =  date('H:i:s', strtotime($postData['class_sun_start_time']));
                $dataToUpdate['class_sun_start_time'] = esc_sql($class_sun_start_time);
            }
            if (!empty($postData['class_sun_end_time'])) {
                $class_sun_end_time =  date('H:i:s', strtotime($postData['class_sun_end_time']));
                $dataToUpdate['class_sun_end_time'] = esc_sql($class_sun_end_time);
            }
            if (!empty($postData['class_mon_start_time'])) {
                $class_mon_start_time =  date('H:i:s', strtotime($postData['class_mon_start_time']));
                $dataToUpdate['class_mon_start_time'] = esc_sql($class_mon_start_time);
            }
            if (!empty($postData['class_mon_end_time'])) {
                $class_mon_end_time =  date('H:i:s', strtotime($postData['class_mon_end_time']));
                $dataToUpdate['class_mon_end_time'] = esc_sql($class_mon_end_time);
            }
            if (!empty($postData['class_tue_start_time'])) {
                $class_tue_start_time =  date('H:i:s', strtotime($postData['class_tue_start_time']));
                $dataToUpdate['class_tue_start_time'] = esc_sql($class_tue_start_time);
            }
            if (!empty($postData['class_tue_end_time'])) {
                $class_tue_end_time =  date('H:i:s', strtotime($postData['class_tue_end_time']));
                $dataToUpdate['class_tue_end_time'] = esc_sql($class_tue_end_time);
            }
            if (!empty($postData['class_wed_start_time'])) {
                $class_wed_start_time =  date('H:i:s', strtotime($postData['class_wed_start_time']));
                $dataToUpdate['class_wed_start_time'] = esc_sql($class_wed_start_time);
            }
            if (!empty($postData['class_wed_end_time'])) {
                $class_wed_end_time =  date('H:i:s', strtotime($postData['class_wed_end_time']));
                $dataToUpdate['class_wed_end_time'] = esc_sql($class_wed_end_time);
            }
            if (!empty($postData['class_thu_start_time'])) {
                $class_thu_start_time =  date('H:i:s', strtotime($postData['class_thu_start_time']));
                $dataToUpdate['class_thu_start_time'] = esc_sql($class_thu_start_time);
            }
            if (!empty($postData['class_thu_end_time'])) {
                $class_thu_end_time =  date('H:i:s', strtotime($postData['class_thu_end_time']));
                $dataToUpdate['class_thu_end_time'] = esc_sql($class_thu_end_time);
            }
            if (!empty($postData['class_fri_start_time'])) {
                $class_fri_start_time =  date('H:i:s', strtotime($postData['class_fri_start_time']));
                $dataToUpdate['class_fri_start_time'] = esc_sql($class_fri_start_time);
            }
            if (!empty($postData['class_fri_end_time'])) {
                $class_fri_end_time =  date('H:i:s', strtotime($postData['class_fri_end_time']));
                $dataToUpdate['class_fri_end_time'] = esc_sql($class_fri_end_time);
            }
            if (!empty($postData['class_sat_start_time'])) {
                $class_sat_start_time =  date('H:i:s', strtotime($postData['class_sat_start_time']));
                $dataToUpdate['class_sat_start_time'] = esc_sql($class_sat_start_time);
            }
            if (!empty($postData['class_sat_end_time'])) {
                $class_sat_end_time =  date('H:i:s', strtotime($postData['class_sat_end_time']));
                $dataToUpdate['class_sat_end_time'] = esc_sql($class_sat_end_time);
            }
            if (!empty($postData['class_room_descrption'])) {

                $dataToUpdate['class_room_descrption'] = esc_sql($postData["class_room_descrption"]);
            }
            if (!empty($postData['class_start_date'])) {
                $class_start_date =  date('Y-m-d', strtotime($postData['class_start_date']));
                $dataToUpdate['class_start_date'] = esc_sql($class_start_date);
            }
            if (!empty($postData['class_end_date'])) {
                $class_end_date =  date('Y-m-d', strtotime($postData['class_end_date']));
                $dataToUpdate['class_end_date'] = esc_sql($class_end_date);
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


    public function get_classes($requestData)
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
            $temp['class_room_name'] = stripslashes_deep(stripslashes_deep($row->class_room_name));
            $temp['class_room_purpose'] = $row->class_room_purpose;
            $temp['class_room_descrption'] = $row->class_room_descrption;
            $temp['class_in_a_week'] = $row->class_in_a_week;
            $temp['class_duration'] = $row->class_duration;
            $temp['school_id'] = $row->school_id;
            $temp['teacher_id'] = $row->teacher_id;
            $class_end_date = date("m/d/Y", strtotime($row->class_end_date));
            $class_start_date = date("m/d/Y", strtotime($row->class_start_date));
            $created_date = date("m/d/Y", strtotime($row->created_date));
            $temp['created_date'] = $row->created_date;
            $temp['class_start_date'] = $class_start_date;
            $temp['class_end_date'] = $class_end_date;
            $action = '<div style="display: flex;" class="wcp-actions">';
            
            $action .= '<input type="button" value="Edit" class="btn btn-info"  onclick="wcp_edit_row(' . $id . ')">&nbsp; &nbsp;';
            $action .= "<input type='button' value='Delete' class='btn btn-danger' onclick='wcp_delete_row(" . $id . ")'>&nbsp;";
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

    public function get_classes_by_class_and_teacher($school_id, $teacher_id, $is_deleted = 1)
    {
        if (empty($school_id) && empty($teacher_id)) {
            return $this->get_classes();
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
                $temp['class_room_name'] = $row->class_room_name;
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


    public function get_classes_array()
    {
        global $wpdb;
        $table_name = $this->table_name;
        $sql = "SELECT * FROM $table_name";
        $rows = $wpdb->get_results($sql);
        return $rows;
    }


    public function wcp_delete_class($id)
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

    public function get_class_by_id($id, $inArray = false)
    {
        global $wpdb;
        $table_name = $this->table_name;
        $response = array();
        $response['status'] = 0;
        $response['row'] = array();
        if (isset($id)) {
            $response['status'] = 1;
            $response['row'] = $this->get_class($id);
        }

        if ($inArray) {
            return $response['row'];
        } else {
            return json_encode($response);
        }
    }


    public function get_class($id)
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

    public function get_class_by_wp_user_id($id)
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


}

if (class_exists("WCP_Common_Class_Model")) {
    $WCP_Common_Class_Model = new WCP_Common_Class_Model();
}
