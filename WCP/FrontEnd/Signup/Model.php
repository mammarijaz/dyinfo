<?php

class WCP_FrontEnd_Signup_Model
{
        public $wpdb;
        public $school_table = 'wcp_schools';
        public $teacher_table = 'wcp_teachers';

        public function __construct()
        {
            global $wpdb;
            $this->wpdb = $wpdb;

        }

    /**
     * School registration
     * */

    ## get data from school registration form
    public function getSchoolRegistration($studentID = null, $columnKey = null, $columnValue = null, $queryVariable = 'get_var')
    {
        $response = null;
        if (!empty($studentID)) {
            $response = $this->wpdb->get_var($this->wpdb->prepare('select * from '  . $this->school_table . ' where id = %d', $studentID));
        } else if (!empty($columnKey) && !empty($columnValue)) {
            $response = $this->wpdb->$queryVariable($this->wpdb->prepare('select * from '  . $this->school_table . ' where ' . $columnKey . ' = %s', $columnValue));
//            $response = ($this->wpdb->prepare('select * from ' . $this->wpdb->prefix . $this->school_table . ' where ' . $columnKey . ' = %s', $columnValue));
        }

        if (empty($this->wpdb->last_error)) {
            return $response;
        } else {
            return $this->wpdb->last_error;
        }

    }


    /*
     * Teacher Recognization
     *
     * */

    ## get data from school registration form
    public function getTeacherRegistration($teacher = null, $columnKey = null, $columnValue = null, $queryVariable = 'get_var')
    {
        $response = null;
        if (!empty($teacher)) {
            $response = $this->wpdb->get_var($this->wpdb->prepare('select * from '  . $this->teacher_table . ' where id = %d', $teacher));
        } else if (!empty($columnKey) && !empty($columnValue)) {
            $response = $this->wpdb->$queryVariable($this->wpdb->prepare('select * from ' . $this->teacher_table . ' where ' . $columnKey . ' = %s', $columnValue));
        }

        if (empty($this->wpdb->last_error)) {
            return $response;
        } else {
            return $this->wpdb->last_error;
        }

    }



}

if (class_exists("WCP_FrontEnd_Signup_Model")) {
    $WCP_FrontEnd_Signup_Model = new WCP_FrontEnd_Signup_Model();
}
