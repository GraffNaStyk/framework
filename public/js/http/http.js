$.on('keyup', '.test__input', $.debounce((e) => {
  console.log(e);
  if (e.target.value.length > 2) {
    $.post({
      url: e.target.dataset.action,
      data: {
        query: e.target.value
      }
    }).then(res => res.json())
    .then((result) => {
      console.log(result);
    })
  }
}, 300));

console.log('cokolwiek? xD');
