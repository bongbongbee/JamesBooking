
<?php

$admin_tel_no = array(
    '94764364', //james
    //,'6596491385'
);

$slotStartDate   = "";
$slotNoOfTables  = "";
$slotTotalCost   = "";
$slotLocation    = "";
$slotUserName    = "";
$slotUserContact = "";

function send_sms($booking_message, $tel_no)
{

    $james_sms_api_id  = '69660002';
    $james_sms_api_pwd = '1qaz)OKM';
    $james_sms_api_url = 'https://www.commzgate.net/gateway/SendMsg';
    foreach ($tel_no as $no) {
        $args = array(
            'body' => array('ID' => $james_sms_api_id, 'Password' => $james_sms_api_pwd, 'Mobile' => "65$no", 'Type' => 'A', 'Message' => $booking_message),
        );
        $response = wp_remote_post($james_sms_api_url, $args);
    }

}

function send_slot_sms($slotId)
{
    //find the wp_post
    global $slotStartDate, $slotNoOfTables, $slotTotalCost, $slotLocation, $slotUserName, $slotUserContact;
    $slotStartDate   = get_post_meta($slotId, 'StartDate', true);
    $slotNoOfTables  = get_post_meta($slotId, 'NoOfTables', true);
    $slotTotalCost   = get_post_meta($slotId, 'TotalCost', true);
    $slotLocation    = get_post_meta($slotId, 'Location', true);
    $slotUserName    = get_post_meta($slotId, 'Name', true);
    $slotUserContact = get_post_meta($slotId, 'Contact', true);
    send_admin_sms();
    send_user_sms();
    
}

function send_admin_sms()
{
    global $slotStartDate, $slotNoOfTables, $slotTotalCost, $slotLocation, $slotUserName, $slotUserContact, $admin_tel_no;
    $totalCost = $slotTotalCost;

    $sms_admin_msg_tpl = "Name:$slotUserName Tel No:$slotUserContact Booking:$slotStartDate Tables:$slotNoOfTables Loc:$slotLocation $totalCost";
    $message           = $sms_admin_msg_tpl;
    send_sms($message, $admin_tel_no);
}

function send_user_sms()
{
    global $slotStartDate, $slotNoOfTables, $slotTotalCost, $slotLocation, $slotUserName, $slotUserContact;
    $totalCost        = $slotTotalCost;
    $sms_user_msg_tpl = "We have successfully received your payment at $$totalCost and your slot is activated";
    $message          = $sms_user_msg_tpl;
    send_sms($message, array($slotUserContact));
}