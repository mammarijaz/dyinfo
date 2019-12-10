<?php

global $WCP_Common_Student_Model, $WCP_Common_Teacher_Model, $WCP_Common_School_Model;
## get user from the wp_user table
$current_user = wp_get_current_user();


if (!empty($current_user->id) && in_array('wcp_student', (array)$current_user->roles)) {
## get the school reference

## now getting the user details from the teacher table.

$studentDetailFromStudentTable = $WCP_Common_Student_Model->get_student_by_wp_user_id($current_user->id);


if (!empty($studentDetailFromStudentTable)) {
    if (!empty($studentDetailFromStudentTable->teacher_id)) {
        $teacherFromTeacherTable = $WCP_Common_Teacher_Model->get_teacher_by_id($studentDetailFromStudentTable->teacher_id, true);
    }
    if (!empty($studentDetailFromStudentTable->school_id)) {
        $SchoolFromSchoolTable = $WCP_Common_School_Model->get_school($studentDetailFromStudentTable->school_id);
    }
}
$user_id = $teacherFromTeacherTable->wp_user_id;
$user_info = get_userdata($user_id);
$teacher_email = $user_info->user_email;



$user_id=$studentDetailFromStudentTable->wp_user_id;
$user_info = get_userdata($user_id);
$student_email = $user_info->user_email;


?>
<div class="container">
    <p id="err_msg"></p>

    <!--  School Detail-->

    <div class="col" style="width: 33%;float: left">

        <?php if (!empty($SchoolFromSchoolTable)) { ?>
            <!--        <table id="school___table">-->
            <!--            <thead>-->
            <!--            <tr>-->
            <!--                <th>Ref</th>-->
            <!--                <th>School Name</th>-->
            <!--                <th>School Phone</th>-->
            <!--                <th>School City</th>-->
            <!--                <th>School Country</th>-->
            <!--            </tr>-->
            <!--            </thead>-->
            <!--            <tbody>-->
            <!--            <tr>-->
            <!--                <td>--><?php //echo $SchoolFromSchoolTable->id ?><!--</td>-->
            <!--                <td>--><?php //echo $SchoolFromSchoolTable->school_name ?><!--</td>-->
            <!--                <td>--><?php //echo $SchoolFromSchoolTable->school_phone ?><!--</td>-->
            <!--                <td>--><?php //echo $SchoolFromSchoolTable->school_city ?><!--</td>-->
            <!--                <td>--><?php //echo $SchoolFromSchoolTable->school_country ?><!--</td>-->
            <!--            </tr>-->
            <!--            </tbody>-->
            <!--        </table>-->



            <h5 class="card-title">School Detail</h5>
            <h6 class="card-subtitle"><?php echo $SchoolFromSchoolTable->school_name ?></h6>
            <p class="card-text"><?php echo $SchoolFromSchoolTable->school_phone ?></p>
            <p class="card-text"><?php echo $SchoolFromSchoolTable->school_city ?></p>
            <p class="card-text"><?php echo $SchoolFromSchoolTable->school_country ?></p>


        <?php } else { ?>
            <h4>No School Found in which this user has enrolled. </h4>
        <?php } ?>
    </div>
    <!--  TEACHER Detail -->
    <div class="col" style="width: 33%;float: left">


        <!--                <h3>Teacher Detail</h3>-->
        <!--    <hr>-->
        <?php if (!empty($teacherFromTeacherTable)) { ?>
            <!--        <table id="teacher___table_">-->
            <!--            <thead>-->
            <!--            <tr>-->
            <!--                <th>Ref</th>-->
            <!--                <th>Teacher Name</th>-->
            <!--            </tr>-->
            <!--            </thead>-->
            <!--            <tbody>-->
            <!--            <tr>-->
            <!---->
            <!--                <td>--><?php //echo $teacherFromTeacherTable->id ?><!--</td>-->
            <!--                <td>--><?php //echo $teacherFromTeacherTable->full_name ?><!--</td>-->
            <!--            </tr>-->
            <!--            </tbody>-->
            <!--        </table>-->
            <h5 class="card-title">Teacher Detail</h5>
            <h6 class="card-subtitle"><?php echo $teacherFromTeacherTable->full_name ?></h6>
            <p class="card-text"><?php echo $teacher_email ?></p>





        <?php } else { ?>
            <h4> No teacher has found who invites you </h4>
        <?php } ?>
    </div>
    <!--Teacher Detail-->
    <!--  TEACHER Detail -->
    <div class="col" style="width: 33%;float: left">


        <!--                <h3>Teacher Detail</h3>-->
        <!--    <hr>-->
        <?php if (!empty($teacherFromTeacherTable)) { ?>
            <!--        <table id="teacher___table_">-->
            <!--            <thead>-->
            <!--            <tr>-->
            <!--                <th>Ref</th>-->
            <!--                <th>Teacher Name</th>-->
            <!--            </tr>-->
            <!--            </thead>-->
            <!--            <tbody>-->
            <!--            <tr>-->
            <!---->
            <!--                <td>--><?php //echo $teacherFromTeacherTable->id ?><!--</td>-->
            <!--                <td>--><?php //echo $teacherFromTeacherTable->full_name ?><!--</td>-->
            <!--            </tr>-->
            <!--            </tbody>-->
            <!--        </table>-->
            <h5 class="card-title">Student Detail</h5>
            <h6 class="card-subtitle"><?php echo $studentDetailFromStudentTable->full_name ?></h6>
            <p class="card-text"><?php echo $student_email ?></p>

            

        <?php } else { ?>
            <h4> No teacher has found who invites you </h4>
        <?php } ?>
    </div>





    <?php } else { ?>
        <h1> You'r not allowed to view this page</h1>
    <?php } ?>

</div>