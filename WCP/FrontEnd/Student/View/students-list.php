<?php
global $WCP_Common_Teacher_Model;
$current_user = wp_get_current_user();

$teacher_id = $school_id = 0;
$is_teacher = false;
if (!empty($current_user->id) && in_array('wcp_teacher', (array)$current_user->roles)) {
  $is_teacher = true;
  ## now getting the user details from the teacher table.
  $teacherData = $WCP_Common_Teacher_Model->get_teacher_by_wp_user_id($current_user->id, true);
  $teacher_id = $teacherData->id;
  $school_id = $teacherData->school_id;

}
?>

 <div class="wcp-addnew-btns text-right">
  <?php if($is_teacher){ ?>
  <a href="#" onclick="invite_student_btn()" class="btn btn-primary"  />Invite Student</a>
  <a href="#" onclick="add_new_btn()" class="btn btn-primary"  />Add New</a>
  <?php } ?>
</div>
 <div class="tablewrap wcp-table-wrapper">
    <div style="padding-bottom:10px;">
        <div style="clear:both;"></div>
    </div>
    <div class="table-responsive">
        <table id='students-table' class="table ">
            <thead>
                <tr>
                    <th class="all">#</th>
                    <th class="all">Name</th>
                    <th class="all">School</th>
                    <th class="all">Teacher</th>
                    <th class="all">User ID</th>
                    <th class="all">Added Date</th>
                    <th class="all">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<!--  Model Popup -->
<?php if($is_teacher){ ?>
<div class="modal fade" id="InviteStudentModal" role="dialog" style="overflow-y: auto;">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
          <div class="modal-header"><h4 class="modal-title">Invite Student</h4><button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
         <div class="modal-body">
            <?php echo do_shortcode("[wcp_invite_student school_id=".$school_id." teacher_id=".$teacher_id." ]"); ?>
        
          </div>
      </div>
    </div>
</div>
<!-- Close Model Popup -->
<?php } ?>

<!--  Model Popup -->

<div class="modal fade" id="AddNewStudentModal" role="dialog" style="overflow-y: auto;">
    <div class="modal-dialog">
  <!-- Modal content-->

  <div class="modal-content">
  <form action="<?php echo admin_url("admin-ajax.php") ?>" method="post" id="wcpStudentForm" autocomplete="off"  >
        <input type="hidden" name="teacher_id" value="<?php echo $teacher_id; ?>">
        <input type="hidden" name="school_id" value="<?php echo $school_id; ?>">
        <input type="hidden" name="wp_user_id" id="wp_user_id" value="<?php echo 0; ?>">
      <div class="modal-header"><h4 class="modal-title"></h4><button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
     <div class="modal-body">
        <div class="row">
           <div class="form-group col-sm-6">
              <label>First Name: <span style="color: red">*</span></label><br>
              <input type="text" id="input_first_name" name="input_first_name" class="form-control" style="width:100%;" required="">
           </div>
           <div class="form-group col-sm-6">
              <label>Last Name: </label><br>
              <input type="text" id="input_last_name" name="input_last_name" class="form-control" style="width:100%;" required="" >
           </div>
        </div>
        <div class="form-group">  
           <label>Email Address: <span style="color: red">*</span></label><br>
           <input type="email" id="input_email" name="input_email" class="form-control" style="width:100%;" value="" required="">
        </div>
        <div class="form-group">
          <input type="hidden" name="action" value="WCP_Frontend_Student_Modal::add_student" id="formaction"/>
          <input type="hidden" name="edit_id" value="0" id="edit_id" />
          
        </div>
    
      </div>
      <div class="modal-footer"> <button type="cancel" class="btn btn-default" data-dismiss="modal">Close</button>  <button type="submit" class=" wcp-button btn btn-info" id="submitform" data-text="<?php echo __("Save", "wcp") ?>"><?php echo __("Submit", "wcp") ?></button>
      </div>
  </form>
  </div>

    </div>
</div>
<!-- Close Model Popup -->

<?php 
$jsmerge = "";
if($is_teacher){
  $jsmerge = " 'teacher_id': '".$teacher_id."', ";  
}
?>
<script>
 

    jQuery(document).ready(function ($) {

        $ = jQuery;
        reload_table();
        var error_count=0;

        var SPform =   $('#wcpStudentForm');
        //SPform.validate();

        SPform.submit(function(e){ 
          e.preventDefault();
          This = $(this);
         // if(SPform.valid()){}
    
          This.find("#submitform").attr("disabled", "disable");
          var input_data = This.serialize(); 
          var ajaxurl = This.attr("action");
          $('.load-spinner').addClass("show");

          $.post(ajaxurl, input_data, function(response) {
            var response = JSON.parse(response);
            if(response.status == 1 && response.success == 1){
              $('.load-spinner').removeClass("show");
              
              jQuery("#AddNewStudentModal").modal('hide');
              SPform[0].reset();
              reload_table();
            }
            if(response.status == 0 || response.success == 0){
              alert(response.error);
            }
            This.find("#submitform").removeAttr("disabled");
          }); 
          
          
         });        
    });
    
    function reload_table() {
            jQuery('#students-table').dataTable({
                    "paging": true,
                    "pageLength": 10,
                    "bProcessing": true,
                    "serverSide": true,
                     "bDestroy": true,
                    "ajax": {
                        type: "post",
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: {
                          "action": "WCP_Frontend_Student_Modal::get_students",
                          <?php echo $jsmerge ?>
                      }

                    },
                    "aoColumns": [
                      
                        {mData: 'id'},
                        {mData: 'full_name'},
                        {mData: 'school_id'},
                        {mData: 'teacher_id'},
                        {mData: 'wp_user_id'},
                        {mData: 'created_date'},
                        {mData: 'action'}
                    ],
                    "order": [[ 0, "desc" ]],        

                    "columnDefs": [{
                        "targets": [1],
                        "orderable": false
                    }]
            });
      
        }
      function wcp_delete_row(id) {
            if (confirm("Are you sure?")) {
          
                jQuery.ajax({
                    type: 'POST',
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    data: {"action": "WCP_Frontend_Student_Modal::delete_student", id: id},
                    success: function (data) {
                        if (data == "success") {
                            reload_table();
                        }
                    }
                });
            }
             return false;
    }
    function wcp_edit_row(id) {    
    jQuery.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            dataType: "json",
            data: {"action": "WCP_Frontend_Student_Modal::get_student_by_id", id: id},
            success: function (data) {
               
                var result = JSON.parse(data);
                if (result.status === 1) {
                  var SPform =   jQuery('#wcpStudentForm');
                 
                  
                  SPform.find(jQuery("#input_email")).val(result.row.user_email);
                  SPform.find(jQuery("#input_first_name")).val(result.row.first_name);
                  SPform.find(jQuery("#input_last_name")).val(result.row.last_name);
                  SPform.find(jQuery("#wp_user_id")).val(result.row.wp_user_id);
                  SPform.find(jQuery("#formaction")).val("WCP_Frontend_Student_Modal::edit_student");
                  SPform.find(jQuery("#edit_id")).val(result.row.id);
                                   
                  jQuery(".modal-title").html("Edit");
                  jQuery('#AddNewStudentModal').modal('show');
                                      
                }
            }
        });
  }
  function add_new_btn(){
    var SPform =   jQuery('#wcpForm');
    jQuery('#formaction').val("WCP_Frontend_Student_Modal::add_student");

    SPform.find(jQuery("#input_email")).val("");
    SPform.find(jQuery("#input_first_name")).val("");
    SPform.find(jQuery("#input_last_name")).val("");
    
    SPform.find(jQuery("#edit_id")).val(0);
    jQuery(".modal-title").html("Add New");
    jQuery('#AddNewStudentModal').modal('show');
    jQuery('#title').val("");
    
  }

  function invite_student_btn(){
    jQuery('#InviteStudentModal').modal('show');
  }
   
 
</script>