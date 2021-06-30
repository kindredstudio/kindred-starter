let loadBtn;
let postGrid;

window.addEventListener('DOMContentLoaded', () => {
  loadBtn = document.querySelector(selectors.loadBtn);
  postGrid = document.querySelector(selectors.postGrid);
});

let selectors = {
  loadBtn: '.btn-load-more',
  postGrid: '.archive-project-grid',
  posts: '.preview-post',
};

let btnText = {
  default: 'View More',
  loading: 'Loading...',
};

async function fetchPosts(e) {
  e.preventDefault();
  loadBtn.innerText = btnText.loading;

  try {
    let res = await fetch(loadBtn.href, { mode: 'same-origin' });
    let html = await res.text();
    let parser = new DOMParser();
    let doc = parser.parseFromString(html, 'text/html');

    let nextPosts = doc.querySelectorAll(selectors.posts);
    let nextLoadBtn = doc.querySelector(selectors.loadBtn);

    nextPosts.forEach((post) => postGrid.append(post));

    // If there's another button, update the href and reset the text. If not, hide the container
    if (nextLoadBtn) {
      loadBtn.innerText = btnText.default;
      loadBtn.setAttribute('href', nextLoadBtn.href);
    } else {
      loadBtn.parentNode.style.display = 'none';
    }
  } catch (err) {
    console.log(`Error: ${err}`);
  }
}

export let loadMore = () => {
  if (loadBtn) {
    loadBtn.addEventListener('click', fetchPosts);
  }
};
