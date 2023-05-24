
function btnSaveChangesClicked(performanceId) {

    let performanceDate=document.getElementById("performanceDate").value;
    let performanceStartTime=document.getElementById("performanceStartTime").value;
    let performanceDuration=document.getElementById("performanceDuration").value;
    let participatingArtists=getParticipatingArtist();
    let performanceSession = document.getElementById("performanceSession").value;
    let performanceVenue = document.getElementById("performanceVenue").value;
    let performanceTotalTickets = document.getElementById("performanceTotalTickets").value;
    let performancePrice = document.getElementById("performancePrice").value;
    let performanceAvailableTickets= document.getElementById("performanceAvailableTickets").value;
    if(performanceDate===""||performanceStartTime===""||performanceDuration===""
        ||participatingArtists.length===0||performanceSession===""||performanceVenue===""
        ||performanceTotalTickets===""||performancePrice===""||performanceAvailableTickets===""){
        displayError("Please fill in all  required fields");
        return;
    }
    let performance={
        id:performanceId,
        date:performanceDate,
        startTime:performanceStartTime,
        duration:performanceDuration,
        participatingArtists:participatingArtists,
        sessionId:performanceSession,
        venueId:performanceVenue,
        totalTickets:performanceTotalTickets,
        totalPrice:performancePrice,
        availableTickets:performanceAvailableTickets
    }
    sendEditRequest(performance);
}

function displayError(error) {
    let errorDiv = document.getElementById("errors");
    errorDiv.innerHTML = error;
    errorDiv.hidden = false;
}
function getParticipatingArtist() {
    let selectedArtistIds = [];
    let artistCheckboxes = document.querySelectorAll('.participatingArtists-checkBox');
    artistCheckboxes.forEach((checkbox) => {
        if (checkbox.checked) {
            selectedArtistIds.push(checkbox.value);
        }
    });
    return selectedArtistIds;
}
function sendEditRequest(performance){
    fetch('http://localhost/api/danceApi/performances', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(performance)
    }).then(response => {
        response.json()
            .then(data => {
                if (data.success) {
                    location.href = "/admin/dance/performances";
                } else {
                    displayError( data.message)
                }
            });
    });
}