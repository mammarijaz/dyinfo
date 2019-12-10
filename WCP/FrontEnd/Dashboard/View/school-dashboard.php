<style>
    .modal-backdrop {
        z-index: unset;
    }
</style>
<?php
global $WCP_Common_Teacher_Model, $WCP_Common_School_Model;
$current_user = wp_get_current_user();


if (!empty($current_user->id)) {
## get the school reference

    if (in_array('wcp_school', (array)$current_user->roles)) {
        ?>
    <div class="row">
        <!-- LIST OF TEACHER -->
        <div class="col-sm-8">
            <h4 class="dash_widget_title">Teachers</h4>
            <table id="teacher___table">
                <thead>
                <tr>
                    <th>Ref</th>
                    <th>Teacher Name</th>
                    <th>Teacher Email</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php

                ## get the current user school id.
                $currentSchool = $WCP_Common_School_Model->get_school_by_wp_user_id($current_user->id);
                $teacher_list = $WCP_Common_Teacher_Model->get_teacher_by_school_id($currentSchool->id);


                if (!empty($teacher_list)) {
                    foreach ($teacher_list as $value) {
                        $class = null;
                        if ($value->is_deleted == 1) {
                            $class = 'style="background-color:red;"';
                        }
                        ?>
                        <tr id="teacher_<?php echo $value->id ?>" <?php echo $class ?>>
                            <td id="edit_id_<?php echo $value->id ?>"><?php echo !empty($value->id) ? $value->id : '' ?></td>
                            <td id="full_name_<?php echo $value->id ?>"><?php echo !empty($value->full_name) ? $value->full_name : '' ?></td>
                            <td id="user_email_<?php echo $value->id ?>"><?php echo !empty(get_userdata($value->wp_user_id)->get('user_email')) ? get_userdata($value->wp_user_id)->get('user_email') : '' ?></td>
                            <td id="teacher_action__<?php echo $value->id ?>">

                                <?php if ($value->is_deleted == 1) { ?>
                                    This user has been deleted
                                <?php } else { ?>
                                    <div class="dropdown">
                                        <button class="btn btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                            Action
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li class="edit-teacher" data-attr="<?php echo $value->id ?>"><a
                                                        href="#">Edit</a></li>
                                            <li class="deleteTeacher" data-attr="<?php echo $value->id ?>"><a
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
        </div>
        <div class="col-sm-4">
            <div class="wcp-invite-box wcp-teacher-invite-box" style="background: #3f3f3f;color: #fff;">
            <!-- Invite Teacher-->
            <h4 class="dash_widget_title">Invite teacher</h4>
            <div id="registerBox" class="wcp-form-box">
                <p id="err_msg"></p>
                <form method="POST"
                      name="teacher_invitation" id="teacher_invitation" enctype="multipart/form-data"
                      class="form-signin">
                    <input type="hidden" name="action" value="wcp_dashboardHub">
                    <input type="hidden" name="target" value="teacherInvitation">
                    <input type="hidden" name="school_reference"
                           value="<?php echo base64_encode($currentSchool->id) ?>">
                    <div class="form-group">
                        <label>Teacher Email: <span style="color: red">*</span></label><br>
                        <input type="email" placeholder="abc@gmail.com" name="teacher_email" required="required"
                               class="form-control" style="width:100%;">
                    </div>

                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Send Invitation to Teacher"
                               style="background: #4c4560;color: white;">
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
        <!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Teacher?</h4>
                    </div>
                    <div class="modal-body">
                        <form method="POST" name="teacherUpdate"
                              id="teacher_update_form"
                              enctype="multipart/form-data"
                              class="form-signin">
                            <input type="hidden" name="action" value="wcp_dashboardHub">
                            <input type="hidden" name="target" value="teacherUpdate">
                            <input type="hidden" name="edit_id" value="">
                            <div class="form-group">
                                <label>Name: <span style="color: red">*</span></label><br>
                                <input type="text" placeholder="abc"
                                       name="full_name" required="required"
                                       class="form-control" style="width:100%;">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary update-teacher_buton" value="Update Teacher"
                                   style="background: #4c4560;color: white;">

                            <input type="button" class="btn btn-default" data-dismiss="modal" value="Close">

                        </div>
                    </div>
                </div>

            </div>
        </div>


        <?php
    } else { ?>
        <h1> You'r not allowed to view teacher's list.</h1>
    <?php }
} ?>
