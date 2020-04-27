<?php

return [
    /**
     *  @csrf is used to blocking csrf attack from users,
     *  if this variable is set to true, you need to add for very form
     *  twig variable like {{ csrf() }}, csrf is not checked if your
     *  request is pushed by js Api fetch method !
     */
    'csrf' => false,

    /*
     *  @dev here tou can set developer mode to true or false, if developer mode
     *  is set to true on page have all bugs
     */
    'dev' => true,

    /*
     *  @url this is a framework url, default u can set '/' if framework exist
     *  in any sub folder need to add this path there to good working
    */
    'url' => '/graff/',

    /*
     *  @mail configuration using in framework to send mails.
     *  If array values are empty mail are not configured.
    */
    'mail' => [
        'smtp' => '',
        'user' => '',
        'password' => '',
        'port' => '',
        'to' => 'kontakt@graff-design.pl'
    ]
];
