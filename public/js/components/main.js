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

setTimeout(() => {
  App.OnSubmitForms();
  App.RefreshSelects();
}, 80);

App.on('click', '.render', (e) => {
  e.preventDefault();
  e.stopPropagation()
  if(e.target.classList.contains('fa')) {
    App.render({
      url: e.target.parentElement.dataset.url,
      el: e.target.parentElement.dataset.el
    })
  } else {
    App.render({
      url: e.target.dataset.url,
      el: e.target.dataset.el
    })
  }
});

App.on('click', '[data-menu="toggle"]', (e) => {
  App.toggle(e.target.dataset.target, 'd-flex');
})

if (window.innerWidth > 991) {
  localStorage.setItem('url', window.location.pathname)
  let el = document.querySelector(`a[href='${localStorage.getItem('url')}']`);
  if (el) {
    el.classList.add('active');
    el.parentElement.classList.add('d-flex');
    if (el.parentElement.previousElementSibling) {
      el.parentElement.previousElementSibling.classList.add('open');
    }
  }
} else {
  document.querySelector('aside.left-panel').classList.remove('d-flex');
}


function debounce(func, wait, immediate){
  var timeout, args, context, timestamp, result;
  if (null == wait) wait = 100;

  function later() {
    var last = Date.now() - timestamp;

    if (last < wait && last >= 0) {
      timeout = setTimeout(later, wait - last);
    } else {
      timeout = null;
      if (!immediate) {
        result = func.apply(context, args);
        context = args = null;
      }
    }
  };

  var debounced = function(){
    context = this;
    args = arguments;
    timestamp = Date.now();
    var callNow = immediate && !timeout;
    if (!timeout) timeout = setTimeout(later, wait);
    if (callNow) {
      result = func.apply(context, args);
      context = args = null;
    }

    return result;
  };

  debounced.clear = function() {
    if (timeout) {
      clearTimeout(timeout);
      timeout = null;
    }
  };

  debounced.flush = function() {
    if (timeout) {
      result = func.apply(context, args);
      context = args = null;

      clearTimeout(timeout);
      timeout = null;
    }
  };

  return debounced;
}
