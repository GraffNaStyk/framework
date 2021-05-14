
const callback = (res) => {
  if (res.ok) {
    if (res.params.to !== undefined) {
      setTimeout(() => {
        document.location.href = $.url + res.params.to;
      }, 700);
    } else if (res.params.reload === true) {
      setTimeout(() => {
        document.location.reload();
      }, 700);
    }
  }
};
