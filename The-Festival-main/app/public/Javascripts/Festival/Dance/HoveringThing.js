const containers = document.querySelectorAll('.MyContainer');
containers.forEach(container => {
    const data = container.querySelector('.container').getAttribute('data-performance');
    if (!data) return;
    const performance = JSON.parse(data.replace(/\\/g, ''));
    const artists = performance.artists;
    const time = performance.date;
    const location = performance.venue;
    const label = container.querySelector('.container-fluid').textContent;

    container.addEventListener('mouseenter', () => {
        container.style.height = '450px';
        const artistString = getArtistName(artists);
        const timeString = typeof time === 'object' ? time.toString() : time;
        const locationString = typeof location === 'object' ? location.name : location;
        const price = performance.price.toFixed(2);
        const session = performance.session;

        const divContainer = document.createElement('div');
        divContainer.style.position = 'relative';
        const description = document.createElement('div');
        description.classList.add('pt-4', 'text-center');
        description.innerHTML = `
            <br><i class="fa-solid fa-user" style="color: #000000;"></i>:  ${artistString}
            <br><i class="fa-solid fa-calendar" style="color: #000000;"></i>: ${timeString}
            <br><i class="fa-solid fa-house" style="color: #000000;"></i>:  ${locationString}
            <br><i class="fa-solid fa-s" style="color: #000000;"></i>:      ${session}
            <br>€: ${price}
            `;
        divContainer.appendChild(description);
        const btnDiv = document.createElement('div');
        btnDiv.classList.add('mt-n4', 'pt-3');
        const bookButton = document.createElement('button');
        bookButton.classList.add('btn', 'btn-primary', 'book-button');
        bookButton.setAttribute('data-bs-toggle', "modal");
        bookButton.setAttribute('data-bs-target', "#performanceModal");
        bookButton.textContent = 'Book Now';
        bookButton.style.position = 'absolute';
        bookButton.style.top = '25%';
        bookButton.style.left = '50%';
        bookButton.style.transform = 'translate(-50%, -50%)';
        bookButton.addEventListener('click', () => {
            fillTicketModal(performance);
        });
        btnDiv.appendChild(bookButton);

        container.querySelector('.container-fluid').innerHTML = '';
        container.querySelector('.container-fluid').appendChild(btnDiv);
        container.querySelector('.container-fluid').appendChild(divContainer);
    });

    container.addEventListener('mouseleave', () => {
        container.style.height = '';
        container.querySelector('.container-fluid').innerHTML = label;
    });
});
function getArtistName(artists) {
    let artistName = '';
    if (artists.length === 1)
        return artists[0].artistName;
    for (let i = 0; i < artists.length; i++) {
        artistName += '|' + artists[i].artistName;
    }
    return artistName.slice(1);
}
