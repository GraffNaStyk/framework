class App {
  constructor() {
    this.events = [];
    this.wrongResponseCodes = [404, 500, 405, 403, 401];
    this.loaderExist = false;
    this.loader = `<div class="loader__container"><div class="loader"></div></div>`;
  }

  el(el) {
    return document.querySelector(`${el}`);
  }

  elements(selector) {
    return Array.from(document.querySelectorAll(`${selector}`));
  }

  on(event, selector, fn) {
    this.events.push({event: event, selector: selector, fn: fn});
    this.elements(selector).forEach((item) => {
      item.addEventListener(`${event}`, fn);
    });
  }

  async post(args) {
    let data;

    let headers = {'Is-Fetch-Request': true};

    if (args.headers) {
      headers = Object.assign(headers, args.headers);
    }

    if (args.form) {
      data = new FormData(args.form);
    }

    if (headers['Content-Type'] !== undefined && headers['Content-Type'] === 'application/json') {
      data = JSON.stringify(args.data);
    } else if (args.data) {
      data = new FormData();
      Object.keys(args.data).forEach(key => data.append(key, args.data[key]));
    }

    return await fetch(args.url, {
      method: 'POST',
      credentials: 'same-origin',
      headers: headers,
      body: data
    }).then(res => {
      let contentType = res.headers.get('Content-Type');

      if (contentType.startsWith('text/html')) {
        return res.text();
      }

      if (contentType.startsWith('application/json')) {
        return res.json();
      }
    });
  }

  async get (args) {
    let headers = {'Is-Fetch-Request': true};

    if (args.headers) {
      headers = Object.assign(headers, args.headers);
    }

    return await fetch(args.url, {
      method: 'GET',
      credentials: 'same-origin',
      headers: headers
    }).then(res => {
      let contentType = res.headers.get('Content-Type');

      if (contentType.startsWith('text/html')) {
        return res.text();
      }

      if (contentType.startsWith('application/json')) {
        return res.json();
      }
    });
  }

  loaderStop = () => {
    setTimeout(() => {
      if (this.loaderExist) {
        this.el('.loader__container').remove();
        this.loaderExist = false;
      }
    }, 200)
  }

  loaderStart = () => {
    if (this.loaderExist === false) {
      this.adjacent(document.body, this.loader);
      this.el('.loader__container').style.background = 'rgba(255,255,255,0.4)';
      this.loaderExist = true;
    }
  }

  adjacent(selector, html, where = 'afterbegin') {
    selector.insertAdjacentHTML(where, `${html}`)
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

  reloadEvents = () => {
    setTimeout(() => {
      let events = this.events;
      this.events = [];

      events.forEach(item => {
        this.on(item.event, item.selector, item.fn);
      });
    }, 150);
  }
}

const $ = new App();
