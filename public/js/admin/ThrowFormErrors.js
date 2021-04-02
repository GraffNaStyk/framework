const throwFormErrors = (res, action) => {
  $.elements('span.err')
  .forEach(e => e.remove());
  
  $.elements('.input_error')
  .forEach(e => e.classList.remove('input_error'));

  if (res.csrf !== undefined && res.csrf !== false) {
    $.el(`form[data-action="${action}"] input[name="_csrf"]`).value = res.csrf;
  }

  if (Array.isArray(res.inputs)) {
    res.inputs.forEach((error => {
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
    }))
  }

  if (res.msg) {
    message(res, action);
  }
};
