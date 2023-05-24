async function deleteButtonClicked(venueId) {
    if (await displayModalForDelete()) {
        deleteVenueRequest(venueId);
    }
}
function deleteVenueRequest(venueId) {
    fetch('http://localhost/api/danceApi/venues?venueId=' + venueId, {
        method: 'DELETE'
    }).then(response => {
        response.json().then(data => {
            if (data.success) {
                location.reload();
            } else {
                displayModal("Cannot be Deleted", data.message)
            }
        });
    });
}