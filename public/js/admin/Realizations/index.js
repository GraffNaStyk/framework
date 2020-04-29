import * as App from '../../app.js';

const selector = document.querySelector(`[data-component="realizations"]`);

App.get('realizations/get').then(res => {
  res.forEach((v) => {
    selector.innerHTML += `<p>${v.id}</p>`;
    selector.innerHTML += `<p>${v.title}</p>`;
    selector.innerHTML += `<p>${v.page_url}</p>`;
  })
});
