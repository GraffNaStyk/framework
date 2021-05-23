$.on('click', '.confirm', (e) => {
  let elem = e.target.dataset;

  const tpl = `
    <form data-action="${elem.url+'/'+elem.id}" data-isconfirm="" data-id="${elem.id}">
      <div class="modal-header">
        <h5 class="modal-title">${elem.title}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <p style="font-size: 14px; text-align: center" class="m-0">${elem.body}</p>
      </div>
      <input type="hidden" name="_csrf" value="${elem.csrf ?? ''}">
      <div class="modal-footer">
        <button class="btn btn-outline-success submit__button"> Potwierdź </button>
      </div>
    </form>
  `;

  $.el('#modal').classList.add('d-block');
  $.el('#modal').setAttribute('style', 'background: rgba(0,0,0,0.7)');
  $.append($.el('.modal-content'), tpl);
  $.reloadEvents();
  registerClose();
});
