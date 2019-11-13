<?php ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.12/datatables.min.js"></script>
<!--Select2 CDN css and jQuery -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<div class="wrap" style="padding-top:20px;">
  <h1 class="wp-heading-inline">Schools</h1>
  <input type="button" value="Add New" name="btn_add_new" id="btn_add_new" class="page-title-action" onclick="add_new_btn()"  />

  <hr/>
    <div class="tablewrap">
        <div style="padding-bottom:10px;">
            <div style="clear:both;"></div>
        </div>
        <div class="table-responsive">
            <table id='service-table' class="table ">
                <thead>
                    <tr>
                        <th class="all">#</th>
                        <th class="all">School Name</th>
                        <th class="all">Phone</th>
                        <th class="all">Address</th>
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
</div>
<!--  Model Popup -->

<div class="modal fade" id="AddNewModal" role="dialog" style="overflow-y: auto;">
    <div class="modal-dialog">
	<!-- Modal content-->

	<div class="modal-content">
  <form action="<?php echo admin_url("admin-ajax.php") ?>" method="post" id="wcpForm" autocomplete="off"  >
	    <div class="modal-header"><h4 class="modal-title"></h4><button type="button" class="close" data-dismiss="modal">&times;</button>
	    </div>
	   <div class="modal-body">
        <div class="form-group">
          <div class="wcp-form-field">
            <label for="title"><?php echo __("School Name", "wcp") ?><span class="is-required">*</span> </label><br/>
            <input class="form-control" name="input_school_name" id="input_school_name" type="text" value="" autocomplete="off" required>
          </div>
        </div>
        <div class="form-check form-group">
          <div class="wcp-form-field">
            <label for="title"><?php echo __("Address", "wcp") ?><span class="is-required">*</span> </label><br/>
            <textarea class="form-control" name="input_address" id="input_address"  autocomplete="off" required></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="wcp-form-field">
            <label for="title"><?php echo __("City", "wcp") ?><span class="is-required">*</span> </label><br/>
            <input class="form-control" name="input_city" id="input_city" type="text" value="" autocomplete="off" required>
          </div>
        </div>
        <div class="form-group">
          <div class="wcp-form-field">
            <label for="title"><?php echo __("State", "wcp") ?><span class="is-required">*</span> </label><br/>
            <input class="form-control" name="input_state" id="input_state" type="text" value="" autocomplete="off" required>
          </div>
        </div>
        <div class="form-group">
          <div class="wcp-form-field">
            <label for="title"><?php echo __("Zipcode", "wcp") ?><span class="is-required">*</span> </label><br/>
            <input class="form-control" name="input_zip" id="input_zip" type="text" value="" autocomplete="off" required>
          </div>
        </div>
        <div class="form-group">
          <div class="wcp-form-field">
            <label for="title"><?php echo __("Country", "wcp") ?><span class="is-required">*</span> </label><br/>
            <input class="form-control" name="input_country" id="input_country" type="text" value="" autocomplete="off" required>
          </div>
        </div>
        <div class="form-group">
          <div class="wcp-form-field">
            <label for="title"><?php echo __("Phone", "wcp") ?><span class="is-required">*</span> </label><br/>
            <input class="form-control" name="input_phone" id="input_phone" type="text" value="" autocomplete="off" required>
          </div>
        </div>
        <div class="form-group">
          <div class="wcp-form-field">
            <label for="title"><?php echo __("First Name", "wcp") ?><span class="is-required">*</span> </label><br/>
            <input class="form-control" name="input_first_name" id="input_first_name" type="text" value="" autocomplete="off" required>
          </div>
        </div>
        <div class="form-group">
          <div class="wcp-form-field">
            <label for="title"><?php echo __("Last Name", "wcp") ?><span class="is-required">*</span> </label><br/>
            <input class="form-control" name="input_last_name" id="input_last_name" type="text" value="" autocomplete="off" required>
          </div>
        </div>
        <div class="form-group">
          <div class="wcp-form-field">
            <label for="title"><?php echo __("Email Address", "wcp") ?><span class="is-required">*</span> </label><br/>
            <input class="form-control" name="input_email" id="input_email" type="email" value="" autocomplete="off" required>
          </div>
        </div>
        <div class="form-group">
          <input type="hidden" name="action" value="WCP_BackEnd_Schools_Model::add_school" id="formaction"/>
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



<script>
    jQuery(document).ready(function ($) {

        $ = jQuery;
        reload_table();
        var error_count=0;

        var SPform =   $('#wcpForm');
        SPform.validate();

        SPform.submit(function(e){ 
          e.preventDefault();
          This = $(this);
          if(SPform.valid()){
    
          This.find("#submitform").attr("disabled", "disable");
          var input_data = This.serialize(); 
          var ajaxurl = This.attr("action");
          $('.load-spinner').addClass("show");

        //  var activeEditor = tinyMCE.get('content');

          $.post(ajaxurl, input_data, function(response) {
            var response = JSON.parse(response);
            if(response.status == 1 && response.success == 1){
              $('.load-spinner').removeClass("show");
              This.find("#submitform").removeAttr("disabled");
              jQuery("#AddNewModal").modal('hide');
              SPform[0].reset();
              reload_table();
            }
          }); 
          }
          
         });        
    });
    
     function reload_table() {
            jQuery('#service-table').dataTable({
                    "paging": true,
                    "pageLength": 10,
                    "bProcessing": true,
                    "serverSide": true,
                     "bDestroy": true,
                    "ajax": {
                        type: "post",
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        data: {
                          "action": "WCP_BackEnd_Schools_Model::get_schools"
                      }

                    },
                    "aoColumns": [
                      
                        {mData: 'id'},
                        {mData: 'school_name'},
                        {mData: 'school_phone'},
                        {mData: 'school_address'},
                        {mData: 'wp_user_link'},
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
                    data: {"action": "WCP_BackEnd_Schools_Model::delete_school", id: id},
                    success: function (data) {
                      console.log(data);
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
            data: {"action": "WCP_BackEnd_Schools_Model::get_school_by_id", id: id},
            success: function (data) {
               
                var result = JSON.parse(data);

                if (result.status == 1) {
                  var SPform =   jQuery('#wcpForm');
                  
                  //jQuery('#exhibits').val(result.row.selected_exhibits).trigger("change");
                 
                  SPform.find(jQuery("#input_school_name")).val(result.row.school_name);
                  SPform.find(jQuery("#input_address")).val(result.row.school_address);
                  SPform.find(jQuery("#input_city")).val(result.row.school_city);
                  SPform.find(jQuery("#input_country")).val(result.row.school_country);
                  SPform.find(jQuery("#input_state")).val(result.row.school_state);
                  SPform.find(jQuery("#input_zip")).val(result.row.school_zipcode);
                  SPform.find(jQuery("#input_phone")).val(result.row.school_phone);
                  SPform.find(jQuery("#input_first_name")).val(result.row.first_name);
                  SPform.find(jQuery("#input_last_name")).val(result.row.last_name);
                  SPform.find(jQuery("#input_email")).val(result.row.email);
                  SPform.find(jQuery("#formaction")).val("WCP_BackEnd_Schools_Model::edit_school");
                  SPform.find(jQuery("#edit_id")).val(result.row.id);
                                   
                  jQuery(".modal-title").html("Edit");
                  jQuery('#AddNewModal').modal('show');
                  //console.log(data);                    
                }
            }
        });
  }
  function add_new_btn(){
		var SPform =   jQuery('#wcpForm');
		jQuery('#formaction').val("WCP_BackEnd_Schools_Model::add_school");
		
		SPform.find(jQuery("#edit_id")).val(0);
		jQuery(".modal-title").html("Add New");
    jQuery('#AddNewModal').modal('show');
    jQuery('#title').val("");
		
  }
   
 
</script>
