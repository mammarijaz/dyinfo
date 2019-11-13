<style>
    .modal-backdrop {
        z-index: unset;
    }
</style>


<?php

$current_user = wp_get_current_user();


if (!empty($current_user->id)) {

## get the school reference
    $WCP_FrontEnd_Signup_Model = new WCP_FrontEnd_Signup_Model();
    $school_id = $WCP_FrontEnd_Signup_Model->getSchoolRegistration(null, 'wp_user_id', $current_user->id, 'get_row');

    if (in_array('wcp_school', (array)$current_user->roles)) {

        /* actions */
        if (!empty($_POST['action']) && $_POST['action'] == 'wcp_school_registration_teacher_invitation') {

            if (!filter_var($_POST['teacher_email'], FILTER_VALIDATE_EMAIL) || empty($_POST['teacher_email'])) {
                $emailErr = " <div class=\"alert alert-danger signup-error\">
            <strong>Error! </strong> Invalid Email Address
        </div>";
            } else {
                $headers = array('Content-Type: text/html; charset=UTF-8');
                $body = get_site_url() . '/' . 'teacher-registration/' . '?query=' . $_POST['school_reference'] . '&ref=' . $_POST['teacher_email'];
                wp_mail($_POST['teacher_email'], 'School Invitation', $body, $headers);
                $emailErr = " <div class=\"alert alert-success email-success signup-error\">
            <strong>Success! </strong>Email Sent to the user
        </div>";

            }

        }

        /* teacher delete action */
        if (!empty($_GET['action']) && $_GET['action'] == 'DT' && !empty($_GET['delete_teacher'])) {

            global $wpdb;
            $wpdb->delete('wcp_teachers', array('id' => $_GET['delete_teacher']));

            if (!empty($wpdb->last_error)) {
                echo '<h1 style="color: red">' . $wpdb->last_error . '</h1>';
            }
        }

        /* update teacher */
        if (!empty($_POST['action']) && $_POST['action'] == 'teacher_update') {

            global $wpdb;
            $validationCheck = ['id' => 'Teacher Reference is missing', 'full_name' => 'Teacher full name is missing'];

            $proceed = true;
            foreach ($validationCheck as $key => $value) {
                if (empty($_POST[$key])) {

                    $emailErr = "";
                    echo '<div class="alert alert-danger signup-error"><strong>Error!</strong>' . $value . '</div>';
                    $proceed = false;
                    break;
                }
            }

            if ($proceed) {
                $dataToUpdate = [
                    'full_name' => $_POST['full_name'],
                ];
                $wpdb->update('wcp_teachers', $dataToUpdate, ['id' => $_POST['id']]);
                if ($wpdb->last_error) {
                    echo '<div class="alert alert-danger signup-error"><strong>Error!</strong> ' . $wpdb->last_error . '</div>';
                } else {
                    $_POST['action'] = null;
                    echo '<div class="alert teacher-success alert-success signup-error"><strong>Success!</strong> Teacher Record has been updated</div>';
                    wp_reset_postdata();
                }
            }
        }

        if (!empty($emailErr)) {
            echo $emailErr;
        }

        ## get the teacher list by the reference of school
        $teacher_list = $WCP_FrontEnd_Signup_Model->getTeacherRegistration(null, 'school_id', $school_id->id, 'get_results');

        ?>

        <table id="teacher___table">
            <thead>
            <tr>
                <th>Ref</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (!empty($teacher_list)) {
                foreach ($teacher_list as $value) {
                    ?>
                    <tr>
                        <td id="id"><?php echo !empty($value->id) ? $value->id : '' ?></td>
                        <td id="full_name"><?php echo !empty($value->full_name) ? $value->full_name : '' ?></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-xs dropdown-toggle" type="button" data-toggle="dropdown">Action
                                    <span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li class="edit-teacher"><a href="#">Edit</a></li>
                                    <li><a href="action=DT&delete_teacher=<?php echo $value->id ?>">Delete</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php }
            }
            ?>
            </tbody>
        </table>

        <h3>Invite Teacher!</h3>
        <hr>

        <div id="registerBox" class="wcp-form-box">
            <form method="POST"
                  name="teacher_invitation" id="teacher_invitation" enctype="multipart/form-data"
                  class="form-signin">
                <input type="hidden" name="action" value="wcp_school_registration_teacher_invitation">
                <input type="hidden" name="school_reference" value="<?php echo base64_encode($school_id->id) ?>">
                <div class="form-group">
                    <label>Teacher Email: <span style="color: red">*</span></label><br>
                    <input type="email" placeholder="abc@gmail.com"
                           name="teacher_email" required="required"
                           class="form-control" style="width:100%;">
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="“Send Invitation to Teacher”"
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
                        <h4 class="modal-title">Edit Teacher?</h4>
                    </div>
                    <div class="modal-body">
                        <form method="POST" name="teacher_invitation"
                              id="teacher_update_form"
                              enctype="multipart/form-data"
                              class="form-signin">
                            <input type="hidden" name="action" value="teacher_update">
                            <input type="hidden" name="id" value="wcp_school_registration_teacher_invitation">
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
                            <input type="submit" class="btn btn-primary update-teacher_buton" value="“Update Teacher”"
                                   style="background: #4c4560;color: white;">

                            <input type="button" class="btn btn-default" data-dismiss="modal" value="“Close”"
                            >


                        </div>
                        <!--                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
                    </div>
                </div>

            </div>
        </div>


        <?php
    } else { ?>
        <h1> You'r not allowed to view this page</h1>';
    <?php }
} ?>

<script>
    $(document).ready(function () {
        $('#teacher___table').DataTable();

        if ($('.alert-success').length > 0) {
            $('.alert-success').remove();
            window.location.href = window.location.href + '?cacheRemoval=true';
            return;
        }


        // edit teacher
        $(document).on('click', '.edit-teacher', function (e) {
            e.preventDefault();
            var object = {
                id: 'id',
                full_name: 'full_name',
            };


            for (var x in object) {
                var value = $('td#' + object[x]).html().trim();
                $('input[name=' + object[x] + ']').val(value);
            }

            $('#myModal').modal('show');

        });

        $(document).on('click', '.update-teacher_buton', function (e) {
            $('#teacher_update_form').submit();

        })


    });


</script>
