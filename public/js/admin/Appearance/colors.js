import * as App from '../../app.js';

document.querySelector('.colors__config').onsubmit = (e) => {
  e.preventDefault();
  App.post({
    url: 'appearance/store',
    form: '.colors__config'
  }).then(res => {
    App.response(res, '.right-panel');
    document.querySelector('iframe').contentWindow.location.reload();
  })
};
