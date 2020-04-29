<!doctype html>
<html lang="pl">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
 <meta http-equiv="X-UA-Compatible" content="ie=edge">
 <link href="https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@300&display=swap" rel="stylesheet">
 <title> Error 404 </title>
 <link rel="shortcut icon" href="data:image/x-icon;" type="image/x-icon">
</head>
<body>
<div style="height: 600px; display: flex; justify-content: center; align-items: center; flex-direction: column">
 <h2 style="font-size: 3rem; color: rgba(0,0,0,0.6); font-family: 'Open Sans Condensed', sans-serif;">
  Something went wrong....
 </h2>
 <a style="border: 1px solid rgba(0,0,0,0.6); padding: 1rem; text-decoration: none; color :rgba(0,0,0,0.6);"
    href="<?=\App\Facades\Url\Url::base();?>">
    Back to main Page
 </a>
</div>
</body>
</html>
