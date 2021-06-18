class App {

  constructor() {
    this.events = [];
    this.loader = `<div class="loader"><div class="lds-ring"><div></div><div></div><div></div><div></div></div><div class="preloader"></div></div>`;
    this.setDocumentUrl();
    this.bindActions();
    this.bindConfirms();
    this.loaderExist = false;
    this.msgCodes = {
      401: 'Nieautoryzowany dostęp',
      403: 'Błąd autoryzacji żądania',
      404: 'Podana strona nie istnieje',
      405: 'Brak dostępu do zasobu',
      500: 'Wystąpił nieoczekiwany problem, prosimy spróbować za chwile',
    };
  }

  setDocumentUrl() {
    this.url = this.el('meta[name="url"]').content;
    this.el('meta[name="url"]').remove();
  }

  el(el) {
    return document.querySelector(`${el}`);
  }

  elements(selector) {
    return Array.from(document.querySelectorAll(`${selector}`));
  }

  html(selector, html) {
    selector.innerHTML = html;
  }

  append(selector, html) {
    selector.innerHTML += html;
  }

  clear(selector) {
    selector.innerHTML = '';
  }

  adjacent(selector, html, where = 'afterbegin') {
    selector.insertAdjacentHTML(where, `${html}`)
  }

  on(event, selector, fn) {
    this.events.push({event: event, selector: selector, fn: fn});
    this.elements(selector).forEach((item) => {
      item.addEventListener(`${event}`, fn);
    });
  }

  async post(args) {
    let data;

    if (args.form) {
      data = new FormData(args.form);
    }

    if (args.data) {
      data = new FormData();
      Object.keys(args.data).forEach(key => data.append(key, args.data[key]));
    }

    if (args.isconfirm !== undefined) {
      data = new FormData();
      let options = this.confirms[args.url].options;
      Object.keys(this.confirms[args.url]['options']).forEach(key => data.append(key, options[key]));
      data.append('_csrf', this.el(`form[data-action="${args.url}"] input[name="_csrf"]`).value);
      args.url = args.url.replace('/' + args.id, '');
    }

    return await fetch(this.url + this.prepareFetchUrl(args.url), {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        "Is-Fetch-Request": "true",
      },
      body: data
    }).then(res => {
      if ([404, 500, 405, 403, 401].includes(res.status)) {
        res.msg = this.msgCodes[res.status];
        res.isError = true;
        return res;
      }

      return res;
    })
  }

  async render(args) {
    return await fetch(this.url + this.prepareFetchUrl(args.url), {
      method: 'GET',
      credentials: 'same-origin',
      headers: {
        "Is-Fetch-Request": "true",
      },
    }).then(res => {
      if ([404, 500, 405, 403].includes(res.status)) {
        res.msg = this.msgCodes[res.status];
        res.isError = true;
        return res;
      }

      if (res.status === 401) {
        window.location.reload();
      }

      return res;
    })
  }

  debounce(func, wait, immediate) {
    let timeout, args, context, timestamp, result;
    if (null == wait) wait = 100;

    function later() {
      let last = Date.now() - timestamp;

      if (last < wait && last >= 0) {
        timeout = setTimeout(later, wait - last);
      } else {
        timeout = null;
        if (!immediate) {
          result = func.apply(context, args);
          context = args = null;
        }
      }
    };

    let debounced = function () {
      context = this;
      args = arguments;
      timestamp = Date.now();
      let callNow = immediate && !timeout;
      if (!timeout) timeout = setTimeout(later, wait);

      if (callNow) {
        result = func.apply(context, args);
        context = args = null;
      }

      return result;
    };

    debounced.clear = function () {
      if (timeout) {
        clearTimeout(timeout);
        timeout = null;
      }
    };

    debounced.flush = function () {
      if (timeout) {
        result = func.apply(context, args);
        context = args = null;

        clearTimeout(timeout);
        timeout = null;
      }
    };

    return debounced;
  }

  prepareFetchUrl(url) {
    if (url.charAt(0) === '/') {
      return url;
    }

    return '/' + url;
  }

  reloadEvents = () => {
    setTimeout(() => {
      let events = this.events;
      this.events = [];

      events.forEach(item => {
        this.on(item.event, item.selector, item.fn);
      });
    }, 150);
  }

  loaderStop = () => {
    setTimeout(() => {
      this.el('.loader').remove();
      this.loaderExist = false;
    }, 200)
  }

  loaderStart = () => {
    if (this.loaderExist === false) {
      this.adjacent(document.body, this.loader);
      this.el('.preloader').style.opacity = .4;
      this.loaderExist = true;
    }
  }

  toggle(selector, by = 'd-flex') {
    selector.classList.toggle(by);
  }

  bindActions() {
    this.buttons = [];

    this.elements('button.action').forEach(item => {
      let rand = this.rand();
      item.setAttribute('data-id', rand);
      this.buttons[item.dataset.url + '/' + rand] = {'options': JSON.parse(item.dataset.options)};
      item.removeAttribute('data-options');
    })
  }

  bindConfirms() {
    this.confirms = [];

    this.elements('.confirm').forEach(item => {
      let rand = this.rand();
      item.setAttribute('data-id', rand);
      this.confirms[item.dataset.url + '/' + rand] = {'options': JSON.parse(item.dataset.options)};
      item.removeAttribute('data-options');
    })
  }

  rand() {
    let min = Math.ceil(500);
    let max = Math.floor(150000);
    return Math.floor(Math.random() * (max - min + 1)) + min;
  }
}

const $ = new App();
