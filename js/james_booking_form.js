jQuery(document).ready(function() {
    jQuery('#paramStartDate').datepicker({
        dateFormat: 'dd M yy',
        minDate: new Date()
    });
    var bookingFormValidator = jQuery("#booking_form").validate({
        //errorPlacement
        errorPlacement: function(error, element) {
            element.parent("div").addClass("has-error").append(error);
        },
        errorClass: "control-label has-error"
    });
    //TODO have to come up with the total amount for the calculation of value * no of tables
    //flip the cost for bkSession in the event of changing
    jQuery('input[name="paramStudentOrAdult"]').click(function(event) {
        var half = this.value == "Student" ? "$10" : "$20";
        var full = this.value == "Student" ? "$15" : "$30";
        changeCost(half, full);
        jQuery("#showIdMsg").toggle();
    });
    jQuery('#checkAvailBtn').click(function(event) {
        console.log("check");
        if(bookingFormValidator.checkForm())
        {
            displayAvailMessage();
        }
    });
    changeCost("$10", "$15");
});

function checkAvail()
{
    //
    https://api.jquery.com/jquery.get/
    jQuery.get(url:url
        );
}
function displayAvailMessage()
{

}

function changeCost(half, full) {
    var options = jQuery("#bkSession").children("option");
    for (i = 0; i < options.length; i++) {
        jQuery(options[i]).text(jQuery(options[i]).attr("data-org") + " " + (i < 2 ? half : full));
    }
}