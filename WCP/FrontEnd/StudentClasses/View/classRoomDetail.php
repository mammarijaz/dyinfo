<?php
global $WCP_Common_Student_Model, $WCP_Common_Teacher_Model;
$WCPFrontEndStudentClassModel = new WCPFrontEndStudentClassModel();

## get user from the wp_user table
$current_user = wp_get_current_user();


if (!empty($current_user->id) && in_array('wcp_teacher', (array)$current_user->roles)) {
    ## get the school reference
    ## now getting the user details from the teacher table.
    // $teacherFromTeacherTable = $WCP_Common_Teacher_Model->get_teacher_by_wp_user_id($current_user->id, true);

    ## list of all non deleted Class rooms

    ## list of all student which is attached is to teacher and get the non-deleted user.
    // $listOfAttachedStudentNonDeleted = $WCP_Common_Student_Model->get_students_by_class_and_teacher($teacherFromTeacherTable->school_id, $teacherFromTeacherTable->id, 1);
    //  $listOfAttachedStudentNonDeleted = empty($listOfAttachedStudentNonDeleted['data']) ? [] : $listOfAttachedStudentNonDeleted['data'];


    ## get the class room if user wants to edit.
    if (!empty($_GET['ref'])) {
        ## Class room
        $ClassRoom = $WCPFrontEndStudentClassModel->getClassRoomByID(base64_decode($_GET['ref']));
        ## list of class room enrolment
        $classRoomEnrolment = $WCPFrontEndStudentClassModel->getClassRoomEnrolmentByClassRoomID($ClassRoom->id);
    }

    if (!empty($ClassRoom->id)) {

        ?>
        <p id="err_msg"></p>
        <span id="remoteResource" action="<?php echo admin_url('admin-ajax.php'); ?>"></span>

        <!-- Make Class Room -->
        <h3>Class Room Detail</h3>
        <a target="_blank" href="<?php echo home_url('/list-of-class-rooms/') ?>"> Class room list </a>
        <hr>
        <!-- Class room detail-->
        <table>
            <thead>
            <tr>
                <th>Class-room name</th>
                <th>Class in a week</th>
                <th>Class Duration</th>
                <th>Class Purpose</th>
                <th>Class Description</th>
                <th>Class Start Date</th>
                <th>Class End Date</th>
                <th>Is Deleted</th>

            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?php echo $ClassRoom->class_room_name ?></td>
                <td><?php echo $ClassRoom->class_in_a_week ?></td>
                <td><?php echo $ClassRoom->class_duration ?></td>
                <td><?php echo $ClassRoom->class_room_purpose ?></td>
                <td><?php echo $ClassRoom->class_room_descrption ?></td>
                <td><?php echo $ClassRoom->class_start_date ?></td>
                <td><?php echo $ClassRoom->class_end_date ?></td>
                <td><?php echo empty ($ClassRoom->is_deleted) ? 'Yes' : 'No' ?></td>
            </tr>
            </tbody>
        </table>

        <!-- Class Room Time-->
        <table>
            <thead>
            <tr>
                <th>Mon Start/End Time</th>
                <th>Tue Start/End Time</th>
                <th>Wed Start/End Time</th>
                <th>Thu Start/End Time</th>
                <th>Fri Start/End Time</th>
                <th>Sat Start/End Time</th>
                <th>Sun Start/End Time</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?php echo $ClassRoom->class_sun_start_time . ' / ' . $ClassRoom->class_sun_end_time ?></td>
                <td><?php echo $ClassRoom->class_mon_start_time . ' / ' . $ClassRoom->class_mon_end_time ?></td>
                <td><?php echo $ClassRoom->class_tue_start_time . ' / ' . $ClassRoom->class_tue_end_time ?></td>
                <td><?php echo $ClassRoom->class_wed_start_time . ' / ' . $ClassRoom->class_wed_end_time ?></td>
                <td><?php echo $ClassRoom->class_thu_start_time . ' / ' . $ClassRoom->class_thu_end_time ?></td>
                <td><?php echo $ClassRoom->class_fri_start_time . '/ ' . $ClassRoom->class_fri_end_time ?></td>
                <td><?php echo $ClassRoom->class_sat_start_time . '/ ' . $ClassRoom->class_sat_end_time ?></td>
            </tr>
            </tbody>
        </table>


        <h3>List of Student Enrolment in Class</h3>
        <hr>
        <table id="school___table">
            <thead>
            <tr>
                <th>Student</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (!empty($classRoomEnrolment)) {
                foreach ($classRoomEnrolment as $value) {

                    $class = null;
                    if ($value->is_deleted == 1) {
                        $class = 'style="background-color:red;"';
                    }

                    ?>

                    <tr id="classEnrolment_<?php echo $value->id ?>" <?php echo $class ?>>
                        <td><?php echo !empty($WCP_Common_Student_Model->get_student_by_id($value->wp_user_id, true)->full_name) ? $WCP_Common_Student_Model->get_student_by_id($value->wp_user_id, true)->full_name : '' ?></td>
                        <td id="teacher_action__<?php echo $value->id ?>">

                            <?php if ($value->is_deleted == 1) { ?>
                                This user has been deleted
                            <?php } else { ?>

                                <li class="deleteClassRoomEnrolment" data-attr="<?php echo $value->id ?>">
                                    <a href="#">Delete</a>
                                </li>

                            <?php } ?>

                        </td>
                    </tr>

                <?php }
            }
            ?>
            </tbody>
        </table>


    <?php } else { ?>
        <div class="alert alert-danger signup-error" style="display:none">
            <strong>Danger!</strong> You'r not allowed to view this page
        </div>
    <?php }
} else { ?>

    <div class="alert alert-danger signup-error" style="display:none">
        <strong>Danger!</strong> You'r not allowed to view this page
    </div>
<?php } ?>
