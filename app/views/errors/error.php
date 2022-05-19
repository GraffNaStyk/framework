<?php

use App\Facades\Url\Url;

?>
<!doctype html>
<html lang="pl">
<head>
 <meta charset="UTF-8">
 <meta name="viewport"
       content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
 <meta http-equiv="X-UA-Compatible" content="ie=edge">
 <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
 <link rel="stylesheet" href="<?= Url::full(); ?>/public/css/font-awesome.min.css">
 <title> Ups :( </title>
 <meta name="robots" content="noindex,nofollow" />
 <link rel="shortcut icon" href="data:image/x-icon;" type="image/x-icon">
 <style>
   body {
     background: #252e39;
     color: rgba(160, 174, 192, 1) !important;
     font-family: 'Nunito', sans-serif;
     text-align: center;
     margin: 30px;
     overflow: hidden;
   }

   h2 {
     font-size: 3.5em;
     font-weight: 600;
     display: inline-block;
     margin: 0;
   }

   h2 span {
     letter-spacing: 10px;
   }

   .wrapper {
     display: flex;
     flex-direction: column;
     justify-content: center;
     height: 90vh;
   }

   .back {
     text-decoration: none;
     cursor: pointer;
     color: rgba(160, 174, 192, 1) !important;
   }
 </style>
</head>
<body>
<div class="wrapper">
 <h2>
  <i class="fa fa-chain-broken"></i>
  <span><?= $exception?->getCode() ?: 404 ?></span>
 </h2>
 <p style="text-transform: uppercase; font-weight: 600;"><?= $exception?->getMessage(); ?></p>
 <div>
  <a style="font-size: 22px;" class="back" href="<?= Url::full() ?: '/'; ?>">Wróć na stronę główną</a>
 </div>
</div>
</body>
</html>
