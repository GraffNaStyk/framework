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


export const render = async (args) => {
  fetch(url + args.url, {
    method: 'GET',
    credentials: 'same-origin',
    headers: {
      "X-Fetch-Header": "fetchApi",
    },
  }).then((res) => res.text())
  .then(result => {
    let script = '';
    if(args.src) {
      script = document.createElement('script');
      script.setAttribute('type', 'module');
      script.setAttribute('src',`public/js/${args.src}`);
    }

    if (args.el !== 'modal') {
      if(args.append)
        document.querySelector(`[data-component="${args.el}"]`).innerHTML += result;
      else {
        document.querySelector(`[data-component="${args.el}"]`).appendChild(script);
        document.querySelector(`[data-component="${args.el}"]`).innerHTML = result;
      }
    } else {
      document.querySelector(`#modal`).appendChild(script);
      modal(result);
    }
  })
};

export const view = async (name) => {
  fetch(url + `Dash/view/${name}`, {
    method: 'GET',
    credentials: 'same-origin',
    headers: {
      "X-Fetch-Header": "fetchApi",
    },
  }).then(res => res.text())
  .then(result => {
    console.log(result);
  })
};

const modal = (result) => {
  const modal = document.getElementById('modal');
  modal.classList.add('d-block');
  modal.setAttribute('style', 'background: rgba(0,0,0,0.8)');
  modal.innerHTML = result;

  document.querySelector('button[data-dismiss="modal"]').onclick = () => {
    modal.classList.remove('d-block');
    modal.innerHTML = '';
    modal.setAttribute('style', '');
  };
};
