const audio = new Audio('build/images/openup.mp3');

export default function notify () {
    audio.volume = 0.7;
    audio.play();
}