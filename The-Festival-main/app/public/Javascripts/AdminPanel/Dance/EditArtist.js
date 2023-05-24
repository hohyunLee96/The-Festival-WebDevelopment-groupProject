function btnSaveChangesClicked(artistId) {
    let artistName = document.getElementById("artistName").value;
    let artistDescription = document.getElementById("artistDescription").value;
    let selectedArtistStyles = getArtistStyles();

    //all Images
    let artistLogo = document.getElementById("artistLogo").files[0];
    let artistPortrait = document.getElementById("artistPortrait").files[0];
    let artistBanner = document.getElementById("artistBanner").files[0];
    let OtherArtistImages = document.getElementById("OtherArtistImages").files;
    if (artistName === "" || artistDescription === "" || selectedArtistStyles.length === 0) {
        displayError("Please fill in all fields");
        return;
    }

    let artist = {
        artistDetails: {
            id: artistId,
            name: artistName,
            description: artistDescription,
            styles: selectedArtistStyles,
        },
        artistLogo: artistLogo,
        artistPortrait: artistPortrait,
        artistBanner: artistBanner,
        OtherArtistImages: OtherArtistImages

    }
    if(artist.OtherArtistImages.length !== 0){
        if(!checkArtistOtherImagesMinimum(artist.OtherArtistImages)){
            displayError("Please upload at least 3 other images for artist");
            return;
        }
    }
    sendUpdateRequest(artist);
}

function getArtistStyles() {
    let selectedStyleIds = [];
    let styleCheckboxes = document.querySelectorAll('.artist-style-checkbox');
    styleCheckboxes.forEach((checkbox) => {
        if (checkbox.checked) {
            selectedStyleIds.push(checkbox.value);
        }
    });
    return selectedStyleIds;
}

function displayError(error) {
    let errorDiv = document.getElementById("errors");
    errorDiv.innerHTML = error;
    errorDiv.hidden = false;
}

function sendUpdateRequest(artist) {
    let formData = getFormData(artist);
    fetch('http://localhost/api/danceApi/artists?artistId=' + artist.artistDetails.id, {
        method: 'POST',
        body: formData
    }).then(response => {
        response.json()
            .then(data => {
                if (data.success) {
                    location.href = "/admin/dance/artists";
                } else {
                    displayError(data.message)
                }
            });
    }).catch(error => {
        console.log(error)
    });
}

function getFormData(artist) {
    const formData = new FormData();
    formData.append('artistDetails', JSON.stringify(artist.artistDetails));
    formData.append('artistLogo', artist.artistLogo);
    formData.append('artistPortrait', artist.artistPortrait);
    formData.append('artistBanner', artist.artistBanner);
    if (artist.OtherArtistImages.length !== 0) {
        for (let i = 0; i < artist.OtherArtistImages.length; i++) {
            formData.append('OtherArtistImages[' + i + ']', artist.OtherArtistImages[i]);
        }
    } else {
        formData.append('OtherArtistImages', '');
    }
    return formData;
}

function checkArtistOtherImagesMinimum(artistOtherImages) {
    if ( artistOtherImages.length < 3) {
        return false;
    }
    return true;
}


