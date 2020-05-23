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
    const doc = new DOMParser().parseFromString(result, 'text/html');
    if (args.el !== 'modal') {
      if(args.append)
        document.querySelector(`[data-component="${args.el}"]`).innerHTML += doc.body.innerHTML;
      else {
        document.querySelector(`[data-component="${args.el}"]`).innerHTML = doc.body.innerHTML;
      }
    } else {
      modal(doc.body);
    }
    setTimeout(() => {
      OnSubmitForms();
    },80);
  })
};

const modal = (result) => {
  console.log(result);
  const modal = document.getElementById('modal');
  modal.classList.add('d-block');
  modal.setAttribute('style', 'background: rgba(0,0,0,0.7)');
  const content = document.querySelector('.modal-content');
  content.innerHTML += result.innerHTML;
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

export const callback = () => {
  if(document.callback !== undefined) {
    eval(document.callback)
  } else {
    setTimeout(() => {
      document.location.reload();
    },2100)
  }
}

export const OnSubmitForms = () => {
  on('submit', 'form', function (e)  {
    e.preventDefault();
    post({
      url: e.target.dataset.action,
      form: 'form'
    }).then(res => {
      let modalSelector = document.getElementById('modal');
      if(res.ok && modalSelector.classList.contains('d-block')) {
        setTimeout(() => {
          document.querySelector('button[data-dismiss="modal"]').click()
        },100);
      }
      if(res.ok === false && modalSelector.classList.contains('d-block')) {
        response(res, '.modal-body')
      } else {
        response(res, '.right-panel')
      }
      callback();
    })
  });
}
