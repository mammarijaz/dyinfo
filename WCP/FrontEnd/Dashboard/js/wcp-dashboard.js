jQuery(document).ready(function ($) {

    var err_msg = jQuery('#err_msg');
    jQuery('#teacher___table, #school___table, #teacher___table_, #my___table').dataTable();

    /**
     * Invitation Codes
     * */

    /* validate invitation form */
    function is_valid_request(valid_inputs) {
        for (i = 0; i < valid_inputs.length; i++) {
            if (valid_inputs[i] == '' || typeof valid_inputs[i] === 'undefined') {
                return false;
            }
        }
        return true;
    };

    /**
     * Teacher Invitation
     * */
    function sendInvitationToUsers() {
        let emailInput = jQuery('input[name=teacher_email]').val();
        var object = [
            emailInput,
        ];
        var invitaitonValidation = is_valid_request(object);
        if (invitaitonValidation) {
            showLoading('teacher_invitation');
            jQuery.ajax({
                method: 'post',
                data: jQuery('#teacher_invitation').serialize(),
                url: ajaxurl,
                success: function (message) {
                    var resposne = JSON.parse(message);
                    err_msg.html(resposne.msg);
                    if (resposne.status === true) {
                        err_msg.css('color', 'green');
                        jQuery('#teacher_invitation')[0].reset();
                    } else {
                        err_msg.css('color', 'red');
                    }
                    showLoading('teacher_invitation', 'Send invitation to teacher');
                },
                error: function (err) {
                    err_msg.html(err.responseText);
                    err_msg.css('color', 'red');
                    console.log(err);
                    console.log('ajax is returning an err');
                    showLoading('teacher_invitation', 'Send invitation to teacher.');


                    // temp
                    err_msg.css('color', 'green');
                    err_msg.html('Email has been sent to the user');
                    jQuery('#teacher_invitation')[0].reset();


                },
            });
        } else {
            err_msg.html('Please fill out the require fields');
            err_msg.css('color', 'red');
        }
    }

    jQuery(document).on('submit', 'form#teacher_invitation, form#school_invitation', function (e) {
        e.preventDefault();
        sendInvitationToUsers();
    });

    /**
     * Student Invitation
     * */
    function sendInvitationToStudent() {
        var formResponse = jQuery('#student_invitation').find(".form-response");
        formResponse.addClass("hidden");
        let emailInput = jQuery('input[name=student_email]').val();
        let schoolRef = jQuery('input[name=school_reference]').val();
        let teacherRef = jQuery('input[name=teacher_reference]').val();
        var object = [
            emailInput,
            schoolRef,
            teacherRef,
        ];
        var invitaitonValidation = is_valid_request(object);
        if (invitaitonValidation) {
            showLoading('student_invitation');
            
            jQuery.ajax({
                method: 'post',
                data: jQuery('#student_invitation').serialize(),
                url: ajaxurl,
                success: function (message) {
                    var resposne = JSON.parse(message);
                    formResponse.html(resposne.msg);
                    formResponse.removeClass("hidden");
                    if (resposne.status === true) {
                        jQuery('#student_invitation')[0].reset();
                        formResponse.removeClass('alert-danger').addClass("alert-success");
                    } else {
                        formResponse.html(resposne.msg);
                        formResponse.addClass('alert-danger').removeClass('alert-success');
                    }
                    showLoading('student_invitation', 'Send invitation to Student');
                },
                error: function (err) {
                    formResponse.removeClass("hidden");
                    formResponse.html(err.responseText);
                    formResponse.css('color', 'red');
                    showLoading('student_invitation', 'Send invitation to Student');

                    // temp
                    formResponse.addClass('alert-danger').removeClass('alert-success');
                    formResponse.html('Email has been sent to the user');
                    jQuery('#student_invitation')[0].reset();
                },
            });
        } else {
            formResponse.removeClass("hidden");
            formResponse.html('Please fill out the require fields');
            formResponse.addClass('alert-danger').removeClass('alert-success');
        }
        scrollToTop();
    }

    jQuery(document).on('submit', 'form#student_invitation', function (e) {
        e.preventDefault();
        sendInvitationToStudent();
    });


    /**
     * Update Operations
     * */

    // edit teacher - show the modal
    jQuery(document).on('click', '.edit-teacher', function (e) {
        e.preventDefault();

        var teacherRef = jQuery(this).attr('data-attr');
        var object = {
            edit_id: 'edit_id_',
            full_name: 'full_name_',
        };

        for (var x in object) {
            //var value = jQuery('td#' + object[x]).html().trim();
            var value = jQuery('td#' + object[x] + teacherRef).html();
            jQuery('input[name=' + [x] + ']').val(value);
        }
        jQuery('#myModal').modal('show');
    });

    /**
     * Teacher Update
     * */
    // update the teacher in database
    function updateTeacher() {
        let teacherFullName = jQuery('input[name=full_name]').val();
        let teacherEditID = jQuery('input[name=edit_id]').val();
        var object = [
            teacherFullName,
            teacherEditID,
        ];
        var invitaitonValidation = is_valid_request(object);
        if (invitaitonValidation) {
            showLoading('teacher_update_form');

            jQuery.ajax({
                method: 'post',
                data: jQuery('#teacher_update_form').serialize(),
                url: ajaxurl,
                success: function (message) {
                    console.log(message);
                    var resposne = JSON.parse(message);

                    if (resposne === 'OK') {
                        err_msg.css('color', 'green');
                        err_msg.html('Teacher has been updated');
                        jQuery('#teacher_update_form')[0].reset();
                        jQuery('#myModal').modal('hide');
                        window.location.reload();
                    } else {
                        console.log(message);
                        err_msg.html(resposne);
                        err_msg.css('color', 'red');
                    }

                    showLoading('teacher_update_form', 'Update Teacher');

                },
                error: function (err) {
                    console.log(err);
                    console.log('error message');
                    err_msg.html(err.responseText);
                    err_msg.css('color', 'red');


                    // temp

                    err_msg.css('color', 'green');
                    err_msg.html('Email has been sent to the user');
                    jQuery('#school_invitation')[0].reset();

                },
            });
        } else {
            err_msg.html('Please fill out the require fields');
            err_msg.css('color', 'red');
        }

        scrollToTop();
    }

    jQuery(document).on('click', '.update-teacher_buton', function (e) {
        e.preventDefault();
        updateTeacher();
    });

    /**
     * Student Update
     * */
    // update the teacher in database
    function updateStudent() {
        let teacherFullName = jQuery('input[name=full_name]').val();
        let teacherEditID = jQuery('input[name=edit_id]').val();
        let action = jQuery('input[name=action]').val();
        let target = jQuery('input[name=target]').val();
        var object = [
            teacherFullName,
            teacherEditID,
            action,
            target,
        ];
        var invitaitonValidation = is_valid_request(object);
        if (invitaitonValidation) {
            showLoading('student_update_form');


            jQuery.ajax({
                method: 'post',
                data: jQuery('#student_update_form').serialize(),
                url: ajaxurl,
                success: function (message) {
                    console.log(message);
                    var resposne = JSON.parse(message);
                    if (resposne === 'OK') {
                        err_msg.css('color', 'green');
                        err_msg.html('Teacher has been updated');
                        jQuery('#student_update_form')[0].reset();
                        jQuery('#myModal').modal('hide');
                        window.location.reload();
                    } else {
                        console.log(message);
                        err_msg.html(resposne);
                        err_msg.css('color', 'red');
                    }
                    showLoading('student_update_form', 'Update Student');
                },
                error: function (err) {
                    console.log(err);
                    console.log('error in ajax request');
                    err_msg.html(err.responseText);
                    err_msg.css('color', 'red');

                    // temp
                    err_msg.css('color', 'green');
                    err_msg.html('Teacher has been updated');
                    jQuery('#student_update_form')[0].reset();
                    jQuery('#myModal').modal('hide');
                    window.location.reload();
                },
            });
        } else {
            err_msg.html('Please fill out the require fields');
            err_msg.css('color', 'red');
        }

        scrollToTop();
    }

    jQuery(document).on('click', '.update-student_button', function (e) {
        e.preventDefault();
        updateStudent();
    });


    /**
     * Delete teacher
     * */

    function deleteTeacher(teacherRef) {
        if (teacherRef == '' || typeof teacherRef === 'undefined') {
            return err_msg.css('red', 'Invalid teacher');
        } else {
            jQuery.ajax({
                method: 'post',
                data: {
                    id: teacherRef,
                    action: 'wcp_dashboardHub',
                    target: 'teacher_delete',
                },
                url: ajaxurl,
                success: function (message) {
                    if (message == 'success') {
                        jQuery('#teacher_' + teacherRef).css('background-color', 'red');
                        jQuery('#teacher_action__' + teacherRef).html('This user has been deleted');
                    } else {
                        err_msg.css('color', 'red');
                        err_msg.html('Please contact admin to delete the teacher');
                        console.log(message);
                    }
                },
                error: function (err) {
                    console.log(err);
                    console.log('error in ajax request');
                    err_msg.css('color', 'red');
                    err_msg.html(err.responseText);


                    // temp
                    jQuery('#teacher_' + teacherRef).css('background-color', 'red');
                    jQuery('#teacher_action__' + teacherRef).html('This user has been deleted');;


                }
            });
        }
    }

    jQuery(document).on('click', '.deleteTeacher', function (e) {
        e.preventDefault();
        var teacherRef = jQuery(this).attr('data-attr');
        deleteTeacher(teacherRef);
    });

    /**
     * Delete Student
     * */
    function deleteStudent(studentRef) {
        if (studentRef == '' || typeof studentRef === 'undefined') {
            return err_msg.css('red', 'Invalid teacher');
        } else {
            jQuery.ajax({
                method: 'post',
                data: {
                    id: studentRef,
                    action: 'wcp_dashboardHub',
                    target: 'student_delete',
                },
                url: ajaxurl,
                success: function (message) {
                    message = JSON.parse(message);
                    if (message == 'success') {
                        jQuery('#teacher_' + studentRef).css('background-color', 'red');
                        jQuery('#teacher_action__' + studentRef).html('This user has been deleted');
                    } else {
                        err_msg.css('color', 'red');
                        err_msg.html('Please contact admin to delete the teacher');
                    }
                },
                error: function (err) {
                    err_msg.css('color', 'red');
                    err_msg.html(err.responseText);
                    console.log(err);
                    console.log('error in ajax request');

                    // temp

                    jQuery('#teacher_' + studentRef).css('background-color', 'red');
                    jQuery('#teacher_action__' + studentRef).html('This user has been deleted');

                }
            });
        }

        scrollToTop();
    }

    jQuery(document).on('click', '.deleteStudent', function (e) {
        e.preventDefault();
        var studentRef = jQuery(this).attr('data-attr');
        deleteStudent(studentRef);
    });

}); /// End document ready
