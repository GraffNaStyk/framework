const setListeners = (targets) => {

  const listeners = ['focus', 'active', 'mouseenter', 'mouseleave'];

  targets.forEach((target) => {
    listeners.forEach((listener) => {
      document.querySelector(`${target}`).addEventListener(`${listener}`, (e) => {
        e.target.previousSibling.previousSibling.style.opacity = listener === 'mouseleave' ? '1' : '0.6';
        e.target.previousSibling.previousSibling.style.color = listener === 'mouseleave' ? 'rgba(0,0,0,.6)' : '#11a4d1';
      });
    })
  })
};

setListeners([
  'input[name="name"]',
  'input[name="email"]',
  'textarea[name="text"]'
]);

import * as App from '../../app.js';

document.querySelector('#contact__form').addEventListener('submit', (e) => {

  e.preventDefault();
  App.post({
    form: '#contact__form',
    url: 'Contact/send'
  }).then(res => {
    if (res.ok) {
      document.querySelector('#contact__form').reset();
    }
    App.response(res, '.grid__contact__container');
  })
});
