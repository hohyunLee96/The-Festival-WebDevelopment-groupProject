//working code with extra info on hovering container
const tourContainers = document.querySelectorAll('.tourContainer');

tourContainers.forEach(function (tourContainer) {
    const tourLanguages = Array.from(tourContainer.querySelectorAll('.tourLanguage'))
        .map(lang => lang.textContent)
        .join('<br>');

    const tourTime = tourContainer.querySelector('.tourTime');
    const tourData = JSON.parse(tourContainer.querySelector('.tourLanguage').dataset.tour);
    console.log(typeof tourData)
    tourTime.style.marginTop = '4px';

    const extraInfo = document.createElement('div');
    extraInfo.style.display = 'none';
    extraInfo.style.position = 'absolute';
    extraInfo.style.top = '0';
    extraInfo.style.left = '0';
    extraInfo.style.right = '0';
    extraInfo.style.backgroundColor = 'white';
    extraInfo.style.padding = '10px';
    extraInfo.style.fontSize = '18px';
    extraInfo.style.fontWeight = 'bold';
    extraInfo.style.textAlign = 'center';
    extraInfo.style.borderRadius = '20%';
    extraInfo.style.border = '4px solid rgba(0, 0, 0, 1)';
    extraInfo.style.width="220px";

    extraInfo.innerHTML =
        `${tourData.tourDate} ${getDayOfWeek(tourData.tourDate)}<br>
     Duration: ${tourData.duration}<br>
     <button class="btn btn-primary btn-sm mt-3">Book Ticket</button>
     <br><br>
     ${tourLanguages}<br>
     ${tourData.historyTourLocations}`;

    const bookButton = extraInfo.querySelector('.btn');
    bookButton.addEventListener('click', function () {
        // Store the booking information in localStorage
        const bookingInfo = {
            tourDate: tourData.tourDate,
            dayOfWeek: getDayOfWeek(tourData.tourDate),
            tourLanguages: tourLanguages,
            tourTime: tourData.time,
        };

        if (bookingInfo) {
            localStorage.setItem('bookingInfo', JSON.stringify(bookingInfo));
            // Navigate to the booking page
            window.location.href = '/festival/history/ticketSelection';
        } else {
            console.error('bookingInfo is null or undefined');
        }
    });


    tourContainer.appendChild(extraInfo);

    tourContainer.addEventListener('mouseover', function () {
        extraInfo.style.display = 'block';
    });

    tourContainer.addEventListener('mouseout', function () {
        extraInfo.style.display = 'none';
    });
});

function getDayOfWeek(dateString) {
    const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const tourDate = new Date(dateString);
    const dayOfWeek = daysOfWeek[tourDate.getDay()];
    return dayOfWeek;
}
