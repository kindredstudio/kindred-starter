import $ from 'jquery';
import 'slick-carousel';

let slider;
let width;

const DEFAULT_WIDTH = 950;

const slickSettings = {
  lazyLoad: 'ondemand',
  infinite: true,
  prevArrow,
  nextArrow,
};

window.addEventListener('DOMContentLoaded', () => {
  slider = $('.js-slider--r');
  width = slider.data('width');
});

function slickResponsively() {
  // The width to initialize the slider at
  const initWidth = width || DEFAULT_WIDTH;

  // True when the window width is less than our init width
  const shouldInit = window.innerWidth <= initWidth;
  // True if the slider is already initialized
  const alreadyInit = slider.hasClass('slick-initialized');

  // Initialize or uninitialize the slider, depending
  if (shouldInit && !alreadyInit) {
    slider.slick(slickSettings);
  } else if (!shouldInit && alreadyInit) {
    slider.slick('unslick');
  }
}

export const responsiveSlider = () => {
  slickResponsively();
  window.addEventListener('resize', slickResponsively);
};
