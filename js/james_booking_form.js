var half = [10, 20];
var full = [15, 30];
jQuery(document).ready(function() {
    jQuery.validator.addMethod("contact", function(value, element) {
        return (value.startsWith("7") || value.startsWith("8") || value.startsWith("9"));
    }, "Please enter a valid contact number in the profile page");

    var currDate = new Date();
    if(currDate.getHours() < 9)
        currDate.setDate(currDate.getDate() - 1);

    jQuery('#paramStartDate').datepicker({
        dateFormat: 'dd M yy',
        minDate: currDate,
        onSelect: function() {
            toggleButtons(false);
        }
    });


    var bookingFormValidator = jQuery('#booking_form').validate({
        //errorPlacement
        errorPlacement: function(error, element) {
            jQuery(element).parent('div').addClass('has-error').append(error);
        },
        success: function(label, element) {
            jQuery(element).parent('div').removeClass('has-error');
        },
        errorClass: 'control-label has-error',
        rules: {
            paramNric: {
                require_from_group: [3, ".required-group"]
            },
            paramEmail: {
                require_from_group: [3, ".required-group"]
            },
            paramContact: {
                contact: true,
                require_from_group: [3, ".required-group"]
            }
        }
    });
    //TODO have to come up with the total amount for the calculation of value * no of tables
    //flip the cost for bkSession in the event of changing
    jQuery('input[name="paramStudentOrAdult"]').click(function(event) {
        var halfCur = "$" + (this.value == 'Student' ? half[0] : half[1]);
        var fullCur = "$" + (this.value == 'Student' ? full[0] : full[1]);
        changeCost(halfCur, fullCur);
        jQuery('#showIdMsg').toggle();
        toggleButtons(false);
    });
    jQuery('input[name="paramLocation"]').click(function(event) {
        toggleButtons(false);
    });
    jQuery('#paramSession, #paramNoOfTables').change(function(event) {
        toggleButtons(false);
    })
    jQuery('#checkAvailBtn').click(function(event) {
        if (jQuery('#booking_form').valid()) {
            checkAvail();
        }
    });
    changeCost('$10', '$15');
});


function checkAvail() {
    //https://api.jquery.com/jquery.get/
    //get num of tables
    //get date
    //get location
    var paramStartDate = jQuery('#paramStartDate').datepicker('getDate');
    paramStartDate = jQuery.datepicker.formatDate('dd M yy', paramStartDate);
    var paramNoOfTables = jQuery('#paramNoOfTables').val();
    var paramLocation = jQuery('input[name="paramLocation"]:checked').val();
    var paramSession = jQuery('select[name="paramSession"] option:selected').val();
    jQuery.get(document.URL, {
        paramStartDate: paramStartDate,
        paramNoOfTables: paramNoOfTables,
        paramLocation: paramLocation,
        paramSession: paramSession,
        func: 'checkAvail'
    }, function(data, textStatus, jqXHR) {
        afterCheckAvail(data);
    });
}

function afterCheckAvail(data) {
    //to validate the data is true
    console.log("data:" + JSON.stringify(data));
    if (data["available"]) {
        calculateTotal();
        displayAvailMessage();
    } else {
        displayNotAvailMessage();
    }
}

function calculateTotal() {
    var studentOrAdult = jQuery('input[name="paramStudentOrAdult"]:checked').val() == "Student";
    var oneTableCost = parseInt(jQuery('#paramSession option:selected').val()) <= 2 ? half[studentOrAdult ? 0 : 1] : full[studentOrAdult ? 0 : 1];
    var noOfTables = jQuery('#paramNoOfTables option:selected').val();
    var totalCost = parseInt(noOfTables) * oneTableCost;
    jQuery('#oneTableCost').val(oneTableCost);
    jQuery('#totalCost').val(totalCost);
    console.log("Total Cost : " + totalCost);
}

function displayAvailMessage() {
    //display the avaliable message
    jQuery('.availMsg').text("Slots Available. Please click Book Slots to PayPal").removeClass('hidden bg-danger').addClass("bg-success");
    toggleButtons(true);
}

function displayNotAvailMessage() {
    jQuery('.availMsg').text("Slots Not Available. Please select a later date.").removeClass('hidden bg-success').addClass("bg-danger");
    toggleButtons(false);
}

function toggleButtons(checked) {
    if (checked) {
        jQuery("#checkAvailBtn").addClass("hidden");
        jQuery("#bookBtn").removeClass("hidden");
    } else {
        jQuery("#checkAvailBtn").removeClass("hidden");
        jQuery("#bookBtn").addClass("hidden");
    }
}

function changeCost(half, full) {
    var options = jQuery('#paramSession').children('option');
    for (i = 0; i < options.length; i++) {
        jQuery(options[i]).text(jQuery(options[i]).attr('data-org') + ' ' + (i < 2 ? half : full));
    }
}