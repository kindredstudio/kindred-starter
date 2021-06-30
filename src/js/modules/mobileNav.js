export default function mobileNav() {
  let slideOut = document.querySelector('[data-slide-out]');
  let navTrigger = document.querySelector('[data-nav-trigger]');

  let toggleNav = () => {
    document.documentElement.classList.toggle('nav-is-open');
    slideOut.classList.toggle('active');
    navTrigger.classList.toggle('active');
  };

  navTrigger.addEventListener('click', toggleNav);
}
