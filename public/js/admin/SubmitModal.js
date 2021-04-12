$.on('click', '.confirm', (e) => {
  let elem = e.target.dataset;
  const tpl = `
    <div class="modal-header">
      <h5 class="modal-title">${elem.title}</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
     <p>${elem.body}</p>
    </div>
    <div class="modal-footer">
     <a class="btn is_button submit__button" href="${elem.action}">Tak</a>
    </div>
  `;

  $.el('#modal').classList.add('d-block');
  $.el('#modal').setAttribute('style', 'background: rgba(0,0,0,0.7)');
  $.append($.el('.modal-content'), tpl);
  registerClose();
});
