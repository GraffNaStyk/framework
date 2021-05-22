$.on('click', '.action', (e) => {
  $.loaderStart();
  let data = $.buttons[e.target.dataset.url]['options'];
  data['_csrf'] = e.target.dataset.csrf;

  $.post({
    url: e.target.dataset.url.replace('/'+e.target.dataset.id, ''),
    data: data,
  }).then(res => res.json())
  .then(res => {
    if (res.isError) {
      message(res, e.target.dataset.url);
      return;
    }

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

const Render = (e) => {
  $.loaderStart();

  $.render({
    url: e.target.dataset.url,
    el: e.target.dataset.el
  }).then(res => {
    $.loaderStop();
    if (res.isError) {
      message(res, e.target.dataset.url);
      return;
    }

    res.text().then(result => {
      if (e.target.dataset.el !== 'modal') {
        if (e.target.dataset.append) {
          $.append($.el(`[data-component="${e.target.dataset.el}"]`), result);
        } else {
          $.html($.el(`[data-component="${e.target.dataset.el}"]`), result);
        }
      } else {
        showModal($.el('#modal'), result);
      }
    });
    $.reloadEvents();
    RefreshSelects();
  });
};
