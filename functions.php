<?php

function wcp_create_slug($string)
{
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower(trim($string)));
    return $slug;
}

function wcp_array_flatten($array)
{
    if (!is_array($array)) {
        return FALSE;
    }
    $result = array();
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $result = array_merge($result, wcp_array_flatten($value));
        } else {
            $result[$key] = $value;
        }
    }
    return $result;
}

// Function to validate email using regular expression 
function wcp_email_validation($str)
{
    return (!preg_match(
        "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $str))
        ? FALSE : TRUE;
}

function wcp_send_mail($to, $subject, $body2)
{
    $blog_title = get_bloginfo('name');
    $blogtime = current_time('mysql');
    $to = $to;
    $subject = $blog_title . ' - ' . $subject;
    $body .= '<div style="max-width:700px;margin:0 auto;text-align:left; mso-element-frame-width:700px; mso-element:para-border-div; mso-element-left:center; mso-element-wrap:no-wrap-beside;background:#ededed ">';
    $body .= '<div style="background:rgba(255,255,255,0.8)!important;background:#fff;padding: 20px 20px 50px;">';
    //<img src="'.wp_get_attachment_url(373).'" style="width: 380px;max-width: 100%;margin: 0 auto;display: block;">
    $body .= '<div style="margin: 30px auto 40px;"><a href="' . home_url('/') . '">
                  ' . $blog_title . ' 
              </a></div>';
    $body .= '<div style="color: #404040;width: 640px;max-width: 100%;margin: 0 auto;background: #fff;padding: 54px 50px 50px;box-sizing: border-box;">';

    $body .= '<div style="color: #404040;font-size: 16px;text-align:center">';
    $body .= $body2;
    $body .= '</div>';

    $body .= '</div>';
    $body .= '</div>';
    $body .= '</div>';

    $headers = array('Content-Type: text/html; charset=UTF-8');
    return wp_mail($to, $subject, $body, $headers);
}

function wcp_rand_string( $length ) {

    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    return substr(str_shuffle($chars),0,$length);

}



?>