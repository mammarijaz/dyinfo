jQuery(document).ready(function ($) {


    var form_signup = $('#wcp_form_signup'), err_msg = $('#err_msg'), input_first_name = $('#input_first_name'),
        input_pass = $('#input_pass'), input_con_pass = $('#input_con_pass'), input_email = $('#input_email');
    input_school = $('#input_school_name');
    //input_website = $('#input_website');
    input_last_name = $('#input_last_name');
    input_phone = $('#school_phone');

    form_signup.on('submit', function (e) {
        e.preventDefault();
        var This = jQuery(this);
        ajaxurl = This.attr("action");

        if (is_valid_request()) {
            var password = input_pass.val();
            var re_1 = /[0-9]/;
            var re_2 = /[a-z]/;
            var re_3 = /[A-Z]/;

            if (password.length < 4) {
                err_msg.css('color', "red");
                err_msg.html('Your Password should be atleast 4 character');
            } else if (!re_1.test(password)) {
                err_msg.css('color', "red");
                err_msg.html('Password must contain at least one number (0-9)!');
            } else if (!re_2.test(password)) {
                err_msg.css('color', "red");
                err_msg.html('Password must contain at least one lowercase letter (a-z)!');
            } else if (!re_3.test(password)) {
                err_msg.css('color', "red");
                err_msg.html('Password must contain at least one uppercase letter (A-Z)!!');
            } else if (input_pass.val() != input_con_pass.val()) {
                err_msg.css('color', "red");
                err_msg.html('Please confirm your password.');
            } else {
                /// Create data for form submission
                // fdata = {};
                // fdata.input_first_name = input_first_name.val();
                // fdata.input_last_name = input_last_name.val();
                // fdata.input_email = input_email.val();
                // fdata.input_pass = input_pass.val();
                // //fdata.input_website = input_website.val();
                // fdata.input_school = input_school.val();
                //
                // // fdata.action = 'WCP_Signup_Controller::custom_signup_user';
                // fdata.action = 'wcp_signup';
                err_msg.html('Please wait...');
                var formData = $('form#wcp_form_signup').serialize();
                // after getting data from input disable now
                inputManager(true);
                
                $.ajax({
                    url: ajaxurl,
                    //url: ajax_url,
                    method: "POST",
                    //action: 'wp_school_registration',
                    dataType: "json",
                    // data: fdata
                    data: formData,
                }).always(function (response) {


                    console.log(response);

                    if (response.status == 'ok' || response.status == 'ok') {
                        err_msg.css('color', "green");
                        err_msg.html("Registration completed");
                        err_msg.html(response.msg);
                        form_signup[0].reset();
                        window.location.replace(response.url);
                    } else {
                        err_msg.css('color', "red");
                        err_msg.html(response.error);
                        err_msg.html(response.responseText);
                    }

                    // enable inputs
                    inputManager(false);

                });

            }


        } else {
            err_msg.css('color', "red");
            err_msg.html('All fields are required.');
        }

        scrollToTop("#wcp_form_signup");


    });

    function is_valid_request() {
        /*valid_inputs = [input_first_name.val(), input_last_name.val(), input_email.val(), input_pass.val(), input_con_pass.val(),input_dob.val(),input_terms.val()];*/
        valid_inputs = [input_first_name.val(), input_phone.val(), input_email.val(), input_pass.val(), input_con_pass.val()];

        for (i = 0; i < valid_inputs.length; i++) {
            if (valid_inputs[i] == '') {
                return false;
            }
        }

        return true;
    };



}); /// End document ready
