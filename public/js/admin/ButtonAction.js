$.on('click', '.action', (e) => {
  let data = {};

  if (e.target.dataset.json) {
    e.target.dataset.json.split(',').forEach((k, v) => {
      let tmp = k.split(':');
      data[tmp[0]] = tmp[1];
    })
  }

  data['_csrf'] = e.target.dataset.csrf;

  $.post({
    url: e.target.dataset.url,
    data: data
  }).then(res => res.json())
  .then(res => {
    if (res.csrf) {
      $.elements(`button[data-url="${e.target.dataset.url}"]`).forEach((k,v) => {
        k.dataset.csrf = res.csrf;
      });
    }

    if (res.to) {
      window.location.href = res.to;
    }

    if (e.target.dataset.refresh) {
      let component = $.el(`[data-component="${e.target.dataset.refresh}"]`);
      $.render({
        url: component.dataset.fetch,
        el: e.target.dataset.refresh
      });
    }
  })
});
