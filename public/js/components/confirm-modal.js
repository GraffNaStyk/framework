$.on('click', '.btn__confirm--js', (e) => {
  let params = e.target.dataset;
  let template = `
    <form data-action="${params.url}" data-confirm-modal>
      <div class="modal-header">
        <h5 class="modal-title">${params.title}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <p style="font-size: 14px; text-align: center" class="m-0">${params.body}</p>
      </div>
      <input type="hidden" name="_csrf" value="${params.csrf ?? ''}">
      <div class="modal-footer">
        <button class="btn btn-outline-success submit__button"> Potwierdź </button>
      </div>
    </form>
`;

  $.el('#modal').classList.add('d-block');
  $.el('#modal').setAttribute('style', 'background: rgba(0,0,0,0.7)');
  $.el('.modal-content').innerHTML = template;

  observeForm('form[data-confirm-modal]');
});
