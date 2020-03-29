if(window.innerWidth > 991) {
  document.getElementsByClassName('grid')[0].style.minHeight
    = window.innerHeight
    - document.getElementsByTagName('nav')[0].clientHeight + 'px';
}

const menu = (e) => {
  if(e.target.nextElementSibling.classList.contains('d-flex')) {
    e.target.nextElementSibling.classList.remove('d-flex');
  } else {
    e.target.nextElementSibling.classList.add('d-flex');
  }
};

// enable all submenu functions
Array.from(document.querySelectorAll('a.has__parent')).forEach((v) => {
  v.addEventListener('click', menu)
});

//burger for show menu on mobile > 991
document.querySelector('nav.top__nav i.fa-bars').onclick = () => {
  if(document.querySelector('.grid aside.left-panel').classList.contains('d-flex')) {
    document.querySelector('.grid aside.left-panel').classList.remove('d-flex');
  } else {
    document.querySelector('.grid aside.left-panel').classList.add('d-flex');
  }
};

//global function for prevent a href if href is == #
const prevent = (e) => {
  if (e.target.parentElement.nodeName === 'A' && e.target.parentElement.href.split('/').pop() === '#') {
    e.preventDefault();
    return false;
  }

  if (e.target.href.split('/').pop() === '#')
    e.preventDefault();
};

Array.from(document.getElementsByTagName('a')).forEach((v) => {
  v.addEventListener('click', prevent)
});

