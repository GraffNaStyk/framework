class _App {
  constructor() {
    this.events = [];
    this.wrongResponseCodes = [404, 500, 405, 403, 401];
    this.loaderExist = false;
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

    if (args.form) {
      data = new FormData(args.form);
    }

    if (args.data) {
      data = new FormData();
      Object.keys(args.data).forEach(key => data.append(key, args.data[key]));
    }

    let headers = {'Is-Fetch-Request': true};

    if (args.headers) {
      headers = Object.assign(headers, args.headers);
    }

    return await fetch(args.url, {
      method: 'POST',
      credentials: 'same-origin',
      headers: headers,
      body: data
    }).then(res => {
      if (this.wrongResponseCodes.includes(res.status)) {
        res.isError = true;
        return res;
      }

      return res;
    })
  }
}
