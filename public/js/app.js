let loader = `<div class="preloader"><div class="lds-ring"><div></div><div></div><div></div><div></div></div></div>`;

export const post = async (args) => {
  let data;

  if(args.form)
    data = new FormData(args.form);

  if(args.data) {
    data = new FormData();
    Object.keys(args.data).forEach(key => data.append(key, args.data[key]));
  }

  return await fetch(document.url + prepareFetchUrl(args.url), {
    method: 'POST',
    credentials: 'same-origin',
    headers: {
      "X-Fetch-Header": "fetchApi",
    },
    body: data
  });
};

export const get = async (fetch_url) => {
  return await fetch(document.url + prepareFetchUrl(fetch_url), {
    method: 'GET',
    credentials: 'same-origin',
    headers: {
      "X-Fetch-Header": "fetchApi",
    },
  }).then(res => res.json());
};

export const render = (args) => {
  insertLoader();
  fetch(document.url + prepareFetchUrl(args.url), {
    method: 'GET',
    credentials: 'same-origin',
    headers: {
      "X-Fetch-Header": "fetchApi",
    },
  }).then(async (res) => res.text())
  .then(async result => {
    if (args.el !== 'modal') {
      if (args.append) {
        document.querySelector(`[data-component="${args.el}"]`).innerHTML += result;
      } else {
        document.querySelector(`[data-component="${args.el}"]`).innerHTML = result;
      }
    } else {
      modal(result);
    }
    setTimeout(() => {
      OnSubmitForms();
      RefreshSelects();
    },100);
    preloader();
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

export const response = (res, action) => {
  document.querySelectorAll('span.err')
  .forEach(e => e.remove());

  if (res.csrf !== undefined && res.csrf !== false) {
    let csrf = document.querySelector(`form[data-action="${action}"] input[name="_csrf"]`);
    csrf.value = res.csrf;
  }

  if (Array.isArray(res.inputs)) {
    res.inputs.forEach((error => {
      let selector = document.querySelector(`form[data-action="${action}"] input[name="${error.field}"]`);
      if (selector === null) {
        selector = document.querySelector(`form[data-action="${action}"] textarea[name="${error.field}"]`);
      }
      if (selector === null) {
        selector = document.querySelector(`form[data-action="${action}"] select[name="${error.field}"]`);
      }

      if (selector.parentElement.children[0].classList.contains('err') === false) {
        selector.parentElement.insertAdjacentHTML('afterbegin', `
          <span class="err" data-field="${error.field}">
            <img src="data:image/png;base64,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" alt="">
          </span>
       `);
        document.querySelector(`form[data-action="${action}"] span[data-field="${error.field}"]`)
        .setAttribute('tooltip', error.msg);
      }
    }))
  }

  if (res.msg) {
    throwCustomMessage(res, action)
  }
};

let lastRandom = '';

const throwCustomMessage = (res, selector) => {
  let min = Math.ceil(500);
  let max = Math.floor(150000);
  let rand = Math.floor(Math.random() * (max - min + 1)) + min;

  if (lastRandom !== '') {
    let alert = document.querySelector(`[data-${lastRandom}=""]`);
    alert.remove();
  }

  let form = document.querySelector(`form[data-action="${selector}"] .form__grid`);

  if (form === null) {
    form = document.querySelector(`form[data-action="${selector}"]`);
  }

  lastRandom = rand;
  form.insertAdjacentHTML('beforebegin', `
    <div data-${rand}="" class="alert alert-${res.ok ? 'success' : 'danger'}" role="alert">
       <i class="fa fa-${res.ok ? 'check' : 'exclamation'} mr-2" style="color: ${res.ok ? '#155724' : '#721c24'};"></i>
       ${res.msg}
     </div>
   `);
}

export const on = (event, selector, fn) => {
  Array.from(document.querySelectorAll(`${selector}`)).forEach((item) => {
    item.addEventListener(`${event}`, eval(fn));
  });
};

export const callback = (ok= false, to = null) => {
  if (document.callback !== undefined) {
    eval(document.callback)
  } else if (to !== null && to !== '' && ok) {
    setTimeout(() => {
      document.location.href = document.url + to;
    },1500)
  } else if (ok) {
    setTimeout(() => {
      document.location.reload();
    },1500)
  }
}

export const OnSubmitForms = () => {
  on('submit', 'form',  (e) => {
    if (e.target.dataset.action) {
      e.preventDefault();
      e.stopImmediatePropagation();
      insertLoader();
      post({
        url: e.target.dataset.action,
        form: e.target
      }).then(res => {
        preloader();
        const contentType = res.headers.get("content-type");
        if (contentType && contentType.indexOf("application/json") !== -1) {
          return res.json().then(res => {
            if (res === null || res === '' || res.length === 0) {
              return false;
            }

            //this is check for modal is open
            let modalSelector = document.getElementById('modal');
            if (res.ok && modalSelector.classList.contains('d-block')) {
              setTimeout(() => {
                document.querySelector('button[data-dismiss="modal"]').click()
              },500);
            }
            response(res, e.target.dataset.action)
            callback(res.ok, res.to ?? res.to);
          });
        } else {
          return res.text().then(res => {
           if (res === null || res === '') {
             return false;
           }

          document.querySelector(`[data-component="${e.target.dataset.el}"]`).innerHTML = res;
          setTimeout(() => {
            OnSubmitForms();
            RefreshSelects();
          },100);
          });
        }
      })
    }
  });
}

export const RefreshSelects = () => {
  const selectors = document.querySelectorAll('[data-select="slim"]');
  if (selectors) {
    Array.from(selectors).forEach((value => {
      if (value.dataset.url !== undefined && value.dataset.ssid === undefined) {
        new SlimSelect({
          select: value,
          allowDeselect: true,
          deselectLabel: '<span class="red">✖</span>',
          searchingText: 'Wyszukaj...',
          ajax: (search, callback) => {
            if (search.length < 3) {
              callback('Need 3 characters')
              return;
            }
            get(`${value.dataset.url}/${search}`).then(res => {
              callback(res);
            })
          }
        })
      } else if (value.dataset.ssid === undefined) {
        new SlimSelect({
          select: value,
          allowDeselect: true,
          deselectLabel: '<span class="red">✖</span>',
          searchingText: 'Wyszukaj...',
        })
      }
    }))
  }
}

export const toggle = (el, by) => {
  let selector = document.querySelector(`${el}`);
  if(selector.classList.contains(`${by}`)) {
    selector.classList.remove(`${by}`)
  } else {
    selector.classList.add(`${by}`)
  }
}

export const toggleActive = (target, el) => {
  document.querySelectorAll(`${el}`).forEach(el => el.classList.remove('active'));
  target.target.classList.add('active');
}

export const preloader = () => {
  let loader = document.querySelector('.preloader');
  setTimeout(() => {
    loader.remove();
  }, 200)
}

const insertLoader = () => {
  document.body.insertAdjacentHTML('afterbegin', loader);
  let isLoader = document.querySelector('.preloader');
  isLoader.style.opacity = .8;
};

const prepareFetchUrl = (url) => {
  if (url.charAt(0) === '/') {
    return url;
  }

  return '/'+url;
}
