<?php 
$school_id = 0;
$teacher_id = 0;
$user_email = ""; 
if (!empty($_GET['query']) && !empty($_GET['user_type'])){
   $register_as = $_GET['user_type'];
   ## reference
   $referrer_id = base64_decode($_GET['query']);

   //If School Inviting
   $teacher_id =  $referrer_id;
   $school_id =  $referrer_id;
   $user_email = isset($_GET["user_email"]) ? $_GET["user_email"] :"";

   //If Teacher Inviting
   if($register_as == "student"){
      $teacher_id =  $referrer_id;
      $school_id = isset($_GET["school_ref"]) ? base64_decode($_GET['school_ref']): 0;
   }

   #$school_id = isset($_GET["school_id"]) ? $_GET["school_id"] :"0";
   #$teacher_id = isset($_GET["teacher_id"]) ? $_GET["teacher_id"] :"0";
}

?>
<div id="registerBox" class="wcp-form-box">
   <form method="POST" name="wcp_form_signup" id="wcp_form_signup" action="<?php echo admin_url('admin-ajax.php'); ?>"  enctype="multipart/form-data" class="form-signin">
      <p id="err_msg"></p>
       <input type="hidden" name="action" value="wcp_signup">
       <input type="hidden" name="user_type" value="<?php echo $user_type; ?>">

       <?php if( in_array($user_type, array("wcp_teacher", "wcp_student") ) ){ ?>
         <input type="hidden" name="school_id" value="<?php echo $school_id; ?>">
         <?php if( in_array($user_type, array( "wcp_student") ) ){ ?>
            <input type="hidden" name="teacher_id" value="<?php echo $teacher_id; ?>">
         <?php }else{ ?>
         
         <?php } ?>
       <?php } ?>

       <div class="alert alert-danger signup-error" style="display:none">
         <strong>Danger!</strong> Indicates a dangerous or potentially negative action.
      </div>
      <?php if($user_type == "wcp_school"){ ?>
      <div class="form-group">
         <label>School Name: <span style="color: red">*</span></label><br>
         <input type="text" id="input_school_name" name="input_school_name" class="form-control" style="width:100%;" >
      </div>
     <!-- <div class="form-group">
         <label>Website Address <span style="color: orange">(NOTE: The address inserted must start with: http://)</span> : <span style="color: red">*</span></label><br>
         <input type="url" id="input_website"  class="form-control" style="width:100%;">
      </div>-->
      <div class="row">
         <div class="form-group col-sm-6"> 
            <label>Address: <span style="color: red">*</span></label><br>
            <input type="text" id="input_address" name="input_address" class="form-control" style="width:100%;">
         </div>
         <div class="form-group col-sm-6"> 
            <label>City: <span style="color: red">*</span></label><br>
            <input type="text" id="input_city" name="input_city" class="form-control" style="width:100%;"/>
         </div>
         <div class="form-group col-sm-6">  
            <label>State: <span style="color: red">*</span></label><br>
            <input type="text" id="input_state" name="input_state" class="form-control" style="width:100%;"/>
         </div>
         <div class="form-group col-sm-6">  
            <label>Zipcode: <span style="color: red">*</span></label><br>
            <input type="text"  id="input_zip" name="input_zip" class="form-control" style="width:100%;"/>
         </div>
         <div class="form-group col-sm-6">  
            <label>Country: <span style="color: red">*</span></label><br>
            <input type="text" id="input_country" name="input_country" class="form-control" style="width:100%;"/>
         </div>
         <div class="form-group col-sm-6">  
            <label>Phone: <span style="color: red">*</span></label><br>
            <input type="text" id="input_phone" name="input_phone" class="form-control" style="width:100%;">
         </div>
      </div>
      <?php } ?>
      <div class="form-group">
         <h3>Account Details:</h3>
      </div>
      <div class="row">
         <div class="form-group col-sm-6">
            <label>First Name: <span style="color: red">*</span></label><br>
            <input type="text" id="input_first_name" name="input_first_name" class="form-control" style="width:100%;" >
         </div>
         <div class="form-group col-sm-6">
            <label>Last Name: </label><br>
            <input type="text" id="input_last_name" name="input_last_name" class="form-control" style="width:100%;" >
         </div>
      </div>
      <div class="form-group">  
         <label>Email Address: <span style="color: red">*</span></label><br>
         <input type="email" id="input_email" name="input_email" class="form-control" style="width:100%;" value="<?php echo $user_email; ?>" >
      </div>
      <hr/>
      <h5><strong>Create a password:</strong></h5>
      <div class="form-group row">
         <div class="col-sm-6">
            <label>Password: <span style="color: red">*</span></label><br>
            <input type="password" id="input_pass" name="input_pass" class="form-control" style="width:100%;" >
         </div>
         <div class="col-sm-6">
            <label>Confirm Password: <span style="color: red">*</span></label><br>
            <input type="password" id="input_con_pass" name="input_con_pass"  class="form-control" style="width:100%;" >
         </div>
         <p class="note-text col-sm-12"><strong>Note:</strong> Password should be at least 4 characters and must contain at least one number (0-9), one lowercase letter (a-z) and one uppercase letter (A-Z) (Example: myPassword123).</p>
      </div>
      <div class="form-group">
         <input type="submit" class="btn btn-primary"  value="“Submit Your Registration”" style="background: #4c4560;color: white;">
         <p>*After clicking the “Submit Your Registration” link above,” you then will be directed to create an account. </p>
      </div>
      <p>Already registered? <a href="<?php echo site_url(); ?>/wp-login.php">Login here</a></p>
   </form>
</div>
