import $ from 'jquery';

$(document).ready(function() {

  var ANIMATE_TIME = 300;

  // slide toggle

  $('.hidden').hide();

  function closeNav() {
    $('.expanded')
      .removeClass('expanded')
      .next('.hidden')
      .slideToggle(ANIMATE_TIME);
  }

  $('.trigger').on('click', function() {
    var isOpen = $(this).hasClass('expanded');

    if (isOpen) {
      closeNav();
      return;
    }

    closeNav();

    $(this)
      .addClass('expanded')
      .next('.hidden')
      .slideToggle(ANIMATE_TIME);
  });

  // mobile menu

  $('.nav-trigger').on('click', function() {
    $(".nav-overlay").fadeToggle(ANIMATE_TIME);
  });

  $(".nav-close").on('click', function() {
    $('.nav-overlay').fadeOut(ANIMATE_TIME);
  });

});
