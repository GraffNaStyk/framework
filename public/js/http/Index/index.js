import * as App from '../../app.js';

document.querySelector('#showModal').onclick = () => {
  App.render({
    url: 'Example/modal',
    modal: true
  });
};
