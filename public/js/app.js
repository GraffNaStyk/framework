export const post = (args) => {
  let data;

  if(args.form)
    data = new FormData(document.querySelector(args.form));

  if(args.data) {
    data = new FormData();
    Object.keys(args.data).forEach(key => data.append(key, args.data[key]));
  }

  return fetch(url + args.url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: {
      "X-Fetch-Header": "fetchApi",
    },
    body: data
  }).then(res => res.json())
};

export const get = (args) => {
  return fetch(url + args.url, {
    method: 'GET',
    credentials: 'same-origin',
    headers: {
      "X-Fetch-Header": "fetchApi",
    },
  }).then(res => res.json())
};

export const render = (args) => {
  fetch(url + args.url, {
    method: 'GET',
    credentials: 'same-origin',
    headers: {
      "X-Fetch-Header": "fetchApi",
    },
  }).then(res => res.text())
  .then(result => {
    if (args.el) {
      document.querySelector(args.el).innerHTML = result;
    }
    if(args.modal) {
      let modal = document.getElementById('modal');
      modal.classList.add('d-block');
      modal.setAttribute('style', 'background: rgba(0,0,0,0.8)');
      modal.innerHTML = result;

      document.querySelector('button[data-dismiss="modal"]').onclick = () => {
        modal.classList.remove('d-block');
        modal.innerHTML = '';
        modal.setAttribute('style', '');
      };

    }
  })
};
