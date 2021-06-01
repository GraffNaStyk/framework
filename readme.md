# Graff framework

## Configuration

Application configuration app/config/app.php
```php
[
    /**
     *  @csrf is used to blocking csrf attack from users,
     *  if this variable is set to true, you need to add for every form
     *  twig variable like {{ form.csrf('Controller@action') }}
     */
    'csrf' => true,

    /**
     *  @dev here tou can set developer mode to true or false, if developer mode
     *  is set to true on page have all bugs, if not all logs send to
     *  storage/private/logs like php or sql log.
     **/
    'dev' => true,

    /**
     *  @url this is a framework url, default u can set '/' if framework exist
     *  in any sub folder need to add this path there to good working
     **/
    'url' => '/',
    
    /**
     *  @cache_view disable or enable view caching
     **/
    'cache_view' => false,
    
    /**
     *  @mail configuration using in framework to send mails.
     *  If array values are empty mail are not configured.
     **/
    'mail' => [
        'smtp' => '',
        'user' => '',
        'password' => '',
        'port' => '',
        'from' => '',
        'fromName' => ''
    ],
    
    /**
     *  @Always loaded libraries css / js from main css / js directory
     *
     **/
    'is_loaded' => [
        'css' => [
            'bootstrap', 'slim-select', 'alerts', 'box', 'buttons', 'form', 'modal', 'table', 'loader'
        ],
        
        'js' => [
            'bootstrap', 'slim-select'
        ]
    ],
    
    /**
     * @Security used for header Content-Security-Policy
     *
     **/
	'security' => [
		'enabled' => true,
		'protection' =>
			"default-src 'self'; style-src 'self' 'unsafe-inline' fonts.googleapis.com; font-src 'self' fonts.gstatic.com; img-src 'self' data:"
		
	]
];

```

Database configuration app/config/.env
```dotenv
DB_USER=user
DB_PASS=pass
DB_HOST=localhost
DB_NAME=db_example
```

## Routing
app/routes/route.php
```php
Route::namespace('App\Controllers\Http', function () {
    Route::get('/url/{param}', 'Controller@action');
    Route::post('/url', 'Controller@action');
});

Route::middleware('auth', function () { 
    Route::get('/url/{param}', 'Controller@action');
    Route::post('/url', 'Controller@action');
});

Route::prefix('/admin', function () { 
    Route::get('/url/{param}', 'Controller@action');
    Route::post('/url', 'Controller@action');
});

Route::group(
    [
        '/url',
        '/url/url2',
        '/url/url2/{param}'
    ],
    'Controller@action',
    'get'
)->middleware(['example']);

Route::redirect('/path');
Route::back();
Route::when('/url1', '/url2');
Route::crud('url', 'Controller');
```

# Console
| Typ | Opis |
| ------ | ------ |
| Crontab | time php path/to/console cron {fileName} run |
| Controller | php console controller {namespace} {controller} |
| Cron | php console cron {fileName} |
| Model | php console model {fileName} Table |
| Migrate | php console {method - up,down,make,dump} optional:{fileName table} |
| Middleware | php console middleware {fileName} |
| Rule | php console rule {fileName} |

# Model
app/model
```php
Model::insert('array')
->duplicate('ON DUPLICATE KEY UPDATE')
->exec();

Model::update('array')->where('param1', 'is', 'param2')->exec();

Model::select('array')
->?selectGroup(PDO::FETCH_GROUP)
->as('alias for table')
->distinct('?array')
->onDuplicate('?array')
->multiple()
->join('table', 'param1', 'is', 'param2', 'rigidly')
->leftJoin('table', 'param1', 'is', 'param2', 'rigidly')
->rightJoin('table', 'param1', 'is', 'param2', 'rigidly')
->where('param1', 'is', 'param2')
->orWhere('param1', 'is', 'param2')
->whereNull('param')
->whereNotNull('param')
->orWhereNull('param')
->orWhereNotNull('param')
->startBracket()
->whereIn('item', 'array')
->whereNotIn('item', 'array')
->whereBetween('item', 'array')
->endBracket()
->raw('query string')
->order('by', 'type')
->group('by')
->limit(limit)
->offset('offset')
->paginate($page)
->bind('array bind values for war query')
->get()
?->exist()
?->first();

Model::raw('Raw query');

Model::delete()->where('param1', 'is', 'param2')->exec();

Model::count('item')->where('param1', 'is', 'param2')->exec();

Model::increment('field', 'value')->where('param1', 'is', 'param2')->exec();

Model::decrement('field', 'value')->where('param1', 'is', 'param2')->exec();

Model::lastId();
```

# Javascript responses
| Typ | Opis |
| ------ | ------ |
| res.params.modal | Otwiera kolejny modal |
| res.params.html | Podmiena HTML w komponencie |
| res.params.to | Gdzie przekierować użytkownika |
| res.params.reload | Ustawione na true, przeładuje stronę |
| res.ok | Warunkuje sukces operacji |
| res.inputs | Błędy formularza |
| res.msg | Głowna wiadomość dla użytkownika |
