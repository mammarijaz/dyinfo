<?php
$current_user = wp_get_current_user();
$WCP_FrontEnd_Signup_Model = new WCP_FrontEnd_Signup_Model();
$teacher_ref = $WCP_FrontEnd_Signup_Model->getTeacherRegistration(null, 'wp_user_id', $current_user->id, 'get_row');

if (in_array('wcp_teacher', (array)$current_user->roles) && !is_array($teacher_ref) && !empty($teacher_ref->school_id) && !empty($teacher_ref->wp_user_id)) {
    if (!empty($_POST['action'] == 'wcp_teacher_registration_student_invitation')) {
        if (!filter_var($_POST['student_email'], FILTER_VALIDATE_EMAIL) || empty($_POST['student_email'])) {
            $emailErr = " <div class=\"alert alert-danger signup-error\">
            <strong>Error!</strong> Invalid Email Address
        </div>";
        } else {
            $headers = array('Content-Type: text/html; charset=UTF-8');
            $body = get_site_url() . '/' . 'student-registration/' . '?query=' . $_POST['school_reference'] .'&query2='.$_POST['teacher_ref']. '&ref=' . $_POST['student_email'];
            wp_mail($_POST['student_email'], 'Teacher Invite Student', $body, $headers);
        }
    }
    if (!empty($emailErr)) {
        echo $emailErr;
    }
    ?>
    <div id="registerBox" class="wcp-form-box">
        <form method="POST" name="teacher_invitation" id="teacher_invitation" enctype="multipart/form-data"
              class="form-signin">
            <input type="hidden" name="action" value="wcp_teacher_registration_student_invitation">
            <input type="hidden" name="school_reference" value="<?php echo base64_encode($teacher_ref->school_id) ?>">
            <input type="hidden" name="teacher_ref" value="<?php echo base64_encode($teacher_ref->wp_user_id) ?>">
            <div class="form-group">
                <label>Teacher Email: <span style="color: red">*</span></label><br>
                <input type="email" placeholder="abc@gmail.com"
                       name="student_email" required="required"
                       class="form-control" style="width:100%;">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="“Send Invitation to Student”"
                       style="background: #4c4560;color: white;">
            </div>
        </form>
    </div>
    <?php
} else { ?>
    <h1> You'r not allowed to view this page</h1>
<?php }
?>
