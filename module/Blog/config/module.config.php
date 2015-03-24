<?php
// module/Blog/config/module.config.php:
return array(
    'controllers' => array(
        'invokables' => array(
            'Blog\Controller\Index' => 'Blog\Controller\IndexController',
            'Blog\Controller\Post' => 'Blog\Controller\PostController',
            'Blog\Controller\Category' => 'Blog\Controller\CategoryController',
            'Blog\Controller\Block' => 'Blog\Controller\BlockController',
            'Blog\Controller\Admin' => 'Blog\Controller\AdminController',
            
            'Blog\Controller\Auth' => 'Blog\Controller\AuthController',
            'Blog\Controller\Success' => 'Blog\Controller\SuccessController'
        ),
    ),
    
    'controller_plugins' => array(
	    'invokables' => array(
	       'MyAclPlugin' => 'Blog\Controller\Plugin\MyAclPlugin',
	     )
	 ),
 
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'index' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/index',
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'postId' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/post/[:id/][:title][/]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                        //'title' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Post',
                        'action'     => 'postId',
                    ),
                ),
            ),
            'postCat' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/category[/:id][/:name][/]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                        //'name' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Post',
                        'action'     => 'postCat',
                    ),
                ),
            ),
            'adm' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/adm[/]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                        //'name' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Admin',
                        'action'     => 'adm',
                    ),
                ),
            ),
            'admPost' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/adm/post[/:action][/:id][/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Post',
                        'action'     => 'index',
                    ),
                ),
            ),
            'admCat' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/adm/cat[/:action][/:id][/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Category',
                        'action'     => 'index',
                    ),
                ),
            ),
            'admBl' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/adm/bl[/:action][/:id][/]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Blog\Controller\Block',
                        'action'     => 'index',
                    ),
                ),
            ),
            'login' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/auth',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Blog\Controller',
                        'controller'    => 'Auth',
                        'action'        => 'login',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'process' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:action]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
            'success' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/success',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Blog\Controller',
                        'controller'    => 'Success',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:action]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
 
    'view_manager' => array(
        'template_path_stack' => array(
            'post' => __DIR__ . '/../view',
        ),
    ),
);
?>