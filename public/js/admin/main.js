import * as  App from '../app.js';

document.getElementsByClassName( window.innerWidth > 991 ? 'grid' : 'right-panel')[0].style.minHeight
  = window.innerHeight - document.getElementsByTagName('nav')[0].clientHeight + 'px';

const menu = (e) => {
  if(e.target.nextElementSibling.classList.contains('d-flex')) {
    e.target.nextElementSibling.classList.remove('d-flex');
    e.target.classList.remove('open');
  } else {
    e.target.nextElementSibling.classList.add('d-flex');
    e.target.classList.add('open');
  }
};

//burger for show menu on mobile > 991
App.on('click', 'nav.top__nav i.fa-bars', () => {
  if(document.querySelector('.grid aside.left-panel').classList.contains('d-flex')) {
    document.querySelector('.grid aside.left-panel').classList.remove('d-flex');
  } else {
    document.querySelector('.grid aside.left-panel').classList.add('d-flex');
  }
});

//global function for prevent a href if href is == #
const prevent = (e) => {
  //this is for i element when parent element is A
  if (e.target.parentElement.nodeName === 'A' && e.target.parentElement.href.split('/').pop() === '#') {
    e.preventDefault();
    return false;
  }

  if (e.target.href.split('/').pop() === '#')
    e.preventDefault();
};

App.on('click', 'a', prevent);
// enable all submenu functions
App.on('click', 'a.has__parent', menu);

let selector;

if(selector = document.querySelector('input[type="submit"]')) {
  selector.onclick = (e) => {
    e.target.disabled = true;
    setTimeout(() => {
      e.target.disabled = false;
    }, 1400);
  };
}

App.OnSubmitForms();

App.on('click', '.render', (e) => {
  e.preventDefault();
  App.render({
    url: e.target.dataset.url,
    el: e.target.dataset.el
  })
});

App.on('click', '.profile', () => {
  App.toggle('.user__profile', 'd-flex');
})
