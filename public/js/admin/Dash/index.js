import * as App from '../../app.js';
new SlimSelect({
  select: '[data-select="slim"]',
  searchingText: 'Searching...',
  ajax: (search, callback) => {
    App.get('dash/users').then(res => {
      let data = []
      for (let i = 0; i < res.length; i++) {
        data.push({text: res[i].name, value: res[i].id})
      }
      callback(data);
    })
  }
})
