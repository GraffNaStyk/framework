const FormError = (res, action) => {
  $.elements('span.err')
  .forEach(e => e.remove());

  $.elements('.input_error')
  .forEach(e => e.classList.remove('input_error'));

  $.elements('.switch__row')
  .forEach(e => e.setAttribute('style', ''));

  if (res.csrf !== undefined && res.csrf !== false) {
    $.el(`form[data-action="${action}"] input[name="_csrf"]`).value = res.csrf;
  }

  if (Array.isArray(res.inputs)) {
    res.inputs.forEach((error => {
      let selector = $.el(`form[data-action="${action}"] .switch__row input[name="${error.field}"]`);

      if (selector !== null) {
        let row = $.el(`form[data-action="${action}"] .switch__row`);
        $.adjacent(row, `
          <span class="err switch_error" data-field="${error.field}">
            ${error.msg}
          </span>`);

        $.el('.switch_error').setAttribute('style', 'top: 0');
        row.setAttribute('style', 'border: 1px solid #ce2e22 !important; border-radius: 8px !important; padding: 3px;');
      } else {
        let selector = $.el(`form[data-action="${action}"] input[name="${error.field}"]`);

        if (selector === null) {
          selector = $.el(`form[data-action="${action}"] textarea[name="${error.field}"]`);
        }

        if (selector === null) {
          selector = $.el(`form[data-action="${action}"] select[name="${error.field}"]`);
        }

        selector.classList.add('input_error');

        if (selector.parentElement.children[0].classList.contains('err') === false) {
          $.adjacent(selector.parentElement, `
          <span class="err" data-field="${error.field}">
            ${error.msg}
          </span>
       `);
        }
      }
    }))
  }

  if (res.msg) {
    message(res, action);
  }
};
