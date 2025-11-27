const audioPlayer = document.getElementById('audioPlayer');
const playButton = document.getElementById('playButton');
const playIcon = document.getElementById('playIcon');
const pauseIcon = document.getElementById('pauseIcon');

let isPlaying = false;
let refreshInterval = null;

playButton.addEventListener('click', () => {
    if (isPlaying) {
        pauseAudio();
    } else {
        playAudio();
    }
});

function playAudio() {
    isPlaying = true;
    audioPlayer.play();

    if (!refreshInterval) {
        refreshInterval = setInterval(() => {
            if (isPlaying) {
                audioPlayer.load();
                audioPlayer.play();
            }
        }, 600000); 
    }
}

function pauseAudio() {
    isPlaying = false;
    audioPlayer.pause();

    if (refreshInterval) {
        clearInterval(refreshInterval);
        refreshInterval = null;
    }
}
