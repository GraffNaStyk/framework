const modal = (el = null, result, config = {}) => {
  if (config.before !== undefined) {
    config.before();
  }

  let modal = $.el(el);
  modal.classList.add('d-block');
  modal.classList.add('modal');
  modal.setAttribute('style', 'background: rgba(0,0,0,0.7)');
  const content = $.el('.modal-content');
  content.innerHTML = '';
  $.adjacent(content, result);

  if (config.after !== undefined) {
    config.after();
  }

  registerClose();
}

const registerClose = () => {
  $.on('click', 'button[data-dismiss="modal"]', () => {
    $.el('#modal').classList.remove('modal');
    $.el('.modal-content').innerHTML = '';
    $.el('#modal').setAttribute('style', '');
  });
};
