export const post = async (args) => {
  let data;

  if(args.form)
    data = new FormData(document.querySelector(args.form));

  if(args.data) {
    data = new FormData();
    Object.keys(args.data).forEach(key => data.append(key, args.data[key]));
  }

  return await fetch(url + args.url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: {
      "X-Fetch-Header": "fetchApi",
    },
    body: data
  }).then(res => res.json())
};

export const get = async (fetch_url) => {
  return await fetch(url + fetch_url, {
    method: 'GET',
    credentials: 'same-origin',
    headers: {
      "X-Fetch-Header": "fetchApi",
    },
  }).then(res => res.json());
};

export const render = (args) => {
  return fetch(url + args.url, {
    method: 'GET',
    credentials: 'same-origin',
    headers: {
      "X-Fetch-Header": "fetchApi",
    },
  }).then(async (res) => res.text())
  .then(async result => {
    if (args.el !== 'modal') {
      if(args.append)
        document.querySelector(`[data-component="${args.el}"]`).innerHTML += result;
      else {
        document.querySelector(`[data-component="${args.el}"]`).innerHTML = result;
      }
    } else {
      modal(result);
    }
  })
};

const modal = (result) => {
  const modal = document.getElementById('modal');
  modal.classList.add('d-block');
  modal.setAttribute('style', 'background: rgba(0,0,0,0.3)');
  modal.innerHTML += result;

  document.querySelector('button[data-dismiss="modal"]').onclick = () => {
    modal.classList.remove('d-block');
    modal.innerHTML = '';
    modal.setAttribute('style', '');
  };
};

export const response = (res, selector) => {
  let min = Math.ceil(500);
  let max = Math.floor(150000);
  let rand = Math.floor(Math.random() * (max - min + 1)) + min;

  document.querySelector(`${selector}`).insertAdjacentHTML('beforeBegin', `
      <div data-${rand}="" class="alert alert-${res.class}" role="alert">
          ${res.msg}
        </div>
     `);

  let alert = document.querySelector(`[data-${rand}=""]`);
  if(alert) {
    setTimeout(() => {
      alert.remove();
    }, 3000)
  }

};
