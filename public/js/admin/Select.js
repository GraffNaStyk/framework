
const RefreshSelects = () => {
  const selectors = $.elements('[data-select="slim"]');
  if (selectors) {
    selectors.forEach((value => {
      if (value.dataset.url !== undefined && value.dataset.ssid === undefined) {
        new SlimSelect({
          select: value,
          allowDeselect: true,
          deselectLabel: '<span class="red">✖</span>',
          searchingText: 'Wyszukaj...',
          ajax: (search, callback) => {
            if (search.length < 3) {
              callback('Need 3 characters');
              return;
            }
            $.post(`${value.dataset.url}/${search}`).then(res => {
              callback(res);
            });
          }
        });
      } else if (value.dataset.ssid === undefined) {
        new SlimSelect({
          select: value,
          allowDeselect: true,
          deselectLabel: '<span class="red">✖</span>',
          searchingText: 'Wyszukaj...',
        });
      }
    }))
  }
};

RefreshSelects();
