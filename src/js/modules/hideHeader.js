let header;
let scrollPosition = 0;
const TARGET_OFFSET = 80;

window.addEventListener('DOMContentLoaded', () => {
  header = document.querySelector('.site-header');
});

function handleScroll() {
  const currentOffset = window.pageYOffset || document.documentElement.scrollTop;
  const isBeyondOffset = currentOffset > TARGET_OFFSET;
  const isMovingUp = document.body.getBoundingClientRect().top > scrollPosition;

  isBeyondOffset ? header.classList.add('scrolled') : header.classList.remove('scrolled');
  isMovingUp ? header.classList.add('up') : header.classList.remove('up');
  scrollPosition = document.body.getBoundingClientRect().top;
}

export const hideHeader = () => window.addEventListener('scroll', handleScroll, { passive: true });
