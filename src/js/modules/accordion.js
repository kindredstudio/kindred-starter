import $ from 'jquery';

export default function accordion() {

let triggers = document.querySelectorAll('.b-accordion__trigger');

  function toggleAccordion({ target }) {
    if (target.href) return;
    let $target = $(target);
    $target.toggleClass('active');
    $target.next('.b-accordion__hidden').slideToggle();
  };

  triggers.forEach((trigger) => trigger.addEventListener('click', toggleAccordion));
}
