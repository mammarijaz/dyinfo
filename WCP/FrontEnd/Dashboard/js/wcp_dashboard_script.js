jQuery(document).ready(function ($) {

    var err_msg = $('#err_msg');
    $('#teacher___table, #school___table, #teacher___table_, #my___table').dataTable();

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
        let emailInput = $('input[name=teacher_email]').val();
        var object = [
            emailInput,
        ];
        var invitaitonValidation = is_valid_request(object);
        if (invitaitonValidation) {
            showLoading('teacher_invitation');
            $.ajax({
                method: 'post',
                data: $('#teacher_invitation').serialize(),
                url: ajaxurl,
                success: function (message) {
                    console.log(message);
                    var resposne = JSON.parse(message);
                    if (resposne === 'OK') {
                        err_msg.css('color', 'green');
                        err_msg.html('Email has been sent to the user');
                        $('#teacher_invitation')[0].reset();
                    } else {
                        err_msg.html(resposne);
                        err_msg.css('color', 'red');
                    }
                    showLoading('teacher_invitation', 'Send invitation to teacher');
                },
                error: function (err) {
                    err_msg.html(err.responseText);
                    err_msg.css('color', 'red');
                    console.log(err);
                    console.log('ajax is returning an err');
                    showLoading('teacher_invitation', 'Send invitation to teacher');


                    // temp
                    err_msg.css('color', 'green');
                    err_msg.html('Email has been sent to the user');
                    $('#teacher_invitation')[0].reset();


                },
            });
        } else {
            err_msg.html('Please fill out the require fields');
            err_msg.css('color', 'red');
        }
    }

    $(document).on('submit', 'form#teacher_invitation, form#school_invitation', function (e) {
        e.preventDefault();
        sendInvitationToUsers();
    });

    /**
     * Student Invitation
     * */
    function sendInvitationToStudent() {
        let emailInput = $('input[name=student_email]').val();
        let schoolRef = $('input[name=school_reference]').val();
        let teacherRef = $('input[name=teacher_reference]').val();
        var object = [
            emailInput,
            schoolRef,
            teacherRef,
        ];
        var invitaitonValidation = is_valid_request(object);

        if (invitaitonValidation) {
            showLoading('school_invitation');
            $.ajax({
                method: 'post',
                data: $('#school_invitation').serialize(),
                url: ajaxurl,
                success: function (message) {
                    console.log(message);
                    var resposne = JSON.parse(message);
                    if (resposne === 'OK') {
                        err_msg.css('color', 'green');
                        err_msg.html('Email has been sent to the user');
                        $('#school_invitation')[0].reset();
                    } else {
                        err_msg.html(resposne);
                        err_msg.css('color', 'red');
                    }
                    showLoading('school_invitation', 'Send invitation to Student');
                },
                error: function (err) {
                    err_msg.html(err.responseText);
                    err_msg.css('color', 'red');
                    console.log(err);
                    showLoading('school_invitation', 'Send invitation to Student');


                    // temp

                    err_msg.css('color', 'green');
                    err_msg.html('Email has been sent to the user');
                    $('#school_invitation')[0].reset();
                },
            });
        } else {
            err_msg.html('Please fill out the require fields');
            err_msg.css('color', 'red');
        }
        scrollToTop();
    }

    $(document).on('submit', 'form#school_invitation', function (e) {
        e.preventDefault();
        sendInvitationToStudent();
    });


    /**
     * Update Operations
     * */

    // edit teacher - show the modal
    $(document).on('click', '.edit-teacher', function (e) {
        e.preventDefault();

        var teacherRef = $(this).attr('data-attr');
        var object = {
            edit_id: 'edit_id_',
            full_name: 'full_name_',
        };

        for (var x in object) {
            //var value = $('td#' + object[x]).html().trim();
            var value = $('td#' + object[x] + teacherRef).html();
            $('input[name=' + [x] + ']').val(value);
        }
        $('#myModal').modal('show');
    });

    /**
     * Teacher Update
     * */
    // update the teacher in database
    function updateTeacher() {
        let teacherFullName = $('input[name=full_name]').val();
        let teacherEditID = $('input[name=edit_id]').val();
        var object = [
            teacherFullName,
            teacherEditID,
        ];
        var invitaitonValidation = is_valid_request(object);
        if (invitaitonValidation) {
            showLoading('teacher_update_form');

            $.ajax({
                method: 'post',
                data: $('#teacher_update_form').serialize(),
                url: ajaxurl,
                success: function (message) {
                    console.log(message);
                    var resposne = JSON.parse(message);

                    if (resposne === 'OK') {
                        err_msg.css('color', 'green');
                        err_msg.html('Teacher has been updated');
                        $('#teacher_update_form')[0].reset();
                        $('#myModal').modal('hide');
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
                    $('#school_invitation')[0].reset();

                },
            });
        } else {
            err_msg.html('Please fill out the require fields');
            err_msg.css('color', 'red');
        }

        scrollToTop();
    }

    $(document).on('click', '.update-teacher_buton', function (e) {
        e.preventDefault();
        updateTeacher();
    });

    /**
     * Student Update
     * */
    // update the teacher in database
    function updateStudent() {
        let teacherFullName = $('input[name=full_name]').val();
        let teacherEditID = $('input[name=edit_id]').val();
        let action = $('input[name=action]').val();
        let target = $('input[name=target]').val();
        var object = [
            teacherFullName,
            teacherEditID,
            action,
            target,
        ];
        var invitaitonValidation = is_valid_request(object);
        if (invitaitonValidation) {
            showLoading('student_update_form');


            $.ajax({
                method: 'post',
                data: $('#student_update_form').serialize(),
                url: ajaxurl,
                success: function (message) {
                    console.log(message);
                    var resposne = JSON.parse(message);
                    if (resposne === 'OK') {
                        err_msg.css('color', 'green');
                        err_msg.html('Teacher has been updated');
                        $('#student_update_form')[0].reset();
                        $('#myModal').modal('hide');
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
                    $('#student_update_form')[0].reset();
                    $('#myModal').modal('hide');
                    window.location.reload();
                },
            });
        } else {
            err_msg.html('Please fill out the require fields');
            err_msg.css('color', 'red');
        }

        scrollToTop();
    }

    $(document).on('click', '.update-student_button', function (e) {
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
            $.ajax({
                method: 'post',
                data: {
                    id: teacherRef,
                    action: 'wcp_dashboardHub',
                    target: 'teacher_delete',
                },
                url: ajaxurl,
                success: function (message) {
                    if (message == 'success') {
                        $('#teacher_' + teacherRef).css('background-color', 'red');
                        $('#teacher_action__' + teacherRef).html('This user has been deleted');
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
                    $('#teacher_' + teacherRef).css('background-color', 'red');
                    $('#teacher_action__' + teacherRef).html('This user has been deleted');;


                }
            });
        }
    }

    $(document).on('click', '.deleteTeacher', function (e) {
        e.preventDefault();
        var teacherRef = $(this).attr('data-attr');
        deleteTeacher(teacherRef);
    });

    /**
     * Delete Student
     * */
    function deleteStudent(studentRef) {
        if (studentRef == '' || typeof studentRef === 'undefined') {
            return err_msg.css('red', 'Invalid teacher');
        } else {
            $.ajax({
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
                        $('#teacher_' + studentRef).css('background-color', 'red');
                        $('#teacher_action__' + studentRef).html('This user has been deleted');
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

                    $('#teacher_' + studentRef).css('background-color', 'red');
                    $('#teacher_action__' + studentRef).html('This user has been deleted');

                }
            });
        }

        scrollToTop();
    }

    $(document).on('click', '.deleteStudent', function (e) {
        e.preventDefault();
        var studentRef = $(this).attr('data-attr');
        deleteStudent(studentRef);
    });

}); /// End document ready
