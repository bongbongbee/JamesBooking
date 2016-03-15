<?php

/*
Plugin Name: James Booking Module
Version: 1.0
Author: James Lian
 */

include_once 'james_slot_custom_posttype.php';

include_once 'james_registration.php';

include_once 'james_booking.php';

include_once 'james_paypal.php';

new james_registration;
new james_booking;
