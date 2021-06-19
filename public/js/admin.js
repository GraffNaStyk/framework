let className;

if (window.innerWidth > 991) {
  className = '.grid';
} else {
  className = '.right-panel';
}

if (window.innerWidth <= 990) {
  $.el('.left-panel').classList.remove('d-flex');
}

const menu = (e) => {
  if (e.target.nextElementSibling.classList.contains('d-flex')) {
    e.target.nextElementSibling.classList.remove('d-flex');
    e.target.classList.remove('open');
  } else {
    e.target.nextElementSibling.classList.add('d-flex');
    e.target.classList.add('open');
  }
};

$.on('click', '.menu__burger', (e) => {
  $.toggle($.el('.grid aside.left-panel'));
});

const prevent = (e) => {
  if (e.target.parentElement.nodeName === 'A'
    && e.target.href !== undefined
    && e.target.href.split('/').pop() === '#'
  ) {
    e.preventDefault();
  }
};

$.on('click', 'a', prevent);
$.on('click', 'a.has__parent', menu);
