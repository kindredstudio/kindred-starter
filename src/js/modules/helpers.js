export const isMobile = () => {
  return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
};

function onFirstTouch() {
  document.documentElement.classList.add('user-is-touching');
  window.removeEventListener('touchstart', onFirstTouch, false);
}

export const detectTouch = () => window.addEventListener('touchstart', onFirstTouch, false);
