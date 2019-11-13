<?php


print_r($_POST);

/* actions */
if (!empty($_POST['action']) && $_POST['action'] == 'wcp_school_registration_teacher_invitation') {

    if (!filter_var($_POST['teacher_email'], FILTER_VALIDATE_EMAIL) || empty($_POST['teacher_email'])) {
        $emailErr = " <div class=\"alert alert-danger signup-error\">
            <strong>Error! </strong> Invalid Email Address
        </div>";
    } else {
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $body = get_site_url() . '/' . 'teacher-registration/' . '?query=' . $_POST['school_reference'] . '&ref=' . $_POST['teacher_email'];
        wp_mail($_POST['teacher_email'], 'School Invitation', $body, $headers);
        $emailErr = " <div class=\"alert alert-success signup-error\">
            <strong>Success! </strong>Email Sent to the user
        </div>";
    }

}

/* teacher delete action */
if (!empty($_GET['action']) && $_GET['action'] == 'DT' && !empty($_GET['delete_teacher'])) {

    global $wpdb;
    $wpdb->delete('wcp_teachers', array('id' => $_GET['delete_teacher']));

    if (!empty($wpdb->last_error)) {
        echo '<h1 style="color: red">' . $wpdb->last_error . '</h1>';
    }
}

/* upddate teacher */
if (!empty($_POST['action']) && $_POST['action'] == 'teacher_update') {

    global $wpdb;
    $validationCheck = ['id' => 'Teacher Reference is missing', 'full_name' => 'Teacher full name is missing'];

    $proceed = true;
    foreach ($validationCheck as $key => $value) {
        if (empty($_POST[$key])) {

            $emailErr = "";
            echo '<div class="alert alert-danger signup-error"><strong>Error!</strong>' . $value . '</div>';
            $proceed = false;
            break;
        }
    }

    if ($proceed) {
        $dataToUpdate = [
            'full_name' => $_POST['full_name'],
        ];
        $wpdb->update('wcp_teachers', $dataToUpdate, ['id' => $_POST['id']]);
        if ($wpdb->last_error) {
            echo '<div class="alert alert-danger signup-error"><strong>Error!</strong> ' . $wpdb->last_error . '</div>';
        } else {
            $_POST['action'] = null;
            echo '<div class="alert teacher-success alert-success signup-error"><strong>Success!</strong> Teacher Record has been updated</div>';
            wp_reset_postdata();
        }
    }
}

if (!empty($emailErr)) {
    echo $emailErr;
}
?>