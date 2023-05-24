function btnSaveChangesClicked(venueId,addressId) {
    let venueName = document.getElementById("input-Venue Name").value;
    let houseNumber = document.getElementById("input-houseNumber").value;
    let houseNumberAdditional = document.getElementById("input-additionalHouseNumber").value;
    let postcode = document.getElementById("input-postCode").value;
    let streetName = document.getElementById("input-streetName").value;
    let city = document.getElementById("input-city").value;
    let country = document.getElementById("input-country").value;
    if (venueName === "" || houseNumber === "" || postcode === "" || streetName === "" || city === "" || country === "") {
        displayError("Please fill in all fields");
        return;
    }
    else if (!isValidPostCode(postcode)) {
        displayError("Please enter a valid postcode");
        return;
    }
    let venue = {
        venueId: venueId,
        venueName: venueName,
        address: {
            addressId: addressId,
            houseNumber: houseNumber,
            houseNumberAdditional: houseNumberAdditional,
            postcode: postcode,
            streetName: streetName,
            city: city,
            country: country
        }
    };
    sendPutRequest(venue);
}
function sendPutRequest(venue){
    fetch('http://localhost/api/danceApi/venues', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(venue)
    }).then(response => {
        response.json()
            .then(data => {
            if (data.success) {
                location.href = "/admin/dance/venues";
            } else {
                displayError( data.message)
            }
        });
    });
}
function isValidPostCode(postCode) {
    let regex = /^[0-9]{4}[a-zA-Z]{2}$/;
    if (regex.test(postCode)) {
        return true;
    }
    return false;
}
function displayError(message) {
    let error = document.getElementById("errors");
    error.innerHTML = message;
    error.hidden = false;
}