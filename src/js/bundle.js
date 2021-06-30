import mobileNav from './modules/mobileNav';
import accordion from './modules/accordion';

let go = () => {
  accordion();
  mobileNav();
};

window.addEventListener('DOMContentLoaded', go);
