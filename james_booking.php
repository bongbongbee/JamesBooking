<?php

class james_booking
{

    public function __construct()
    {

        add_shortcode('james_booking', array($this, 'shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'init_scripts'));
    }

    public function shortcode()
    {

        ob_start();

        if ($_POST['bookSlot']) {

            $this->validation();
            $this->booking();
        }

        if($_GET['paymentId'])
        {
            $this->receive_payment();
        }

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

    public function booking()
    {
        $paramFirstName      = "bkFirstName";
        $paramLastName       = "bkLastName";
        $paramNric           = "bkNric";
        $paramContact        = "bkContact";
        $paramEmail          = "bkEmail";
        $paramStudentOrAdult = "bkStudentOrAdult";
        $paramSession        = "bkSession";
        $paramStartDate      = "bkStartDate";
        $paramNoOfTables     = "bkNoOfTables";
        
        
        $post_detail = array(
            'post_type'   => 'slot',
            'post_title'  => 'Slot at '+$_POST[$paramStartDate],
            'post_status' => 'Publish',
        );

        $post_id = wp_insert_post($post_detail, true);
        add_post_meta($post_id, $paramFirstName, $_POST[$paramFirstName]);
        add_post_meta($post_id, $paramLastName, $_POST[$paramLastName]);
        add_post_meta($post_id, $paramNric, $_POST[$paramNric]);
        add_post_meta($post_id, $paramContact, $_POST[$paramContact]);
        add_post_meta($post_id, $paramEmail, $_POST[$paramEmail]);
        add_post_meta($post_id, $paramStudentOrAdult, $_POST[$paramStudentOrAdult]);
        add_post_meta($post_id, $paramSession, $_POST[$paramSession]);
        add_post_meta($post_id, $paramStartDate, $_POST[$paramStartDate]);
        add_post_meta($post_id, $paramNoOfTables, $_POST[$paramNoOfTables]);

        //start the payment
        addCreditCard();
    }

    public function receive_payment()
    {
        receive_paypal_payment();
    }
}