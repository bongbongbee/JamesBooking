<?php

function send_sms($booking_message, $tel_no)
{

    $james_sms_api_id  = '69660002';
    $james_sms_api_pwd = '1qaz)OKM';
    $james_sms_api_url = 'https://www.commzgate.net/gateway/SendMsg';
    
    foreach ($tel_no as $no) {
        $args = array(
            'body' => array('ID' => $james_sms_api_id, 'Password' => $james_sms_api_pwd, 'Mobile' => "65" . $no, 'Type' => 'A', 'Message' => $booking_message),
        );
        $response = wp_remote_post($james_sms_api_url, $args);
        
    }

}

function send_slot_sms($slotId)
{
    //find the wp_post
    global $slotStartDate, $slotNoOfTables, $slotTotalCost, $slotLocation, $slotUserName, $slotUserContact, $slotPin;
    $slotStartDate   = get_post_meta($slotId, 'StartDate', true);
    $slotNoOfTables  = get_post_meta($slotId, 'NoOfTables', true);
    $slotTotalCost   = get_post_meta($slotId, 'TotalCost', true);
    $slotLocation    = get_post_meta($slotId, 'Location', true);
    $slotUserName    = get_post_meta($slotId, 'Name', true);
    $slotUserContact = get_post_meta($slotId, 'Contact', true);
    $slotPin         = get_post_meta($slotId, 'pin', true);
    
    send_admin_sms();
    
    send_user_sms();
}

function send_admin_sms()
{
    global $slotStartDate, $slotNoOfTables, $slotTotalCost, $slotLocation, $slotUserName, $slotUserContact, $admin_tel_no, $slotPin;
    $totalCost = $slotTotalCost;

    $sms_admin_msg_tpl = "Name:$slotUserName Tel No:$slotUserContact Booking:$slotStartDate Tables:$slotNoOfTables Loc:$slotLocation $$totalCost $slotPin";
    $message           = $sms_admin_msg_tpl;
    send_sms($message, $admin_tel_no);
}

function send_user_sms()
{
    global $slotStartDate, $slotNoOfTables, $slotTotalCost, $slotLocation, $slotUserName, $slotUserContact, $slotPin;
    $totalCost = $slotTotalCost;
    $pins      = explode(",", $slotPin);
    foreach ($pins as $pin) {
        $sms_user_msg_tpl = "We have received your payment and your session on $slotStartDate has been confirmed. For assistance, you can contact our staff, Clement @96491385. Your Pins is $pin #";
        $message          = $sms_user_msg_tpl;
        send_sms($message, array($slotUserContact));
    }

}
