function fillTicketModal(performance) {
    clearTicketModals()
    document.getElementById('artistName').innerText = getArtistName(performance.artists);
    document.getElementById('dateTime').innerText = getFullDate(performance.date) + '   ' + performance.startTime + '-' + performance.endTime;
    document.getElementById('location').innerText = performance.venue;
    document.getElementById('price').innerText = "€" +performance.price.toFixed(2);
    document.getElementById('numberOfTickets').value = 1; //for now
    document.getElementById('performanceId').value = performance.id;
    document.getElementById('performanceModal').setAttribute('data-performancePrice', performance.price);

}
function clearTicketModals(){
    document.getElementById('artistName').innerText = '';
    document.getElementById('dateTime').innerText = '';
    document.getElementById('location').innerText = '';
    document.getElementById('price').innerText = '';
    document.getElementById('performanceId').value = '';
    document.getElementById('numberOfTickets').value = 1; //for now
    document.getElementById('performanceModal').setAttribute('data-performancePrice', '');
}
function getArtistName(artists) {
    let artistName = '';
    if (artists.length === 1)
        return artists[0].artistName;
    for (let i = 0; i < artists.length; i++) {
        artistName += '| ' + artists[i].artistName;
    }
    return artistName.slice(1);
}

function getFullDate(dateString) {
    const date = new Date(dateString);
    const options = {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    };
    return new Intl.DateTimeFormat('en-US', options).format(date);
}
function updatePrice(nrOfTicketInput){
    let nrOfTickets = nrOfTicketInput.value;
    let updatedPrice = nrOfTickets *  document.getElementById('performanceModal').getAttribute('data-performancePrice');
    document.getElementById('price').innerHTML = "€"+updatedPrice.toFixed(2);
}