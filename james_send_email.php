<?php

function send_mail($booking_subject, $booking_message, $email)
{
    wp_mail($email, $booking_subject, $booking_message);

}

function send_slot_mail($slotId)
{
    //find the wp_post
    global $slotStartDate, $slotNoOfTables, $slotTotalCost, $slotLocation, $slotUserName, $slotUserContact, $slotUserMail;
    $slotStartDate   = get_post_meta($slotId, 'StartDate', true);
    $slotNoOfTables  = get_post_meta($slotId, 'NoOfTables', true);
    $slotTotalCost   = get_post_meta($slotId, 'TotalCost', true);
    $slotLocation    = get_post_meta($slotId, 'Location', true);
    $slotUserName    = get_post_meta($slotId, 'Name', true);
    $slotUserContact = get_post_meta($slotId, 'Contact', true);
    $slotUserMail = get_post_meta($slotId,'Email',true);
    send_admin_mail();
    send_user_mail();
    
}

function send_admin_mail()
{
    global $slotStartDate, $slotNoOfTables, $slotTotalCost, $slotLocation, $slotUserName, $slotUserContact, $admin_mail;
    $totalCost = $slotTotalCost;

    $sms_admin_msg_tpl = "Name:$slotUserName Tel No:$slotUserContact Booking:$slotStartDate Tables:$slotNoOfTables Loc:$slotLocation $$totalCost";
    $message           = $sms_admin_msg_tpl;
    $subject = "TheStudyArea - New Appointment for $slotStartDate";
    send_mail($subject,$message, $admin_mail);
}

function send_user_mail()
{
    global $slotStartDate, $slotNoOfTables, $slotTotalCost, $slotLocation, $slotUserName, $slotUserContact, $slotUserMail;
    $totalCost        = $slotTotalCost;

    $sms_user_msg_tpl = "We have received your payment and your session on $slotStartDate has been confirmed. For assistance, you can contact our staff, Clement @96491385. Your Pins is $pin #";
    $message          = $sms_user_msg_tpl;
    $subject = "TheStudyArea - Booking Confirmed for $slotStartDate";
    send_mail($subject, $message, array($slotUserMail));
}