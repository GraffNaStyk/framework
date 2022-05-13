observeForm('form[data-action]', {
  before: () => {
  },
  after: (res, e) => {
    window.location.href = res.params.to;
  }
});
