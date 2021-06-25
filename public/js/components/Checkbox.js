$.on('click', '.switch_slider_toggle', (e) => {
  const isSwitch = e.target.previousElementSibling;
  const hiddenInput = e.target.nextElementSibling;
  const textField = $.el('.switch__text__field-' + e.target.dataset.inputname);

  if (parseInt(isSwitch.value) === 1) {
    isSwitch.value = 0;
    hiddenInput.value = 0;
    textField.innerHTML = 'Nie';
  } else {
    isSwitch.value = 1;
    hiddenInput.value = 1;
    textField.innerHTML = 'Tak';
  }
})
