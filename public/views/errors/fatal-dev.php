<!doctype html>
<html lang="pl">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
 <meta http-equiv="X-UA-Compatible" content="ie=edge">
 <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
 <title> Oh no ! </title>
 <link rel="shortcut icon" href="data:image/x-icon;" type="image/x-icon">
</head>
<body style="border: 1px solid #b80f0f; padding: 1rem;">
<div style="height: 600px; display: flex; justify-content: start; align-items: start; flex-direction: column">
 <pre style="color :rgba(0,0,0,0.6); font-family: 'Nunito', sans-serif; font-size: 20px;"><b>[Line]:</b> <?=$lastError['line'];?></pre>
 <pre style="color :rgba(0,0,0,0.6); font-family: 'Nunito', sans-serif; font-size: 20px;"><b>[File]:</b> <?=$lastError['file'];?></pre>
<pre style="color :rgba(0,0,0,0.6); font-family: 'Nunito', sans-serif; font-size: 20px; white-space: pre-wrap;  word-wrap: break-word; ">
<b>[Message]:</b> <?= str_replace(['thrown', 'Stack trace:', ':'.$lastError['line']], ['', '<br /><b>[Trace]:</b>', ''], $lastError['message']);?>
</pre>
 <a style="margin: 80px auto 0 auto; border: 1px solid rgba(0,0,0,0.6); padding: 1rem; text-decoration: none; color :rgba(0,0,0,0.6); font-family: 'Nunito', sans-serif;"
    href="<?=\App\Facades\Url\Url::get();?>">
    Back to main Page
 </a>
</div>
<p style="color :rgba(0,0,0,0.6); font-family: 'Nunito', sans-serif; font-size: 20px;">Created by @Graff</p>
</body>
</html>
