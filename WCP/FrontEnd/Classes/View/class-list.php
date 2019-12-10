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
<style type="text/css">#wcpClassForm{display: block!important;}</style>
 <div class="wcp-addnew-btns text-right">
  <?php if($is_teacher){ ?>
  <a href="#" onclick="add_new_btn()" class="btn btn-primary"  />Add New</a>
  <?php } ?>
</div>
 <div class="tablewrap wcp-table-wrapper">
    <div style="padding-bottom:10px;">
        <div style="clear:both;"></div>
    </div>
    <div class="table-responsive">
        <table id='classes-table' class="table ">
            <thead>
                <tr>
                    <th class="all">#</th>
                    <th class="all">Name</th>
                    <th class="all">Description</th>
                    <th class="all">Start Date</th>
                    <th class="all">End Date</th>
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

<div class="modal fade" id="AddNewClassModal" role="dialog" style="overflow-y: auto;">
    <div class="modal-dialog">
  <!-- Modal content-->

  <div class="modal-content">
  <form action="<?php echo admin_url("admin-ajax.php") ?>" method="post" id="wcpClassForm" autocomplete="off"  >
      <div class="modal-header"><h4 class="modal-title"></h4><button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
     <div class="modal-body">
        <input type="hidden" name="teacher_id" value="<?php echo $teacher_id; ?>">
        <input type="hidden" name="school_id" value="<?php echo $school_id; ?>">
        <div class="row">
            <div class="form-group col-sm-12">
                <label>Class name: <span style="color: red">*</span></label><br>
                <input type="text" name="class_room_name" id="class_room_name" class="form-control"
                       value=""
                       required="required">
            </div>
            <div class="form-group col-sm-12">
                <label>Description </label><br>
                <input type="text" name="class_room_descrption" id="class_room_descrption" class="form-control" value="">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-6">
                <label>Class Start on </label><br>
                <input type="date" name="class_start_date" id="class_start_date" class="form-control" value="">
            </div>
            <div class="form-group col-sm-6">
                <label>Class End on </label><br>
                <input type="date" name="class_end_date" id="class_end_date" class="form-control" value="">
            </div>
        </div>
        <div class="form-group">
          <input type="hidden" name="action" value="WCP_Frontend_Class_Modal::add_class" id="formClassFormaction"/>
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

        var SPform =   $('#wcpClassForm');
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
              This.find("#submitform").removeAttr("disabled");
              jQuery("#AddNewClassModal").modal('hide');
              SPform[0].reset();
              reload_table();
            }
          }); 
          
          
         });        
    });
    
    function reload_table() {
            jQuery('#classes-table').dataTable({
                    "paging": true,
                    "pageLength": 10,
                    "bProcessing": true,
                    "serverSide": true,
                     "bDestroy": true,
                    "ajax": {
                        type: "post",
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: {
                          "action": "WCP_Frontend_Class_Modal::get_classes",
                          <?php echo $jsmerge ?>
                      }

                    },
                    "aoColumns": [
                        {mData: 'id'},
                        {mData: 'class_room_name'},
                        {mData: 'class_room_descrption'},
                        {mData: 'class_start_date'},
                        {mData: 'class_end_date'},
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
                    data: {"action": "WCP_Frontend_Class_Modal::delete_class", id: id},
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
            data: {"action": "WCP_Frontend_Class_Modal::get_class_by_id", id: id},
            success: function (data) {
               
                //var result = JSON.parse(data);
                var result = data;
                console.log(result);
                if (result.status === 1) {
                  var SPform =   jQuery('#wcpClassForm');
                 
                  SPform.find(jQuery("#class_room_name")).val(result.row.class_room_name);
                  /*SPform.find(jQuery("#school_id")).val(result.row.school_id);
                  SPform.find(jQuery("#teacher_id")).val(result.row.teacher_id);*/
                  SPform.find(jQuery("#class_room_descrption")).val(result.row.class_room_descrption);
                  SPform.find(jQuery("#class_start_date")).val(result.row.class_start_date);
                  SPform.find(jQuery("#class_end_date")).val(result.row.class_end_date);

                  SPform.find(jQuery("#formClassFormaction")).val("WCP_Frontend_Class_Modal::edit_class");
                  SPform.find(jQuery("#edit_id")).val(result.row.id);
                                   
                  jQuery(".modal-title").html("Edit");
                  jQuery('#AddNewClassModal').modal('show');
                                      
                }
            }
        });
  }
  function add_new_btn(){
    var SPform =   jQuery('#wcpClassForm');
    jQuery('#formClassFormaction').val("WCP_Frontend_Class_Modal::add_class");
    
    SPform.find(jQuery("#edit_id")).val(0);
    jQuery(".modal-title").html("Add New");
    SPform.find(jQuery("#class_room_name")).val("");
    SPform.find(jQuery("#class_room_descrption")).val("");
    SPform.find(jQuery("#class_start_date")).val("");
    SPform.find(jQuery("#class_end_date")).val("");
    jQuery('#AddNewClassModal').modal('show');
    jQuery('#title').val("");
    
  }

  function invite_class_btn(){
    jQuery('#InviteClassModal').modal('show');
  }
   
 
</script>