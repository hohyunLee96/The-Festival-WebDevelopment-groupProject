const bookingInfo = JSON.parse(localStorage.getItem('bookingInfo'));
const tourLanguages = bookingInfo.tourLanguages;
const tourTime = bookingInfo.tourTime;
const tourDate = bookingInfo.tourDate;
const buttonContainer = document.getElementById('languageButtonsContainer');

document.querySelector('.panel-title.text-center:first-of-type').textContent = `Book your tour for ${tourDate} at ${tourTime}`;

const languagesArray = tourLanguages.split('<br>');

for (let i = 0; i < languagesArray.length; i++) {
    const language = languagesArray[i].trim(); // trim to remove any white space
    const button = document.createElement('button');
    button.type = 'button';
    button.name = 'TourLanguage';
    button.value = language.toLowerCase();
    button.textContent = language;
    button.className = 'btn btn-outline-dark';
    buttonContainer.appendChild(button);
    button.onclick = function () {
        document.getElementById("language").value = language.toLowerCase();
    }
}




document.getElementById('tour-date').value = tourDate;
document.getElementById('tour-time').value = tourTime;

// Display date and time
document.getElementById('tour-date-display').textContent = tourDate;
document.getElementById('tour-time-display').textContent = tourTime;
