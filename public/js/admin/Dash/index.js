import * as App from '../../app.js';

App.on('click', '.modal-btn', () => {
  App.render({
    url: 'dash/modal',
    el: 'modal'
  })
});
