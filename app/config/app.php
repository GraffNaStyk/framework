<?php

return [
    /**
     * @csrf is used to blocking csrf attack from users,
     * if this variable is set to true, you need to add for very form
     * twig variable like {{ csrf() }}, csrf is not checked if your
     * request is pushed by js Api fetch method !
     */
    'csrf' => false,

    /*
     * @dev here tou can set developer mode to true or false, if developer mode
     * is set to true on page have all bugs
     */
    'dev' => false,

    /*
     * @admin if variable admin is set to true, application automatic redirect to
     * admin panel, page is offline
     */
    'admin' => false,

    /*
     *  @url this is a framework url, default u can set '/' if framework exist
     * in any sub folder need to add this path there to good working
    */
    'url' => '/graff/',

    /*
    * @cms_url is a calling url for go to management system for page, default is a admin like
     *  www.page.pl/admin you can write here every link
    */
    'cms' => 'cms',

    /*
     *   @controller Set default controller for framework, current default controller is every item Index
     */
    'controller' => 'index',

    /*
     * @action Set default action for framework, current default action is every item Index
     */
    'action' => 'index',

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
