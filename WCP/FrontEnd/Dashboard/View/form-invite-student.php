<div id="inviteStudentBox" class="wcp-form-box" >
    <form method="POST" name="student_invitation" id="student_invitation" class="form-student-invitation" 
          action="<?php echo admin_url("admin-ajax.php") ?>">
          <div class="alert hidden form-response" ></div>
        <input type="hidden" name="action" value="wcp_dashboardHub">
        <input type="hidden" name="target" value="studentInvitation">
        <input type="hidden" name="school_reference"
               value="<?php echo base64_encode($school_id) ?>">
        <input type="hidden" name="teacher_reference"
               value="<?php echo base64_encode($teacher_id) ?>">
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