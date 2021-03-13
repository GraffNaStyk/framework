const showModal = (modal, result) => {
  modal.classList.add('d-block');
  modal.setAttribute('style', 'background: rgba(0,0,0,0.7)');
  const content = $.el('.modal-content');
  $.append(content, result);
};

$.on('click', 'button[data-dismiss="modal"]', () => {
  $.el('#modal').classList.remove('d-block');
  $.clear($.el('.modal-content'));
  $.el('#modal').setAttribute('style', '');
});
