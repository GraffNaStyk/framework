let lastRandom = '';

const message = (res, selector) => {
  let min = Math.ceil(500);
  let max = Math.floor(150000);
  let rand = Math.floor(Math.random() * (max - min + 1)) + min;

  if (lastRandom !== '') {
    let alert = $.el(`[data-${lastRandom}=""]`);

    if (alert) {
      alert.remove();
    }
  }

  let form = $.el(`form[data-action="${selector}"] .modal-body`);
  let isModal = true;

  if (form === null) {
    form = $.el(`form[data-action="${selector}"]`);
    isModal = false;
  }

  lastRandom = rand;
  $.adjacent(form, `
    <div data-${rand}="" class="alert alert-${res.ok ? 'success' : 'danger'} ${isModal ? 'mt-3' : ''}" role="alert">
       ${res.msg}
     </div>
   `, isModal ? 'beforeend' : 'afterbegin');
}
