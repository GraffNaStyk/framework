const observeForm = (selector = null, config = {}) => {
  $.on('submit', selector ?? 'form', (e) => {
    if (e.target.dataset.action) {
      e.preventDefault();
      $.loaderStart();

      if (config.before !== undefined) {
        config.before(e.target);
      }

      let args = {
        url: e.target.dataset.action,
        headers: config.headers,
      };

      if (config.data) {
        args['data'] = config.data;
      } else {
        args['form'] = e.target;
      }

      $.post(args).then((res) => {
        if (typeof FormError !== undefined) {
          FormError(res, e.target.dataset.action);
        }

        $.reloadEvents();

        if (typeof RefreshSelects !== undefined) {
          RefreshSelects();
        }

        $.loaderStop();

        if (! res.ok) {
          return false;
        }

        if (config.after !== undefined) {
          config.after(res, e.target);
        }

        e.target.reset();

        if (res.params.to) {
          window.location.href = res.params.to;
        }

        if (res.params.reload) {
          window.location.reload();
        }
      })
    }
  })
}
