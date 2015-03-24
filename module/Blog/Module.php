<?php
// module/Blog/Module.php
namespace Blog;

use Blog\Model\Post;
use Blog\Model\Block;
use Blog\Model\Category;
use Blog\Model\PostTable;
use Blog\Model\BlockTable;
use Blog\Model\CategoryTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

use Zend\ModuleManager\ModuleManager;
// added for Acl  ###################################
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
// end: added for Acl   ###################################

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
 
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    	// added for Acl   ###################################
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach('route', array($this, 'loadConfiguration'), 2);
        //you can attach other function need here...
    }
	
    public function loadConfiguration(MvcEvent $e)
    {
    $application   = $e->getApplication();
	$sm            = $application->getServiceManager();
	$sharedManager = $application->getEventManager()->getSharedManager();
	
    $router = $sm->get('router');
	$request = $sm->get('request');
	
	$matchedRoute = $router->match($request);
	if (null !== $matchedRoute) { 
           $sharedManager->attach('Zend\Mvc\Controller\AbstractActionController','dispatch', 
                function($e) use ($sm) {
		   $sm->get('ControllerPluginManager')->get('MyAclPlugin')
                      ->doAuthorization($e); //pass to the plugin...    
	       },2
           );
        }
    }
	
	
	// end: added for Acl   ###################################
    public function init(ModuleManager $moduleManager)
    {
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        $sharedEvents->attach(__NAMESPACE__, 'dispatch', function($e) {
            // This event will only be fired when an ActionController under the MyModule namespace is dispatched.
            $controller = $e->getTarget();
            //$controller->layout('layout/zfcommons'); // points to module/Album/view/layout/album.phtml
        }, 100);
    }
    
    public function getServiceConfig()
    {
     return array(
         'initializers' => array(
             function ($instance, $sm) {
                 if ($instance instanceof \Zend\Db\Adapter\AdapterAwareInterface) {
                     $instance->setDbAdapter($sm->get('Zend\Db\Adapter\Adapter'));
                 }
             }
         ),
         'invokables' => array(
              'menu' => 'Blog\Model\MenuTable'
         ),
         
         'factories' => array(
             'myMenus' => 'Blog\Navigation\MyNavigationFactory',
             'Blog\Model\PostTable' =>  function($sm) {
                 $tableGateway = $sm->get('PostTableGateway');
                 $table = new PostTable($tableGateway);
                 return $table;
             },
             'PostTableGateway' => function ($sm) {
                 $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                 $resultSetPrototype = new ResultSet();
                 $resultSetPrototype->setArrayObjectPrototype(new Post());
                 return new TableGateway('tbl_post', $dbAdapter, null, $resultSetPrototype);
             },
             'Blog\Model\BlockTable' =>  function($sm) {
                 $tableGateway = $sm->get('BlockTableGateway');
                 $table = new BlockTable($tableGateway);
                 return $table;
             },
             'BlockTableGateway' => function ($sm) {
                 $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                 $resultSetPrototype = new ResultSet();
                 $resultSetPrototype->setArrayObjectPrototype(new Block());
                 return new TableGateway('tbl_block', $dbAdapter, null, $resultSetPrototype);
             },
             'Blog\Model\CategoryTable' =>  function($sm) {
                 $tableGateway = $sm->get('CategoryTableGateway');
                 $table = new CategoryTable($tableGateway);
                 return $table;
             },
             'CategoryTableGateway' => function ($sm) {
                 $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                 $resultSetPrototype = new ResultSet();
                 $resultSetPrototype->setArrayObjectPrototype(new Category());
                 return new TableGateway('tbl_category', $dbAdapter, null, $resultSetPrototype);
             },
             'Blog\Model\MyAuthStorage' => function ($sm) {
                return new \Blog\Model\MyAuthStorage('blog_ses');
             },
             'AuthService' => function ($sm) {
                $dbAdapter      = $sm->get('Zend\Db\Adapter\Adapter');
                        $dbTableAuthAdapter  = new DbTableAuthAdapter($dbAdapter, 'tbl_user','name','pass', 'MD5(?)');
    
                $authService = new AuthenticationService();
                $authService->setAdapter($dbTableAuthAdapter);
                $authService->setStorage($sm->get('Blog\Model\MyAuthStorage'));
    
                return $authService;
             },
             
         ),
     );
    }
}