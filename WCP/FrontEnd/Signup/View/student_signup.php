<?php

if (!empty($_GET['query']) && !empty($_GET['query2']) && !empty($_GET['ref']) && filter_var($_GET['ref'], FILTER_VALIDATE_EMAIL)) {


## school_reference
    $queryDecode = base64_decode($_GET['query']);
    $queryDecode2 = base64_decode($_GET['query2']);

    $WCP_FrontEnd_Signup_Model = new WCP_FrontEnd_Signup_Model();
    $school_id = $WCP_FrontEnd_Signup_Model->getSchoolRegistration($queryDecode);

    if (!empty($school_id)) {

        ?>
        <div id="registerBox" class="wcp-form-box">
            <form method="POST" name="wcp_form_signup" id="wcp_form_signup" enctype="multipart/form-data"
                  class="form-signin">
                <p id="err_msg"></p>
                <div class="form-group">
                    <h3>Account Details:</h3>
                </div>
                <input type="hidden" name="action" value="student_sign_up">
                <input type="hidden" name="school_ref" value="<?php $queryDecode ?>">
                <input type="hidden" name="teacher_ref" value="<?php $queryDecode2 ?>">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label>First Name: <span style="color: red">*</span></label><br>
                        <input type="text" id="input_first_name" name="student_first_name" class="form-control"
                               style="width:100%;">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Last Name: </label><br>
                        <input type="text" id="input_last_name" name="student_last_name" class="form-control"
                               style="width:100%;">
                    </div>
                </div>
                <div class="form-group">
                    <label>Email Address: <span style="color: red">*</span></label><br>
                    <input type="email" id="input_email" name="student_email" class="form-control" style="width:100%;">
                </div>
                <hr/>
                <h5><strong>Create a password:</strong></h5>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label>Password: <span style="color: red">*</span></label><br>
                        <input type="password" id="input_pass" name="student_pass" class="form-control"
                               style="width:100%;">
                    </div>
                    <div class="col-sm-6">
                        <label>Confirm Password: <span style="color: red">*</span></label><br>
                        <input type="password" id="input_con_pass" name="student_confr_pass" class="form-control"
                               style="width:100%;">
                    </div>
                    <p class="note-text col-sm-12"><strong>Note:</strong> Password should be at least 4 characters and
                        must
                        contain at least one number (0-9), one lowercase letter (a-z) and one uppercase letter (A-Z)
                        (Example:
                        myPassword123).</p>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="“Submit Your Registration”"
                           style="background: #4c4560;color: white;">
                    <p>*After clicking the “Submit Your Registration” link above,” you then will be directed to create
                        an
                        account. </p>
                </div>
                <p>Already registered? <a href="<?php echo site_url(); ?>/wp-login.php">Login here</a></p>
            </form>
        </div>
    <?php }
} ?>