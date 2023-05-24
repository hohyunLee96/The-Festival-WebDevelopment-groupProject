

function displayNav() {
    $("#navbarToggleBtn").toggle(function () {
        $("#navbarNavDropdown").hide();
    },
        function () {
            $("#navbarNavDropdown").show();
        },
    );
}

function displayAccountSettings() {
    $("#accountSettings").toggle(function () {
        $("#accountSettingsLinks").hide();
    }, function () {
        $("#accountSettingsLinks").show();
    },

    );
}

function hideAccountSettings() {

    $(document).on("click", function () {

        $("#accountSettingsLinks").hide();
    }
    );
}

function setUserEditingPermission() {

    $(document).ready(function () {


        $.ajax({
            url: "http://localhost/api/Users/retrieveUserPermissions?id=" + $("#userId").text(),
            type: "GET",
            dataType: "JSON",
            success: function (jsonStr) {
                if (jsonStr == "Customer") {
                    $("#manageUsersLink").hide();
                }
                else {
                    $("#manageUsersLink").show();
                }
            }
        });

    });
}

displayNav();
displayAccountSettings();
hideAccountSettings();
setUserEditingPermission();
