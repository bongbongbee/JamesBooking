<style>
    #booking_form label
    {
        margin-top:5px;
    }
    #booking_form .btn
    {
        margin-top:10px;
    }
</style>
<div class="bootstrap-styles">

<div class="container-fluid">
    <!--
        to show the form to redirect in the event of the user not login and show links to register page
        save this as a custom post type first
        do the paypal account submission first
    -->
    <?php
if (is_user_logged_in()) {
    $user_id = get_current_user_id();
    $name    = xprofile_get_field_data('1', $user_id);
    $nric    = xprofile_get_field_data('2', $user_id);
    $contact = xprofile_get_field_data('3', $user_id);
    $email   = xprofile_get_field_data('4', $user_id);
    ?>

    <form action="" id="booking_form" method="POST">
        <div class="col-sm-12 control-group">
            <label for="paramName" class="control-label">
                First Name
            </label>
            <input type="text" class="form-control" id="paramName"  readonly="true" name="paramName" value="<?php echo $name ?>"/>
        </div>

        <div class="col-sm-6 control-group"  >
            <label for="paramNric" class="control-label">
                NRIC / Passport No
            </label>
            <input type="text" class="form-control  required-group" id="paramNric"    name="paramNric" value="<?php echo $nric ?>" readonly=true/>
        </div>
        <div class="col-sm-6 control-group"  >
            <label for="paramContact" class="control-label">
                Contact No.
            </label>
            <input type="text" class="form-control  required-group contact" id="paramContact" name="paramContact" value="<?php echo $contact ?>" minlength="8" readonly=true/>
        </div>
        <div class="col-sm-12">
            <label for="paramEmail"  class="control-label">
                Email Address
            </label>
            <input type="text" class="form-control required-group email" id="paramEmail" name="paramEmail" value="<?php echo $email ?>" readonly=true/>
        </div>
        <div class="col-sm-12">
            <div class="showEditProfilePage" >
                To edit your profile please click <a href="<?php echo get_edit_user_link(); ?>">here</a>
            </div>
        </div>
        <div class="col-sm-12 control-group" >
            <label  class="control-label">
                Please advise if you are a Student or Adult?
            </label>
            <br/>
            <label class="radio-inline" for="paramStudentOrAdult1">
                <input type="radio" name="paramStudentOrAdult" id="paramStudentOrAdult1" value="Student"
                    checked/>
                    Student
                </label>
                <label class="radio-inline" for="paramStudentOrAdult2">
                    <input type="radio" name="paramStudentOrAdult" id="paramStudentOrAdult2" value="Adult" />
                    Adult
                </label><br/>
                <label id="showIdMsg">
                    Please bring along your proof of student id if student is selected.
                </label>
            </div>
            <div class="col-sm-12 control-group" >
            <label  class="control-label">
                Please choose which location?
            </label>
            <br/>
            <label class="radio-inline" for="paramLocation1">
                <input type="radio" name="paramLocation" id="paramLocation1" value="Tai Seng"
                    checked/>
                    Tai Seng
                </label>
                <label class="radio-inline" for="paramLocation2">
                    <input type="radio" name="paramLocation" id="paramLocation2" value="Bukit Batok" disabled="true" />
                    Bukit Batok (Coming Soon)
                </label>
            </div>
            <div class="col-sm-6 control-group">
                <label  class="control-label">
                    Session
                </label>
                <select class="form-control" id="paramSession" name="paramSession">
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
                <label for="paramStartDate"  class="control-label">
                    Slot Start Date
                </label>
                <input class="form-control required" id="paramStartDate" name="paramStartDate" />
            </div>
            <div class="col-sm-12">
                <label  class="control-label">
                    Number of tables
                </label>
                <select class="form-control" id="paramNoOfTables" name="paramNoOfTables">
                    <?php
for ($i = 1; $i < 10; $i++) {
        echo "<option value='$i'>$i</option>";
    }
    ?>
                </select>
            </div>
            <div class="col-sm-12">
                <input type="button" id="checkAvailBtn" class="btn btn-default" value="Check Availability"/>
                <input type="submit" class="btn btn-default hidden" value="Book Slot" name="bookSlot"/>
        </div>
    </form>
</div>
<?php } else {
    ?>
    <div class="col-sm-12">
           <h3>User account is required in order to proceed.</h3>
           <a href="<?php echo get_home_url() ?>/register">Register</a>&nbsp;<a href="<?php echo get_home_url(); ?>/wp-login.php?redirect_to=<?php echo get_home_url(); ?>/booking">Login</a>
        </div>
<?php
}
?>
</div>
