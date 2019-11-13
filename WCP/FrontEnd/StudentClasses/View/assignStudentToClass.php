<?php
global $WCP_Common_Student_Model, $WCP_Common_Teacher_Model;
$WCPFrontEndStudentClassModel = new WCPFrontEndStudentClassModel();

## get user from the wp_user table
$current_user = wp_get_current_user();


if (!empty($current_user->id) && in_array('wcp_teacher', (array)$current_user->roles)) {
    ## get the school reference
    ## now getting the user details from the teacher table.
    $teacherFromTeacherTable = $WCP_Common_Teacher_Model->get_teacher_by_wp_user_id($current_user->id, true);

    ## list of all non deleted Class rooms
    $listOfClassRoom = $WCPFrontEndStudentClassModel->getClassRoomBySchoolID_TeacherID($teacherFromTeacherTable->school_id, $teacherFromTeacherTable->id, 1);

    ## list of all student which is attached is to teacher and get the non-deleted user.
    $listOfAttachedStudentNonDeleted = $WCP_Common_Student_Model->get_students_by_class_and_teacher($teacherFromTeacherTable->school_id, $teacherFromTeacherTable->id, 1);
    $listOfAttachedStudentNonDeleted = empty($listOfAttachedStudentNonDeleted['data']) ? [] : $listOfAttachedStudentNonDeleted['data'];


    ## get the class room if user wants to edit.
    if (!empty($_GET['ref'])) {
        $classRoomEnrolment = $WCPFrontEndStudentClassModel->getClassRoomEnrolmentByID(base64_decode($_GET['ref']));
    }

    if (!empty($teacherFromTeacherTable->id)) {

        ?>
        <p id="err_msg"></p>

        <!-- Make Class Room -->
        <h3>Assign students to Class Room</h3>
        <hr>

        <a target="_blank" href="<?php echo home_url('/list-of-class-rooms/') ?>"> Class room list </a>


        <div id="registerBox" class="wcp-form-box">
            <form method="POST" name="wcp_form_signup" id="assignStudentToClassRoomForm" enctype="multipart/form-data"
                  class="form-signin" action="<?php echo admin_url('admin-ajax.php'); ?>">
                <input type="hidden" name="action" value="wcp_class_room">
                <input type="hidden" name="target" value="assignStudentClassRoom">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label>Select Class: <span style="color: red">*</span></label><br>
                        <select name="class_room_id" class="form-control">
                            <?php foreach ($listOfClassRoom as $value) { ?>
                                <option value="<?php echo $value->id ?>"> <?php echo $value->class_room_name ?> </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Select Student: </label><br>
                        <select name="wp_user_id" class="form-control">
                            <?php foreach ($listOfAttachedStudentNonDeleted as $value) { ?>
                                <option value="<?php echo $value['id'] ?>"> <?php echo $value['full_name'] ?> </option>
                            <?php } ?>
                            <option value="13"> testin </option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="“Assign Student to Class Room”"
                           style="background: #4c4560;color: white;">
                </div>
            </form>
        </div>


    <?php }
} else { ?>

    <div class="alert alert-danger signup-error" style="display:none">
        <strong>Danger!</strong> You'r not allowed to view this page
    </div>
<?php } ?>
