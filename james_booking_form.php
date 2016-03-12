<style>
    #booking_form input[type="radio"]
    {
    margin-top: 14px;
    }
</style>

<div class="container-fluid">
    <!--
        to show the form to redirect in the event of the user not login and show links to register page
        save this as a custom post type first
        do the paypal account submission first 
    -->
    <?php
        if(is_user_logged_in())
        {
    ?>    
    <form action="" id="booking_form" method="POST">
        <div class="col-sm-6 control-group">
            <label for="bkFirstName" class="control-label">
                First Name
            </label>
            <input type="text" class="form-control" id="bkFirstName" value readonly="true" name="bkFirstName"/>
        </div>
        <div class="col-sm-6 control-group"  >
            <label for="bkLastName" class="control-label">
                Last Name
            </label>
            <input type="text" class="form-control" id="bkLastName" value  readonly="true" name="bkLastName"/>
        </div>
        <div class="col-sm-6 control-group"  >
            <label for="bkNric" class="control-label">
                NRIC / Passport No
            </label>
            <input type="text" class="form-control" id="bkNric" value readonly="true" required name="bkNric"/>
        </div>
        <div class="col-sm-6 control-group"  >
            <label for="bkContact" class="control-label">
                Contact No.
            </label>
            <input type="text" class="form-control" id="bkContact" value  readonly="true" required name="bkContact"/>
        </div>
        <div class="col-sm-12">
            <label for="bkEmail"  class="control-label">
                Email Address
            </label>
            <input type="text" class="form-control" id="bkEmail" value  readonly="true" required email name="bkEmail"/>
        </div>
        <div class="col-sm-12 control-group" >
            <label  class="control-label">
                Please advise if you are a Student or Adult?
            </label>
            <br/>
            <label class="radio-inline" for="bkStudentOrAdult1">
                <input type="radio" name="bkStudentOrAdult" id="bkStudentOrAdult1" value="Student"
                    checked/>
                    Student
                </label>
                <label class="radio-inline" for="bkStudentOrAdult2">
                    <input type="radio" name="bkStudentOrAdult" id="bkStudentOrAdult2" value="Adult" />
                    Adult
                </label>
                <label id="showIdMsg">
                    Please bring along your proof of student id if student is selected.
                </label>
            </div>
            <div class="col-sm-6 control-group">
                <label  class="control-label">
                    Session
                </label>
                <select class="form-control" id="bkSession" name="bkSessionw">
                    <option value="1" data-org="Slot A (9am to 9pm)">
                        Slot A (9am to 9pm)
                    </option>
                    <option value="2" data-org="Slot B (9pm to Next Day 9am)">
                        Slot B (9pm to Next Day 9am)
                    </option>
                    <option value="3" data-org="Slot C (9am to Next Day 9am)">
                        Slot C (9am to Next Day 9am)
                    </option>
                </select>
            </div>
            <div class="col-sm-6 control-group">
                <label for="bkStartDate"  class="control-label">
                    Slot Start Date
                </label>
                <input class="form-control" id="bkStartDate" name="bkStartDate" required/>
            </div>
            <div class="col-sm-12">
                <label  class="control-label">
                    Number of tables
                </label>
                <select class="form-control" id="bkNoOfTables" name="bkNoOfTables">
                    <?php
                    for ($i = 1; $i < 10; $i++) {
                    echo "<option value='$i'>$i</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-12">
                <input type="button" class="btn btn-default" value="Check Availability"/>
                <input type="submit" class="btn btn-default" value="Book Slot" name="bookSlot"/>
            </input>
        </div>
    </form>
</div>
<script>
    jQuery(document).ready(function() {
        jQuery('#bkStartDate').datepicker({
        dateFormat : 'dd M yy',
        minDate : new Date()
        });
        /*
        jQuery("#booking_form").validate(
        {
            //errorPlacement
            errorPlacement: function (error, element)
            {
                element.parent("div").addClass("has-error").append(error);
            },
            errorClass: "control-label has-error"
        });
        */

        //TODO have to come up with the total amount for the calculation of value * no of tables
        

        //flip the cost for bkSession in the event of changing

        jQuery('input[name="bkStudentOrAdult"]').click(function(event)
        {
            var half = this.value == "Student" ? "$10" : "$20";
            var full = this.value == "Student" ? "$15" : "$30";
            changeCost(half,full);
            jQuery("#showIdMsg").toggle();
        });

        changeCost("$10","$15");
    });

    function changeCost(half, full)
    {
        var options = jQuery("#bkSession").children("option");
        for(i=0;i<options.length;i++)
        {
            jQuery(options[i]).text(jQuery(options[i]).attr("data-org") + " " + (i<2 ? half : full));
        }
    }
</script>
<?php }else { ?>
    <div class="col-sm-12">
           <h3>User account is required in order to proceed.</h3>
           <a href="<?php echo get_home_url()?>/register">Register</a>&nbsp;<a href="<?php echo get_home_url();?>/wp-login.php?redirect_to=<?php echo get_home_url();?>/booking">Login</a>
        </div>
<?php 
} ?>

