<style>
    .modal-backdrop {
        z-index: unset;
    }
</style>


<?php

global $WCP_Common_Student_Model, $WCP_Common_Teacher_Model;
## get user from the wp_user table
$current_user = wp_get_current_user();


if (!empty($current_user->id) && in_array('wcp_teacher', (array)$current_user->roles)) {
    ## get the school reference

    ## now getting the user details from the teacher table.
    $teacherFromTeacherTable = $WCP_Common_Teacher_Model->get_teacher_by_wp_user_id($current_user->id, true);

    ?>

    <a target="_blank" href="<?php echo home_url('/make-class-room/') ?>"> Make Class room </a>
    <a target="_blank" href="<?php echo home_url('/make-class-room/') ?>"> Assign student to class room </a>
    <a target="_blank" href="<?php echo home_url('/list-of-class-rooms/') ?>"> Class room list </a>

    <p id="err_msg"></p>

    <!-- LIST OF TEACHER -->
    <h3>Student List</h3>
    <hr>
    <table id="teacher___table">
        <thead>
        <tr>
            <th>Ref</th>
            <th>Student Name</th>
            <th>Student Email</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php

        $student_list = $WCP_Common_Student_Model->get_students_by_class_and_teacher($teacherFromTeacherTable->school_id, $teacherFromTeacherTable->id);
        $student_list = !empty($student_list['data']) ? $student_list['data'] : null;
        if (!empty($student_list)) {
            foreach ($student_list as $value) {

                $class = null;
                if ($value['is_deleted'] == 1) {
                    $class = 'style="background-color:red;"';
                }

                ?>
                <tr id="teacher_<?php echo $value['id'] ?>" <?php echo $class ?>>
                    <td id="edit_id_<?php echo $value['id'] ?>"><?php echo !empty($value['id']) ? $value['id'] : '' ?></td>
                    <td id="full_name_<?php echo $value['id'] ?>"><?php echo !empty($value['full_name']) ? $value['full_name'] : '' ?></td>
                    <td id="user_email_<?php echo $value['id'] ?>"><?php echo !empty(get_userdata($value['wp_user_id'])) && get_userdata($value['wp_user_id'])->has_prop('user_email') ? get_userdata($value['wp_user_id'])->get('user_email') : '' ?></td>
                    <td id="teacher_action__<?php echo $value['id'] ?>">

                        <?php if ($value['is_deleted'] == 1) { ?>
                            This user has been deleted
                        <?php } else { ?>
                            <div class="dropdown">
                                <button class="btn btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                    Action
                                    <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li class="edit-teacher" data-attr="<?php echo $value['id'] ?>"><a
                                                href="#">Edit</a></li>
                                    <li class="deleteStudent" data-attr="<?php echo $value['id'] ?>"><a
                                                href="#">Delete</a>
                                    </li>

                                </ul>
                            </div>
                        <?php }
                        ?>

                    </td>
                </tr>
            <?php }
        }
        ?>
        </tbody>
    </table>

    <!-- Invite Teacher-->
    <h3>Invite Student!</h3>
    <hr>

    <div id="registerBox" class="wcp-form-box">
        <form method="POST" novalidate="novalidate"
              name="teacher_invitation" id="school_invitation" enctype="multipart/form-data"
              class="form-signin">
            <input type="hidden" name="action" value="wcp_dashboardHub">
            <input type="hidden" name="target" value="studentInvitation">
            <input type="hidden" name="school_reference"
                   value="<?php echo base64_encode($teacherFromTeacherTable->school_id) ?>">
            <input type="hidden" name="teacher_reference"
                   value="<?php echo base64_encode($teacherFromTeacherTable->id) ?>">
            <div class="form-group">
                <label>Student Email: <span style="color: red">*</span></label><br>
                <input type="email" placeholder="abc@gmail.com" name="student_email" required="required"
                       class="form-control" style="width:100%;">
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="“Send Invitation to Student”"
                       style="background: #4c4560;color: white;">
            </div>
        </form>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Student?</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" name="teacherUpdate"
                          id="student_update_form"
                          enctype="multipart/form-data"
                          class="form-signin">
                        <input type="hidden" name="action" value="wcp_dashboardHub">
                        <input type="hidden" name="target" value="studentUpdate">
                        <input type="text" name="edit_id" value="">
                        <div class="form-group">
                            <label>Teacher Name: <span style="color: red">*</span></label><br>
                            <input type="text" placeholder="abc"
                                   name="full_name" required="required"
                                   class="form-control" style="width:100%;">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary update-student_button" value="“Update Student”"
                               style="background: #4c4560;color: white;">

                        <input type="button" class="btn btn-default" data-dismiss="modal" value="“Close”">

                    </div>
                </div>
            </div>

        </div>
    </div>


<?php } else { ?>
    <h1> You'r not allowed to view this page</h1>
<?php } ?>
