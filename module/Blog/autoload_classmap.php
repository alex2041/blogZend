<?php
// module/Blog/autoload_classmap.php:
return array(
    'SanAuth\Module'                       => __DIR__ . '/Module.php',
    'SanAuth\Controller\AuthController'    => __DIR__ . '/src/SanAuth/Controller/AuthController.php',
    'SanAuth\Controller\SuccessController' => __DIR__ . '/src/SanAuth/Controller/SuccessController.php',
    'SanAuth\Model\MyAuthStorage'          => __DIR__ . '/src/SanAuth/Model/MyAuthStorage.php',
    'SanAuth\Model\User'                   => __DIR__ . '/src/SanAuth/Model/User.php',
);
?>