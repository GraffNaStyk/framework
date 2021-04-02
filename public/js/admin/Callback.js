
const callback = (res) => {
  if (res.ok) {
    if (res.reload) {
      if (res.to !== null && res.to !== '') {
        setTimeout(() => {
          document.location.href = $.url + res.to;
        }, 700);
      } else {
        setTimeout(() => {
          document.location.reload();
        }, 700);
      }
    }
  }
};
