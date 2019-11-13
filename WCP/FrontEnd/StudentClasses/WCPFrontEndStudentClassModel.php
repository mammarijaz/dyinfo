<?php
class WCPFrontEndStudentClassModel
{
    public $wpdb;
    public $class_enrolment = 'wcp_class_enrolment';
    public $class_room = 'wcp_class_room';

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;

    }


    public function validateSaveClassRoom($array)
    {
        $response = true;
        foreach ($array as $value) {
            if (empty($value)) {
                $response = false;
                break;
            }
        }
        return $response;
    }

    ## Save class room
    public function saveClassRoom($array)
    {
        $removeTheseValueFromArray = [
            'action',
            'target',
            'edit_id',
        ];

        $validationArray = [
            $array['class_room_name'],
            $array['teacher_id'],
            $array['school_id'],
        ];
        $validationResponse = $this->validateSaveClassRoom($validationArray);

        if (!$validationResponse) {
            return 'Please fill out require fields';
        }


        $dataToPushInTable = [];

        foreach ($array as $key => $value) {
            if (!empty($value) && !in_array($key, $removeTheseValueFromArray)) {
                $dataToPushInTable[$key] = esc_sql(trim($value));
            }
        }

        if (empty($array['edit_id'])) {
            $this->wpdb->insert($this->class_room, $dataToPushInTable);
        } else {
            $this->wpdb->update($this->class_room, $dataToPushInTable, ['id' => esc_sql(trim($array['edit_id']))]);
        }


        $response = null;
        if (empty($this->wpdb->last_error)) {
            $response = 'OK';
        } else {
            $response = $this->wpdb->last_error;
        }

        return $response;
    }

    ## get list of all class room
    public function getAllClassRoom()
    {
        global $wpdb;
        $sql = "SELECT * FROM " . $this->class_room;
        $rows = $wpdb->get_results($sql);
        return $rows;
    }

    ## get all class room with the reference of the class enrolment of the students.
    public function getAllClassRoomWithClassEnrolment()
    {
        $sql = 'SELECT * FROM ' . $this->class_room .
            ' left join ' . $this->class_enrolment . ' 
                    on ' . $this->class_room . '.id = ' . $this->class_enrolment . '.class_room_id
                    left join ' . $this->wpdb->prefix . 'users
                    on ' . $this->class_enrolment . '.wp_user_id = ' . $this->wpdb->prefix . 'users.id';

//        return $sql;
        return $this->wpdb->get_results($sql, OBJECT_K);

    }

    ## get class by id
    public function getClassRoomByID($id)
    {
        if ($id) {
            global $wpdb;
            $sql = "SELECT * FROM " . $this->class_room . ' where id = ' . esc_sql(trim($id));
            $rows = $wpdb->get_row($sql);
            return $rows;
        } else {
            return null;
        }
    }

    ## get class by id
    public function getClassRoomBySchoolID_TeacherID($schoolID, $teacherID, $nonDeleted = false)
    {
        if ($schoolID && $teacherID) {
            global $wpdb;
            $sql = "SELECT * FROM " . $this->class_room . ' where school_id = "' . esc_sql(trim($schoolID)) . '" and teacher_id = "' . esc_sql(trim($teacherID)) . '"';

            if ($nonDeleted == 1) {
                ## get non deleted
                $sql .= ' and is_deleted = 0';
            } else if ($nonDeleted == 2) {
                ## get deleted records
                $sql .= ' and is_deleted = 1';
            }

            $rows = $wpdb->get_results($sql);
            return $rows;
        } else {
            return null;
        }
    }

    ## delete class room
    public function deleteClassRoom($id)
    {
        if (!empty($id)) {

            global $wpdb;

            $wpdb->update(
                $this->class_room,
                array(
                    'is_deleted' => 1
                ),
                array('id' => esc_sql(trim($id)))
            );

            ## return error if we have it.
            if (!empty($wpdb->last_error)) {
                return $wpdb->last_error;
            } else {
                return 'OK';
            }

        } else {
            return null;
        }


    }

    ## get Class Enrolment
    public function getClassRoomEnrolmentByID($id)
    {
        if ($id) {
            global $wpdb;
            $sql = "SELECT * FROM " . $this->class_enrolment . ' where id = ' . esc_sql(trim($id));
            $rows = $wpdb->get_row($sql);
            return $rows;
        } else {
            return null;
        }
    }

    ## get class enrolment by the reference of class_room
    public function getClassRoomEnrolmentByClassRoomID($ClassRoomID)
    {
        if ($ClassRoomID) {
            global $wpdb;
            $sql = "SELECT * FROM " . $this->class_enrolment . ' where class_room_id = ' . esc_sql(trim($ClassRoomID));
            $rows = $wpdb->get_results($sql);
            return $rows;
        } else {
            return (object)[];
        }
    }

    ## assign student to Class room.
    public function assignStudentToClassRoom($array)
    {
        $validationArray = [
            $array['class_room_id'],
            $array['wp_user_id'],
        ];

        $validationResponse = $this->validateSaveClassRoom($validationArray);

        $returnResponse = null;
        if ($validationResponse) {

            unset($array['action']);
            unset($array['target']);

            foreach ($array as $key => $value) {
                $array[$key] = esc_sql(trim($value));
            }

            $this->wpdb->insert($this->class_enrolment, $array);
            if ($this->wpdb->last_error) {
                $returnResponse = $this->wpdb->last_error;
            } else {
                $returnResponse = 'OK';
            }
        }

        return $returnResponse;

    }

    ## delete Class room enrolment

    ## delete class room
    public function deleteClassRoomEnrolment($id)
    {
        if (!empty($id)) {
            global $wpdb;
            $wpdb->delete($this->class_enrolment, ['id' => esc_sql(trim($id))]);
            ## return error if we have it.
            if (!empty($wpdb->last_error)) {
                return $wpdb->last_error;
            } else {
                return 'OK';
            }
        } else {
            return null;
        }


    }


}