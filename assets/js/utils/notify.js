const audio = new Audio('build/images/openup.mp3');
const uuid = new Date().getTime().toString();

if (localStorage) {
  localStorage.setItem('windowId', uuid)
  window.addEventListener('focus', function () {
    localStorage.setItem('windowId', uuid)
  })
}

function isActiveWindow () {
    if (localStorage) {
      return uuid === localStorage.getItem('windowId')
    } else {
      return true
    }
  }

export default function notify () {
    if (isActiveWindow()) {
        audio.volume = 0.7;
        audio.play();
    }
}