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
        } elseif ($_GET['func'] == "checkAvail") {
            $this->check_avail();

        } elseif ($_GET['paymentId']) {
            $this->receive_payment();
        }
    }

    public function checkAvailSlotsCount($startDate, $session)
    {
        $args = array('post_type' => 'slot',
            'meta_query'              => array(
                array(
                    'key'     => 'StartDate',
                    'value'   => $startDate,
                    'compare' => '=',
                ),
                array(
                    'key'     => 'session',
                    'value'   => $session,
                    'compare' => '=',
                ),
            ),
        );
        $the_query   = new WP_Query($args);
        $table_total = 0;
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $table_count = intval(get_post_meta(get_the_ID(), 'NoOfTables',true));
            $table_total += $table_count;

        }
        return $table_total;
    }

    public function check_avail()
    {
        $limit         = 30;
        $startDate     = $_GET["paramStartDate"];
        $noOfTables    = intval($_GET["paramNoOfTables"]);
        $paramLocation = $_GET["paramLocation"];
        $session       = intval($_GET["paramSession"]);
        $result        = array("startDate" => $startDate, "noOfTables" => $noOfTables, "location" => location, "session" => $session);

        $fullCount             = $this->checkAvailSlotsCount($startDate, "3");
        $halfAMCount           = $this->checkAvailSlotsCount($startDate, "1");
        $halfPMCount           = $this->checkAvailSlotsCount($startDate, "2");
        $result["fullCount"]   = $fullCount;
        $result["halfAMCount"] = $halfAMCount;
        $result["halfPMCount"] = $halfPMCount;
        $result["limit"]       = $limit;
        $result["halfAMAvail"] = ($limit - $noOfTables - $halfAMCount - $fullCount);

        if ($session == 1) {
            $result["available"] = ($limit - $noOfTables - $halfAMCount - $fullCount) >= 0;
        } elseif ($session == 2) {
            $result["available"] = ($limit - $noOfTables - $halfPMCount - $fullCount) >= 0;
        } elseif ($session == 3) {
            $result["available"] = (($limit - $noOfTables - $halfPMCount - $fullCount) >= 0) && (($limit - $noOfTables - $halfAMCount - $fullCount) >= 0);
        }

        header('Content-Type: application/json');
        echo json_encode($result);

        die();
    }

    public function shortcode()
    {

        ob_start();
        $this->booking_form();
        return ob_get_clean();
    }

    public function init_scripts()
    {
        wp_enqueue_style('bootstrap-css', plugins_url('bootstrap/css/bootstrap-tsa.css', __FILE__));
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
        wp_enqueue_script('jquery-validation', plugins_url('js/jquery.validate.min.js', __FILE__));
        wp_enqueue_script('jquery-validation-additional', plugins_url('js/additional-methods.js', __FILE__));
        wp_enqueue_script('james-booking-form', plugins_url('js/james_booking_form.js', __FILE__));

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
        $post_title = $_POST['paramName'] . " " . $_POST['paramStartDate'] . " at slot " . $_POST['paramSession'] . " Booked at " . date("d M y h:i:s");
        echo $post_title;

        $post_detail = array(
            'post_title'  => $post_title,
            'post_type'   => 'slot',
            'post_status' => 'Publish',
        );

        $post_id = wp_insert_post($post_detail, true);
        //add the created date
        add_post_meta($post_id, 'Book Date', date("d M y h:i:s"));

        foreach ($_POST as $key => $value) {
            if (strstr($key, 'param')) {
                $key = str_replace("param", "", $key);
                add_post_meta($post_id, $key, $value);
            }
        }

        $mypostobject = (object) $_POST;
        //temp to die
        die();

        //start the payment

        //addAndRedirectPayment($one_table_cost, $_POST[$GLOBALS['paramNoOfTables']], $item_name, $post_id);

    }

    public function receive_payment()
    {
        receive_paypal_payment();
        send_sms('test');
    }
}