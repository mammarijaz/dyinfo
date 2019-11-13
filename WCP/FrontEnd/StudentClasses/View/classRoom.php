<?php
global $WCP_Common_Student_Model, $WCP_Common_Teacher_Model;

## get user from the wp_user table
$current_user = wp_get_current_user();


if (!empty($current_user->id) && in_array('wcp_teacher', (array)$current_user->roles)) {
    ## get the school reference
    ## now getting the user details from the teacher table.
    $teacherFromTeacherTable = $WCP_Common_Teacher_Model->get_teacher_by_wp_user_id($current_user->id, true);

    ## get the class room if user wants to edit.
    if (!empty($_GET['ref'])) {
        $WCPFrontEndStudentClassModel = new WCPFrontEndStudentClassModel();
        $classRoom = $WCPFrontEndStudentClassModel->getClassRoomByID(base64_decode($_GET['ref']));
    }


    if (!empty($teacherFromTeacherTable->id)) {

        ?>
        <p id="err_msg"></p>

        <!-- Make Class Room -->
        <h3>Make Class room</h3>
        <hr>

        <a target="_blank" href="<?php echo home_url('/list-of-class-rooms/') ?>"> Class room list </a>


        <div id="registerBox" class="wcp-form-box">
            <form method="POST" name="wcp_form_signup" id="makeClassRoom" enctype="multipart/form-data"
                  class="form-signin" action="<?php echo admin_url('admin-ajax.php'); ?>">
                <input type="hidden" name="action" value="wcp_class_room">
                <input type="hidden" name="teacher_id" value="<?php echo $teacherFromTeacherTable->id; ?>">
                <input type="hidden" name="school_id" value="<?php echo $teacherFromTeacherTable->school_id; ?>">
                <input type="hidden" name="target" value="makeClassRoom">
                <input type="hidden" name="edit_id" value="<?php echo !empty($classRoom->id) ? $classRoom->id : 0 ?>">

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label>Class name: <span style="color: red">*</span></label><br>
                        <input type="text" name="class_room_name" class="form-control"
                               style="width:100%;" maxlength="70"
                               value="<?php echo !empty($classRoom->class_room_name) ? $classRoom->class_room_name : '' ?>"
                               required="required">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Purpose of making class room: </label><br>
                        <input type="text" name="class_room_purpose" class="form-control"
                               value="<?php echo !empty($classRoom->class_room_purpose) ? $classRoom->class_room_purpose : '' ?>"
                               style="width:100%;" maxlength="250">
                    </div>
                </div>


                <div class="row">
                    <div class="form-group col-sm-6">
                        <label>Class Duration </label><br>
                        <input type="text" name="class_duration" class="form-control"
                               value="<?php echo !empty($classRoom->class_duration) ? $classRoom->class_duration : '' ?>"
                               style="width:100%;" placeholder="2 Months" maxlength="50">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Class in a week: (<small>2 or 4</small>) </label><br>
                        <input type="text" name="class_in_a_week" pattern="\d*" class="form-control"
                               value="<?php echo !empty($classRoom->class_in_a_week) ? $classRoom->class_in_a_week : '' ?>"
                               style="width:100%;" maxlength="2">
                    </div>
                </div>


                <div class="row">
                    <div class="form-group col-sm-6">
                        <label>Class Start time </label><br>
                        <input type="date" name="class_start_date" class="form-control"
                               value="<?php echo !empty($classRoom->class_start_date) ? $classRoom->class_start_date : '' ?>"
                               style="width:100%;">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Class End time </label><br>
                        <input type="date" name="class_end_date" class="form-control"
                               value="<?php echo !empty($classRoom->class_end_date) ? $classRoom->class_end_date : '' ?>"
                               style="width:100%;">
                    </div>
                </div>


                <div class="row">
                    <div class="form-group col-sm-12">
                        <label>Description </label><br>
                        <input type="text" name="class_room_descrption" class="form-control"
                               value="<?php echo !empty($classRoom->class_room_descrption) ? $classRoom->class_room_descrption : '' ?>"
                               style="width:100%;" maxlength="250">
                    </div>
                </div>
                <hr/>

                <h3>Class Room Routine</h3>
                <hr>

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label>Sunday Class Start time </label><br>
                        <input type="time" name="class_sun_start_time" class="form-control"
                               value="<?php echo !empty($classRoom->class_sun_start_time) ? $classRoom->class_sun_start_time : '' ?>"

                               style="width:100%;">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Sunday Class End time </label><br>
                        <input type="time" name="class_sun_end_time" class="form-control"
                               value="<?php echo !empty($classRoom->class_sun_end_time) ? $classRoom->class_sun_end_time : '' ?>"

                               style="width:100%;">
                    </div>
                </div>


                <div class="row">
                    <div class="form-group col-sm-6">
                        <label>Monday Start time </label><br>
                        <input type="time" name="class_mon_start_time" class="form-control"
                               value="<?php echo !empty($classRoom->class_mon_start_time) ? $classRoom->class_mon_start_time : '' ?>"

                               style="width:100%;">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Monday End time </label><br>
                        <input type="time" name="class_mon_end_time" class="form-control"
                               value="<?php echo !empty($classRoom->class_mon_end_time) ? $classRoom->class_mon_end_time : '' ?>"

                               style="width:100%;">
                    </div>
                </div>


                <div class="row">
                    <div class="form-group col-sm-6">
                        <label>Tuesday Start time </label><br>
                        <input type="time" name="class_tue_start_time" class="form-control"
                               value="<?php echo !empty($classRoom->class_tue_start_time) ? $classRoom->class_tue_start_time : '' ?>"

                               style="width:100%;">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Tuesday End time </label><br>
                        <input type="time" name="class_tue_end_time" class="form-control"
                               value="<?php echo !empty($classRoom->class_tue_end_time) ? $classRoom->class_tue_end_time : '' ?>"

                               style="width:100%;">
                    </div>
                </div>


                <div class="row">
                    <div class="form-group col-sm-6">
                        <label>Wednesday Start time </label><br>
                        <input type="time" name="class_wed_start_time" class="form-control"
                               value="<?php echo !empty($classRoom->class_wed_start_time) ? $classRoom->class_wed_start_time : '' ?>"

                               style="width:100%;">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Wednesday End time </label><br>
                        <input type="time" name="class_wed_end_time" class="form-control"
                               value="<?php echo !empty($classRoom->class_wed_end_time) ? $classRoom->class_wed_end_time : '' ?>"

                               style="width:100%;">
                    </div>
                </div>


                <div class="row">
                    <div class="form-group col-sm-6">
                        <label>Thursday Start time </label><br>
                        <input type="time" name="class_thu_start_time" class="form-control"
                               value="<?php echo !empty($classRoom->class_thu_start_time) ? $classRoom->class_thu_start_time : '' ?>"

                               style="width:100%;">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Thursday End time </label><br>
                        <input type="time" name="class_thu_end_time" class="form-control"
                               value="<?php echo !empty($classRoom->class_thu_end_time) ? $classRoom->class_thu_end_time : '' ?>"

                               style="width:100%;">
                    </div>
                </div>


                <div class="row">
                    <div class="form-group col-sm-6">
                        <label>Friday Start time </label><br>
                        <input type="time" name="class_fri_start_time" class="form-control"
                               value="<?php echo !empty($classRoom->class_fri_start_time) ? $classRoom->class_fri_start_time : '' ?>"

                               style="width:100%;">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Friday End time </label><br>
                        <input type="time" name="class_fri_end_time" class="form-control"
                               value="<?php echo !empty($classRoom->class_fri_end_time) ? $classRoom->class_fri_end_time : '' ?>"

                               style="width:100%;">
                    </div>
                </div>


                <div class="row">
                    <div class="form-group col-sm-6">
                        <label>Saturday Start time </label><br>
                        <input type="time" name="class_sat_start_time" class="form-control"
                               value="<?php echo !empty($classRoom->class_sat_start_time) ? $classRoom->class_sat_start_time : '' ?>"

                               style="width:100%;">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Saturday End time </label><br>
                        <input type="time" name="class_sat_end_time" class="form-control"
                               value="<?php echo !empty($classRoom->class_sat_end_time) ? $classRoom->class_sat_end_time : '' ?>"

                               style="width:100%;">
                    </div>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="“Make Class Room”"
                           style="background: #4c4560;color: white;">
                </div>
            </form>
        </div>


    <?php } else { ?>


        <div class="alert alert-danger signup-error" style="display:none">
            <strong>Danger!</strong> You'r not allowed to view this page
        </div>

        <?php
    }
} else { ?>

    <div class="alert alert-danger signup-error" style="display:none">
        <strong>Danger!</strong> You'r not allowed to view this page
    </div>
<?php } ?>
