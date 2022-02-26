const modal = (el = null, result, config = {}) => {
  if (config.before !== undefined) {
    config.before();
  }

  let modal = $.el(el);
  modal.classList.add('d-block');
  modal.setAttribute('style', 'background: rgba(0,0,0,0.7)');
  const content = $.el('.modal-content');
  content.innerHTML = '';
  $.append(content, result);

  if (config.after !== undefined) {
    config.after();
  }

  registerClose();
}

const registerClose = () => {
  $.on('click', 'button[data-dismiss="modal"]', () => {
    $.el('#modal').classList.remove('d-block');
    $.clear($.el('.modal-content'));
    $.el('#modal').setAttribute('style', '');
  });
};
