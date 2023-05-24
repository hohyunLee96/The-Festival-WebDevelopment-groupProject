async function deletePerformanceClicked(performanceId) {
    if (await displayModalForDelete()) {
        deletePerformance(performanceId);
    }
}

function deletePerformance(performanceId) {
    fetch('http://localhost/api/danceApi/performances?id=' + performanceId, {
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

