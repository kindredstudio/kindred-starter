let alertBarContainer;
let alertClose;

window.addEventListener('DOMContentLoaded', () => {
  alertBarContainer = document.querySelector('.c-alert-bar');
  alertClose = document.querySelector('.c-alert-bar__close');
});

let cookie = localStorage.getItem('somerville-alert-close');

if (cookie == undefined || cookie == null) {
  cookie = 0;
}

const DAYS_TO_REMEMBER = 1;

const currentTime = new Date().getTime();

// A boolean that tells us whether to show the alert (the math is to convert milliseconds to days)
const isWithinDaysToRemember = (currentTime - cookie) / (1000 * 60 * 60 * 24) < DAYS_TO_REMEMBER;

const rememberClose = () => localStorage.setItem('somerville-alert-close', currentTime);

const closeAlertBar = () => (alertBarContainer.style.display = 'none');

export const alertBar = () => {
  if (isWithinDaysToRemember) {
    closeAlertBar();
    return;
  }

  if (alertBarContainer) {
    alertClose.addEventListener('click', () => {
      closeAlertBar();
      rememberClose();
    });
  }
};
