export function expander() {
  $('.expander__trigger').on('click', function(e) {
    // If the clicked thing has an href, don't do anything
    if (e.target.href) return;

    $(this)
      .toggleClass('active')
      .find('.hidden')
      .slideToggle();
  });
}
