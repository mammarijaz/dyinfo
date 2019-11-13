jQuery(".reset-form").click(function() {
   jQuery(this).closest('form').find(":input").not(':button, :submit, :reset, :hidden').val("").prop('checked', false)
  .prop('selected', false);
  jQuery(this).closest('form').submit();
  
 });