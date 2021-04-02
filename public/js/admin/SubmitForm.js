$.on('submit', 'form',  (e) => {
  let that = e.target;
  if (that.dataset.action) {
    e.preventDefault();
    e.stopImmediatePropagation();
    $.loaderStart();

    $.post({
      url: that.dataset.action,
      form: that
    }).then(res => {
      $.loaderStop();
      const contentType = res.headers.get('content-type');

      if (contentType && contentType.indexOf('application/json') !== -1) {
        res.json().then(res => {
          if (res === null || res === '' || res.length === 0) {
            return false;
          }

          if (res.html) {
            $.html($.el(`[data-component="${that.el}"]`), res.html);
          } else {
            let modalSelector = $.el('#modal');
            if (res.ok && modalSelector.classList.contains('d-block')) {
              setTimeout(() => {
                $.el('button[data-dismiss="modal"]').click()
              }, 500);
            }
          }

          throwFormErrors(res, that.dataset.action);

          if (that.dataset.reload === undefined) {
            callback(res);
          }
        });
      } else {
        res.text().then(res => {
          if (res === null || res === '') {
            return false;
          }

          $.html($.el(`[data-component="${that.el}"]`), res);
        });
      }

      $.reloadEvents();
      RefreshSelects();
    });
  }
});
