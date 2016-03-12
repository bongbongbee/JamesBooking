<style>
    #booking_form input[type="radio"]
    {
    margin-top: 14px;
    }
</style>
<div class="container-fluid">
    <!--
        contact number validation on registration form
        have the auto population on a js to populate the prices
    -->
    <form action="" id="booking_form">
        <div class="col-sm-6">
            <label for="bkFirstName">
                First Name
            </label>
            <input type="text" class="form-control" id="bkFirstName" value readonly="true"/>
        </div>
        <div class="col-sm-6">
            <label for="bkLastName">
                Last Name
            </label>
            <input type="text" class="form-control" id="bkLastName" value  readonly="true"/>
        </div>
        <div class="col-sm-6">
            <label for="bkNric">
                NRIC / Passport No
            </label>
            <input type="text" class="form-control" id="bkNric" value readonly="true"/>
        </div>
        <div class="col-sm-6">
            <label for="bkContact">
                Contact No.
            </label>
            <input type="text" class="form-control" id="bkContact" value  readonly="true"/>
        </div>
        <div class="col-sm-12">
            <label for="bkEmail">
                Email Address
            </label>
            <input type="text" class="form-control" id="bkEmail" value  readonly="true"/>
        </div>
        <div class="col-sm-12">
            <label >
                Please advise if you are a Student or Adult?
            </label>
            <br/>
            <label class="radio-inline" for="bkStudentOrAdult1">
                <input type="radio" name="bkStudentOrAdult" id="bkStudentOrAdult1" value="Student"
                    />
                    Student
                </label>
                <label class="radio-inline" for="bkStudentOrAdult2">
                    <input type="radio" name="bkStudentOrAdult" id="bkStudentOrAdult2" value="Adult" />
                    Adult
                </label>
                <label >
                    Please bring along your proof of student id if student is selected.
                </label>
            </div>
            <div class="col-sm-6">
                <label >
                    Session
                </label>
                <select class="form-control" id="bkSession">
                    <option value="1">
                        Slot A (9am to 9pm)
                    </option>
                    <option value="2">
                        Slot B (9pm to Next Day 9am)
                    </option>
                    <option value="3">
                        Slot C (9am to Next Day 9am)
                    </option>
                </select>
            </div>
            <div class="col-sm-6">
                <label>
                    Number of tables
                </label>
                <select class="form-control" id="bkNoOfTables">
                    <?php
                    for ($i = 1; $i < 10; $i++) {
                    echo "<option value='$i'>$i</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-sm-12">
                <input type="button" class="btn btn-default" value="Check Availability"/>
                <input type="submit" class="btn btn-default" value="Book Slot"/>
            </input>
        </div>
    </form>
</div>
