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

  let form = $.el(`form[data-action="${selector}"] .form__grid`);

  if (form === null) {
    form = $.el(`form[data-action="${selector}"]`);
  }

  lastRandom = rand;
  $.adjacent(form, `
    <div data-${rand}="" class="alert alert-${res.ok ? 'success' : 'danger'}" role="alert">
       <i class="fa fa-${res.ok ? 'check' : 'exclamation'} mr-2" style="color: ${res.ok ? '#155724' : '#721c24'};"></i>
       ${res.msg}
     </div>
   `, 'beforebegin');
}
