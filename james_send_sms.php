<?php

function send_sms($booking_message)
{
    $api_id   = '69660002';
    $api_pwd  = 'TheStudyArea';
    $send_url = 'https://www.commzgate.net/gateway/SendMsg';

    $admin_tel_no = array(
        '6594764364','6596491385');

    

    foreach ($admin_tel_no as $no) {
        $args = array(
            'body' => array('ID' => $api_id, 'Password' => $api_pwd, 'Mobile' => $no, 'Type' => 'A', 'Message' => $booking_message),
        );
        $response = wp_remote_post($send_url, $args);
    }

}
