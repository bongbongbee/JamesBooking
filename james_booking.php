<?php

class james_booking
{

    public function __construct()
    {

        add_shortcode('james_booking', array($this, 'shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'init_scripts'));
        add_action('init', array($this, 'add_book_post'));
    }

    public function add_book_post()
    {
        if ($_POST['bookSlot']) {

            $this->validation();
            $this->start_book();
        } elseif ($_GET['paymentId']) {
            $this->receive_payment();
        }
    }

    public function shortcode()
    {

        ob_start();
        $this->booking_form();
        return ob_get_clean();
    }

    public function init_scripts()
    {
        wp_enqueue_style('bootstrap-css', plugins_url('bootstrap/css/bootstrap.css', __FILE__));
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
        wp_enqueue_script('jquery-validation', plugins_url('js/jquery.validate.min.js', __FILE__));

        //wp_enqueue_style('flat-ui-kit', plugins_url('css/flat-ui.css', __FILE__));
        //TODO
        //include the script to have jquery validation inside check whether there is a jquery inside

    }

    public function booking_form()
    {

        include_once 'james_booking_form.php';
        ?>


    <?php
}

    public function validation()
    {

    }

    public function start_book()
    {

        $post_detail = array(
            'post_type'   => 'slot',
            'post_title'  => 'Slot at '+$GLOBALS['StartDate'],
            'post_status' => 'Publish',
        );

        $post_id = wp_insert_post($post_detail, true);
        add_post_meta($post_id, $paramFirstName, $_POST[$GLOBALS['paramFirstName']]);
        add_post_meta($post_id, $paramLastName, $_POST[$GLOBALS['paramLastName']]);
        add_post_meta($post_id, $paramNric, $_POST[$GLOBALS['paramNric']]);
        add_post_meta($post_id, $paramContact, $_POST[$GLOBALS['paramContact']]);
        add_post_meta($post_id, $paramEmail, $_POST[$GLOBALS['paramEmail']]);
        add_post_meta($post_id, $paramStudentOrAdult, $_POST[$GLOBALS['paramStudentOrAdult']]);
        add_post_meta($post_id, $paramSession, $_POST[$GLOBALS['paramSession']]);
        add_post_meta($post_id, $paramStartDate, $_POST[$GLOBALS['paramStartDate']]);
        add_post_meta($post_id, $paramNoOfTables, $_POST[$GLOBALS['paramNoOfTables']]);

        
        $one_table_cost = (($_POST[$GLOBALS['paramStudentOrAdult']] == "Student") ? 10 : 15) * (($_POST[$GLOBALS['paramSession']] == 3) ? 2 : 1);

        $mypostobject = (object) $_POST;

        //start the payment
        
        addAndRedirectPayment($one_table_cost, $_POST[$GLOBALS['paramNoOfTables']], $item_name, $post_id);

        
    }

    public function receive_payment()
    {
        receive_paypal_payment();
        send_sms('test');
    }
}