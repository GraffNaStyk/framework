<!doctype html>
<html lang="pl">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
 <meta http-equiv="X-UA-Compatible" content="ie=edge">
 <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
 <link rel="stylesheet" href="<?=App\Facades\Url\Url::base();?>/public/css/font-awesome.min.css">
 <title> Error 404 </title>
 <link rel="shortcut icon" href="data:image/x-icon;" type="image/x-icon">
</head>
<body>
<style>
  *:focus {outline:none;}
  body {
    background: #C33232;
    color: #fff;
    font-family: 'Nunito', sans-serif;
    text-align: center;
    margin: 30px;
    overflow:hidden;
  }

  .fa-5, h2 {
    font-size: 12em;
    font-weight: 600;
    display:inline-block;
    margin: 0;
  }
  
  div {
    margin-top: 30px;
  }

  .button {
    font-size: 2em;
    background: white;
    border: 0;
    cursor:pointer;
    color: #333;
    padding: 10px 20px;
    text-decoration: none;
    text-underline: none;
    transition: .6s;
  }

  .button:hover {
    background: #f4f4f4;
    transition: .6s;
  }
  
  .wrapper {
    display: flex;
    flex-direction: column;
    justify-content: center;
    height: 90vh;
  }
</style>

<div class="wrapper">
 <h2>
  <i class="fa fa-chain-broken"></i>
  <span>4</span>
  <span>0</span>
  <span>4</span>
 </h2>
 <h1>Sorry, page not found</h1>
 <div>
  <a class="button" href="<?= App\Facades\Url\Url::base() ?: '/'; ?>">Back to main page</a>
 </div>
</div>
</body>
</html>
