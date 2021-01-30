<!doctype html>
<html lang="pl">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
 <meta http-equiv="X-UA-Compatible" content="ie=edge">
 <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
 <link rel="stylesheet" href="<?=app('url');?>/public/css/font-awesome.min.css">
 <title> Error 500 </title>
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

  h2 {
    font-size: 12em;
    font-weight: 600;
    display:inline-block;
    margin: 0;
  }

  div {
    margin-top: 30px;
  }

  .wrapper {
    display: flex;
    flex-direction: column;
    justify-content: center;
    height: 90vh;
    align-items: start;
  }
</style>

<div class="wrapper">
 <h1 style="text-align: center; width: 100%; font-size: 3.5em;">An error occured.</h1>
 <h1><b>[File]:</b> <?=$lastError['file'];?></h1>
 <h1><b>[Line]:</b> <?=$lastError['line'];?></h1>
 <h1>
<b>[Message]:</b> <?= str_replace(['thrown', 'Stack trace:', ':'.$lastError['line']], ['', '<br /><b>[Trace]:</b>', ''], $lastError['message']);?>
</h1>
</div>
</body>
</html>
