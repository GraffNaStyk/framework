$.on('click', '.render', (e) => {
  e.preventDefault();
  e.stopPropagation();
  $.loaderStart();

  $.render({
    url: e.target.dataset.url,
    el: e.target.dataset.el
  }).then(res => {
    $.loaderStop();
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
});
