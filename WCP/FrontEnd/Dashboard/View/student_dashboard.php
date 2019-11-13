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

    ?>

    <p id="err_msg"></p>

    <!--  School Detail-->
    <h3>School Detail</h3>
    <hr>
    <?php if (!empty($SchoolFromSchoolTable)) { ?>
        <table id="school___table">
            <thead>
            <tr>
                <th>Ref</th>
                <th>School Name</th>
                <th>School Phone</th>
                <th>School City</th>
                <th>School Country</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?php echo $SchoolFromSchoolTable->id ?></td>
                <td><?php echo $SchoolFromSchoolTable->school_name ?></td>
                <td><?php echo $SchoolFromSchoolTable->school_phone ?></td>
                <td><?php echo $SchoolFromSchoolTable->school_city ?></td>
                <td><?php echo $SchoolFromSchoolTable->school_country ?></td>
            </tr>
            </tbody>
        </table>
    <?php } else { ?>
        <h4>No School Found in which this user has enrolled. </h4>
    <?php } ?>

    <!--  TEACHER Detail -->
    <h3>Teacher Detail</h3>
    <hr>
    <?php if (!empty($teacherFromTeacherTable)) { ?>
        <table id="teacher___table_">
            <thead>
            <tr>
                <th>Ref</th>
                <th>Teacher Name</th>
            </tr>
            </thead>
            <tbody>
            <tr>

                <td><?php echo $teacherFromTeacherTable->id ?></td>
                <td><?php echo $teacherFromTeacherTable->full_name ?></td>
            </tr>
            </tbody>
        </table>
    <?php } else { ?>
        <h4> No teacher has found who invites you </h4>
    <?php } ?>

    <!--My Details-->

    <!--  TEACHER Detail -->
    <h3>My Detail</h3>
    <hr>
    <table id="my___table">
        <thead>
        <tr>
            <th>Ref</th>
            <th> Name</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><?php echo $studentDetailFromStudentTable->id ?></td>
            <td><?php echo $studentDetailFromStudentTable->full_name ?></td>
        </tr>
        </tbody>
    </table>


<?php } else { ?>
    <h1> You'r not allowed to view this page</h1>
<?php } ?>

