/*
* */
function inputManager(disable) {
    if (disable) {
        jQuery('form input').prop('disabled', true);
        jQuery('form select').prop('disabled', true);
    } else {
        jQuery('form input').prop('disabled', false);
        jQuery('form select').prop('disabled', false);
    }
}

function scrollToTop (target = false, morepad = 40){
    if(target){
        targetTop = $(target);
    }else{
        targetTop =  $('html');
    }
    if(targetTop.length){
        // scroll top of the page
        jQuery('html,body').animate({
            scrollTop:targetTop.offset().top - morepad
        }, 1000);
    }
}

function showLoading(fromRef, textToDisplay = 'Loading, please wait') {
    var targetButton = $('#' + fromRef + ' input[type=submit]');
    jQuery(targetButton).val(textToDisplay);
}

jQuery(function ($) {
});