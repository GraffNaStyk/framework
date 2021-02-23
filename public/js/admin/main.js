import * as  App from '../app.js';
let className;

if (window.innerWidth > 991) {
  className = 'grid';
} else {
  className = 'right-panel';
}

let element = document.getElementsByClassName(className)[0];

if (element !== undefined) {
  element.style.minHeight = window.innerHeight - document.getElementsByTagName('nav')[0].clientHeight + 'px';
}

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

//global function for prevent a href if href is === #
const prevent = (e) => {
  if (e.target.parentElement.nodeName === 'A'
    && e.target.href !== undefined
    && e.target.href.split('/').pop() === '#'
  ) {
    e.preventDefault();
  }
};

App.on('click', 'a', prevent);
// enable all submenu functions
App.on('click', 'a.has__parent', menu);
