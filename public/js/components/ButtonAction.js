$.on('click', '.action', (e) => {
  $.loaderStart();
  let data = $.buttons[e.target.dataset.url]['options'];
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

    $.loaderStop();

    if (e.target.dataset.refresh) {
      let component = $.el(`[data-component="${e.target.dataset.refresh}"]`);
      e.target.dataset.url = component.dataset.fetch;
      Render(e);
    } else {
      callback(res);
    }
  })
});
