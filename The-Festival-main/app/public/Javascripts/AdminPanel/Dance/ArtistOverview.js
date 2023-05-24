async function deleteButtonClicked(artistId) {
    const userChoice = await displayModalForDelete();
    if ( userChoice) {
        deleteArtistRequest(artistId);
    }
}
function deleteArtistRequest(artistId) {
    fetch('http://localhost/api/danceApi/artists?id=' + artistId, {
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