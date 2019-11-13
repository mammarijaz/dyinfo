<?php

global $WCP_Common_Teacher_Model, $WCP_Common_Teacher_Model;
$WCPFrontEndStudentClassModel = new WCPFrontEndStudentClassModel();
## get user from the wp_user table
$current_user = wp_get_current_user();


if (!empty($current_user->id) && in_array('wcp_teacher', (array)$current_user->roles)) {
    ## get the school reference

    ## now getting the user details from the teacher table.
    $teacherFromTeacherTable = $WCP_Common_Teacher_Model->get_teacher_by_wp_user_id($current_user->id, true);
    ## get the list of class of room

    $listOfClassRoom = $WCPFrontEndStudentClassModel->getClassRoomBySchoolID_TeacherID($teacherFromTeacherTable->school_id, $teacherFromTeacherTable->id);

    ?>

    <a target="_blank" href="<?php echo home_url('/make-class-room/') ?>"> Make Class room </a>
    <a target="_blank" href="<?php echo home_url('/make-class-room/') ?>"> Assign student to class room </a>

    <p id="err_msg"></p>

    <!-- LIST OF TEACHER -->
    <h3>Class Room List</h3>
    <hr>
    <span id="remoteResource" action="<?php echo admin_url('admin-ajax.php'); ?>"></span>
    <table id="teacher___table">
        <thead>
        <tr>
            <th>Class Room Name</th>
            <th>Purpose</th>
            <th>Class in a week</th>
            <th>Class duration</th>
            <th>Class from / to</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if (!empty($listOfClassRoom)) {
            foreach ($listOfClassRoom as $value) {

                $class = null;
                if ($value->is_deleted == 1) {
                    $class = 'style="background-color:red;"';
                }

                ?>

                <tr id="teacher_<?php echo $value->id ?>" <?php echo $class ?>>
                    <td><?php echo $value->class_room_name ?></td>
                    <td> <?php echo $value->class_room_purpose ?>  </td>
                    <td> <?php echo $value->class_in_a_week ?> </td>
                    <td> <?php echo $value->class_duration ?> </td>
                    <td> <?php echo $value->class_start_date ?> / <?php echo $value->class_end_date ?> </td>
                    <td id="teacher_action__<?php echo $value->id ?>">


                        <?php if ($value->is_deleted == 1) { ?>
                            This user has been deleted
                        <?php } else { ?>

                            <li data-attr="<?php echo $value->id ?>">
                                <a href="<?php echo home_url('/make-class-room/') . '?ref=' . base64_encode($value->id) ?>">Edit</a>
                            </li>

                            <li class="deleteClassRoom" data-attr="<?php echo $value->id ?>">
                                <a href="#">Delete</a>
                            </li>


                            <li data-attr="<?php echo $value->id ?>">
                                <a href="<?php echo home_url('/detail-of-class-room/') . '?ref=' . base64_encode($value->id) ?>">View</a>
                            </li>

                        <?php } ?>

                    </td>
                </tr>

                <!--                <tr>-->
                <!--                    <th>Sun, Start / End Time</th>-->
                <!--                    <th>Mon, Start / End Time</th>-->
                <!--                    <th>Tue, Start / End Time</th>-->
                <!--                    <th>Wed, Start / End Time/th>-->
                <!--                    <th>Thu, Start / End Time</th>-->
                <!--                    <th>Fri, Start / End Time</th>-->
                <!--                    <th>Sat, Start / End Time</th>-->
                <!--                </tr>-->
                <!--                -->
                <!--                <tr id="ref__--><?php //echo $value->id ?><!--">-->
                <!---->
                <!--                    <td>--><?php //echo $value->class_sun_start_time . ' / ' . $value->class_sun_end_time ?><!--</td>-->
                <!--                    <td>--><?php //echo $value->class_mon_start_time . ' / ' . $value->class_mon_end_time ?><!--</td>-->
                <!--                    <td>--><?php //echo $value->class_tue_start_time . ' / ' . $value->class_tue_end_time ?><!--</td>-->
                <!--                    <td>--><?php //echo $value->class_wed_start_time . ' / ' . $value->class_wed_end_time ?><!--</td>-->
                <!--                    <td>--><?php //echo $value->class_thu_start_time . ' / ' . $value->class_thu_end_time ?><!--</td>-->
                <!--                    <td>--><?php //echo $value->class_fri_start_time . '/ ' . $value->class_fri_end_time ?><!--</td>-->
                <!--                    <td>--><?php //echo $value->class_sat_start_time . '/ ' . $value->class_sat_end_time ?><!--</td>-->
                <!--                </tr>-->

            <?php }
        }
        ?>
        </tbody>
    </table>


<?php } else { ?>
    <h1> You'r not allowed to view this page</h1>
<?php } ?>
