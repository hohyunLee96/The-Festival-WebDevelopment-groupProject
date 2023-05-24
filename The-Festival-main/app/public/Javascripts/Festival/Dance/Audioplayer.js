let audio = null;
let isPlaying = false;
let currentTrackId = null;
let isTrackPlaying = false;

// Play a track
function playTrack(url, trackId) {
    if (!audio || audio.src !== url) {
        audio = new Audio(url);
        audio.addEventListener('ended', function() {
            stopTrack(trackId);
        });
        audio.addEventListener('pause', function() {
            stopTrack(trackId);
        });
        audio.addEventListener('playing', function() {
            isTrackPlaying = true;
        });
    }

    audio.src = url;
    if (!isPlaying) {
        audio.play();
        isPlaying = true;
    }
    currentTrackId = trackId;
    updateButton(trackId);
}

// Pause a track
function pauseTrack() {
    if (isTrackPlaying) {
        audio.pause();
        isPlaying = false;
        isTrackPlaying = false;
    }
}

// Stop a track
function stopTrack(trackId) {
    audio.currentTime = 0;
    pauseTrack();
    updateButton(trackId);
}

// Toggle play/pause button and audio playback
function togglePlayPause(url, trackId) {
    if (isPlaying && audio.paused && audio.src === url) {
        audio.play();
        isPlaying = true;
    } else if (isPlaying && !audio.paused && audio.src === url) {
        pauseTrack();
    } else {
        if (isPlaying && audio.src !== url) {
            pauseTrack();
            let playingButtonId = currentTrackId;
            let playingButton = document.getElementById("playPauseButton" + playingButtonId);
            playingButton.innerHTML = '<i class="fa-solid fa-play fa-2xl"></i>';
        }
        playTrack(url, trackId);
    }
}

// Update the play/pause button
function updateButton(trackId) {
    let button = document.getElementById("playPauseButton" + trackId);
    if (trackId === currentTrackId) {
        button.innerHTML = isPlaying ? '<i class="fa-solid fa-pause fa-2xl"></i>' : '<i class="fa-solid fa-play fa-2xl"></i>';
    } else {
        button.innerHTML = '<i class="fa-solid fa-play fa-2xl"></i>';
    }
}