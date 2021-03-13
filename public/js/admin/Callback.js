
const callback = (ok = false, to = null) => {
  if (to !== null && to !== '' && ok) {
    setTimeout(() => {
      document.location.href = $.url + to;
    }, 700);
  } else if (ok) {
    setTimeout(() => {
      document.location.reload();
    }, 700);
  }
};
