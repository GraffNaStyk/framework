import * as App from '../../app.js';
new SlimSelect({
  select: '[data-select="slim"]',
  searchingText: 'Searching...',
  ajax: (search, callback) => {
    App.get('dash/users').then(res => {
      callback(res);
    })
  }
})
