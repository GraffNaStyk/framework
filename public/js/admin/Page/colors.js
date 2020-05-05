import * as App from '../../app.js';

document.querySelector('.colors__config').onsubmit = (e) => {
  e.preventDefault();
  App.post({
    url: 'Page/store',
    form: '.colors__config'
  }).then(res => {
    App.response(res, '.row');
    document.querySelector('iframe').contentWindow.location.reload(true);
  })
};
