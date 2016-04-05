<?php
$PIN_LENGTH = 6;
 
function set_slot_pin($slotId)
{
    //find the wp_post
    global $slotStartDate, $slotNoOfTables, $slotTotalCost, $slotLocation, $slotUserName, $slotSession;
    $slotStartDate   = get_post_meta($slotId, 'StartDate', true);
    $slotNoOfTables  = get_post_meta($slotId, 'NoOfTables', true);
    $slotLocation    = get_post_meta($slotId, 'Location', true);
    $slotSession = get_post_meta($slotId,'Session', true);
    $pins = james_generate_pin($slotStartDate, $slotSession, $slotNoOfTables);
    add_post_meta($slotId, 'pin', join(",", $pins));
}

function james_generate_pin($startDate, $session, $noOfTables)
{
    $pin_array = array();

    for ($noOfPins = 0; $noOfPins < intval($noOfTables); $noOfPins++) {

        //see whether the generated pin is ok
        $pin = james_get_generate_pin();
        while (!checkPinExist($startDate, $session, $pin) && !in_array($pin, $pin_array)) {
            $pin = james_get_generate_pin();
        }

        array_push($pin_array, $pin);
    }
    return $pin_array;
}

function checkPinExist($startDate, $session, $pin)
{
    $args = array('post_type' => 'slot',
        'meta_query'              => array(
            array(

                'key'     => 'StartDate',
                'value'   => $startDate,
                'compare' => '=',
            ),
            array(
                'key'     => 'Session',
                'value'   => $session,
                'compare' => '=',
            ),
            array(
                'key'     => 'pin',
                'value'   => '%$pin%',
                'compare' => 'like',
            ),
        ),
    );
    $the_query   = new WP_Query($args);
    $table_total = 0;
    return !$the_query->have_posts();
}

function james_get_generate_pin()
{
    global $PIN_LENGTH;
    $pin = "";
    for ($i = 0; $i < $PIN_LENGTH; $i++) {
        $pin .= rand(0, 9);
    }
    return $pin;
}