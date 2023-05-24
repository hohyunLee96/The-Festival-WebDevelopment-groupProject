
function retrieveUserPermissions() {
   
    $(document).ready(function () {
    $.ajax({
        url: "http://localhost/api/Users/retrieveUserPermissions?id=" + $("#userId").text(),
        type: "GET",
        dataType: "JSON",
        success: function (jsonStr) {
            if(jsonStr=="Customer"){
                $("#userRole").hide();}
                else {
                    $("#userRole").show();
                }
        }
    });

});

}



function checkEmailAddress() {
   
    $("#email").on("input", function (e) {  
    $.ajax({
        url: "http://localhost/api/Users/checkEmailAddress?email=" + $("#email").val(),
        type: "GET",
        dataType: "JSON",
        success: function (jsonStr) {
            if(JSON.stringify(jsonStr)=="true"){
            $("#emailValidation").text("This email address is already taken");}
            else {
                $("#emailValidation").text("");
            }
        }
    });

});

}


function showPasswordFields() {
    $('#changePasswordCheckBox').click(function () {
        $('#password-fields').slideToggle();
    });
}


function submitAccountInfo() {

    $(document).ready(function () {

        $("#updateForm").on("submit", function (event) {
            if ($("#changePasswordCheckBox").prop("checked")) {
                var newPassword = $("#newPassword").val();
                var confirmPassword = $("#confirmPassword").val();

                if (newPassword !== confirmPassword) {
                    $("#validationMessage").text("Passwords Do Not Match!");
                    $("#submit").prop("disabled", true);
                    event.preventDefault();

                }
                else {
                    event.currentTarget.submit();
                }
            }
        });
    });
}

function enableSubmitButton() {

    $("#newPassword").on("input", function (e) {
        $("#submit").prop("disabled", false);
    });
    $("#confirmPassword").on("input", function (e) {
        $("#submit").prop("disabled", false);
    });

}

retrieveUserPermissions();
checkEmailAddress();
showPasswordFields();
submitAccountInfo();
enableSubmitButton();


