jQuery(document).ready(function ($) {

    var err_msg = $('#err_msg');
    var formRef = 'form#makeClassRoom';
    var formRefWithoutHash = 'makeClassRoom';
    var assignStudentClassRoom = 'form#assignStudentToClassRoomForm';
    var assignStudentClassRoomWithoutHash = 'form#assignStudentToClassRoomForm';

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
     * Class Room Operations.
     * */

    // add or edit class room
    function makeClassRoomOperation() {

        let schoolName = $('input[name=class_room_name]').val();
        let teacherRef = $('input[name=teacher_id]').val();
        let schoolRef = $('input[name=school_id]').val();
        var object = [
            schoolName,
            teacherRef,
            schoolRef,
        ];
        var invitaitonValidation = is_valid_request(object);

        if (invitaitonValidation) {

            showLoading(formRefWithoutHash);
            ajaxurl = $(formRef).attr("action");
            err_msg.html('Please wait...');

            var formData = $(formRef).serialize();
            inputManager(true);
            $.ajax({
                url: ajaxurl,
                method: "POST",
                dataType: "json",
                data: formData,
            }).always(function (response) {
                console.log(response);

                if (response.status === 'ok' || response.status === 'OK' || response === 'OK') {
                    err_msg.css('color', "green");
                    err_msg.html("Registration completed");
                    err_msg.html(response.msg);
                    $(formRef)[0].reset();
                    //window.location.replace(response.url);
                    err_msg.html('School has created');
                } else {
                    err_msg.css('color', "red");
                    err_msg.html(response.error);
                    err_msg.html(response.responseText);
                    err_msg.html(response);
                }

                // enable inputs
                inputManager(false);
                showLoading(formRefWithoutHash, 'MAKE CLASS ROOM');

            });
        } else {
            err_msg.html('Please fill out the require fields');
            err_msg.css('color', 'red');
        }


        scrollToTop();
    }
    $(document).on('submit', 'form#makeClassRoom', function (e) {
        e.preventDefault();
        makeClassRoomOperation();
    });

    // delete Class room
    function deleteCassRoom(classRoom) {

        if (classRoom == '') {
            return;
        } else {
            ajaxurl = $('#remoteResource').attr("action");
            err_msg.html('Please wait...');
            $.ajax({
                url: ajaxurl,
                method: "POST",
                dataType: "json",
                data: {
                    action: 'wcp_class_room',
                    target: 'deleteClass',
                    ref: classRoom,
                },
            }).always(function (response) {
                console.log(response);
                if (response.status === 'ok' || response.status === 'OK' || response === 'OK') {
                    err_msg.css('color', "green");
                    err_msg.html("Record has deleted");
                    $('#teacher_action__' + classRoom).closest('tr').css('background', 'red');
                    $('#teacher_action__' + classRoom).html('Class Record has been deleted');
                } else {
                    err_msg.css('color', "red");
                    err_msg.html(response.error + ' ' + response.responseText + ' ' + response);
                }
            });

        }
    }
    $(document).on('click', '.deleteClassRoom', function (e) {
        e.preventDefault();
        var deleteClassRoomVar = $(this).attr('data-attr');
        deleteCassRoom(deleteClassRoomVar);
    });


    /**
     * Class Enrolment Operations
     * */
    function assignStudentToClassRoom() {

        let input2 = $('select[name=class_room_id]').val();
        let input3 = $('select[name=wp_user_id]').val();
        var object = [
            input2,
            input3,
        ];
        var invitaitonValidation = is_valid_request(object);

        if (invitaitonValidation) {

            showLoading(assignStudentClassRoomWithoutHash);
            ajaxurl = $(assignStudentClassRoom).attr("action");
            console.log( 'ajax url =>  ' + ajaxurl);
            err_msg.html('Please wait...');
            var formData__ = $(assignStudentClassRoom).serialize();
            inputManager(true);
            $.ajax({
                url: ajaxurl,
                method: "POST",
                dataType: "json",
                data: formData__,
            }).always(function (response) {
                console.log(response);

                if (response.status === 'ok' || response.status === 'OK' || response === 'OK') {
                    err_msg.css('color', "green");
                    $(assignStudentClassRoom)[0].reset();
                    err_msg.html('Student has assigned to the class');
                } else {
                    err_msg.css('color', "red");
                    //err_msg.html(response.responseText + ' ' + response.error + ' ' + response);

                    console.log(response.responseText);
                    console.log(response.error);
                    console.log(response);
                }

                // enable inputs
                inputManager(false);
                showLoading(assignStudentClassRoomWithoutHash, 'Assign Student to Class room');

            });
        } else {
            err_msg.html('Please fill out the require fields');
            err_msg.css('color', 'red');
        }


        scrollToTop();

    }
    $(document).on('submit', 'form#assignStudentToClassRoomForm', function (e) {
        e.preventDefault();
        assignStudentToClassRoom();
    });


    // delete Class enrolent
    function deleteClassEnrolment(classRoomEnrolment) {
        if (classRoomEnrolment == '') {
            return;
        } else {
            ajaxurl = $('#remoteResource').attr("action");
            err_msg.html('Please wait...');
            $.ajax({
                url: ajaxurl,
                method: "POST",
                dataType: "json",
                data: {
                    action: 'wcp_class_room',
                    target: 'deleteClassEnrolment',
                    ref: classRoomEnrolment,
                },
            }).always(function (response) {
                console.log(response);
                if (response.status === 'ok' || response.status === 'OK' || response === 'OK') {
                    err_msg.css('color', "green");
                    err_msg.html("Record has been deleted");
                    $('#classEnrolment_' + classRoomEnrolment).closest('tr').remove();
                } else {
                    err_msg.css('color', "red");
                    err_msg.html(response.error + ' ' + response.responseText + ' ' + response);
                }
            });

        }
    };
    $(document).on('click', '.deleteClassRoomEnrolment', function (e) {
        e.preventDefault();
        var ref = $(this).attr('data-attr');
        deleteClassEnrolment(ref);
    })

}); /// End document ready
