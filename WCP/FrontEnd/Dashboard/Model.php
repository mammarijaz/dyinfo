<?php

class WCP_FrontEnd_Dashboard_Model
{
    public $wpdb;
    public $school_table = 'wcp_schools';
    public $teacher_table = 'wcp_teachers';

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;

    }

    ## send invitation to teacher
    public function sendInvitations()
    {
    }
}