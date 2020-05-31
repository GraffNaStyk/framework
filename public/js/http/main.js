import * as App from "../app.js";

document.getElementById('main-page-wrapper').style.minHeight
  = window.innerHeight
  - document.getElementsByTagName('footer')[0].clientHeight
  - document.getElementsByTagName('nav')[0].clientHeight  - 1 + 'px';

App.OnSubmitForms();

App.on('click', '.render', (e) => {
  e.preventDefault();
  App.render({
    url: e.target.dataset.url,
    el: e.target.dataset.el
  })
});

App.on('click', '[data-menu="toggle"]', (e) => {
  App.toggle(e.target.dataset.target, 'd-flex');
});
