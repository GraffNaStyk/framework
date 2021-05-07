$.on('click', '.confirm', (e) => {
  let elem = e.target.dataset;

  const tpl = `
    <form data-action="${elem.url}" data-isconfirm="">
      <div class="modal-header">
        <h5 class="modal-title">${elem.title}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <p>${elem.body}</p>
      </div>
      <input type="hidden" name="_csrf" value="${elem.csrf ?? ''}">
      <div class="modal-footer">
        <button class="btn submit__button">Submit</button>
      </div>
    </form>
  `;

  $.el('#modal').classList.add('d-block');
  $.el('#modal').setAttribute('style', 'background: rgba(0,0,0,0.7)');
  $.append($.el('.modal-content'), tpl);
  $.reloadEvents();
  registerClose();
});
