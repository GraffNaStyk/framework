export const post = async (args) => {
  let data;

  if(args.form)
    data = new FormData(document.querySelector(args.form));

  if(args.data) {
    data = new FormData();
    Object.keys(args.data).forEach(key => data.append(key, args.data[key]));
  }

  return await fetch(document.url + args.url, {
    method: 'POST',
    credentials: 'same-origin',
    headers: {
      "X-Fetch-Header": "fetchApi",
    },
    body: data
  }).then(res => res.json())
};

export const get = async (fetch_url) => {
  return await fetch(document.url + fetch_url, {
    method: 'GET',
    credentials: 'same-origin',
    headers: {
      "X-Fetch-Header": "fetchApi",
    },
  }).then(res => res.json());
};

export const render = (args) => {
  fetch(document.url + args.url, {
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
  modal.setAttribute('style', 'background: rgba(0,0,0,0.7)');
  const content = document.querySelector('.modal-content');
  content.innerHTML += result;
  on('click', 'button[data-dismiss="modal"]', () => {
    modal.classList.remove('d-block');
    content.innerHTML = '';
    modal.setAttribute('style', '');
  });
};

export const response = (res, selector) => {
  res.msg.forEach((msg => {
    let min = Math.ceil(500);
    let max = Math.floor(150000);
    let rand = Math.floor(Math.random() * (max - min + 1)) + min;

    document.querySelector(`${selector}`).insertAdjacentHTML('afterbegin', `
      <div data-${rand}="" class="alert alert-${res.ok ? 'success' : 'danger'}" role="alert">
          ${msg}
        </div>
     `);

    let alert = document.querySelector(`[data-${rand}=""]`);
    if(alert) {
      setTimeout(() => {
        alert.remove();
      }, 2000)
    }
  }))
};

export const on = (event, selector, fn) => {
  Array.from(document.querySelectorAll(`${selector}`)).forEach((item) => {
    item.addEventListener(`${event}`, eval(fn));
  });
};
