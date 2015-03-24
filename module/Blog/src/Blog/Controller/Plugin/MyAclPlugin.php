<?
namespace Blog\Controller\Plugin;
 
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\Container as SessionContainer;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
      
class MyAclPlugin extends AbstractPlugin
{
    protected $sesscontainer ;
    private function getSessContainer()
    {
        if (!$this->sesscontainer) {
            $this->sesscontainer = new SessionContainer('blog_ses');
        }
        return $this->sesscontainer;
    }
    
    public function doAuthorization($e)
    {

        $acl = new Acl();
        $acl->deny(); // on by default
        //$acl->allow(); // this will allow every route by default so then you have to explicitly deny all routes that you want to protect.		
		
		# ROLES ############################################
        $acl->addRole(new Role('anonymous'));
        $acl->addRole(new Role('admin'), 'anonymous');
		# end ROLES ########################################
        
		# RESOURCES ########################################
        $acl->addResource('Application'); // Application module
        $acl->addResource('Blog'); // Blog module
		# end RESOURCES ########################################
		
		################ PERMISSIONS #######################
		// $acl->allow('role', 'resource', 'controller:action');
		
        // Application -------------------------->
        $acl->allow('anonymous', 'Application', 'Index:index');
       
        // Blog -------------------------->
		$acl->allow('anonymous', 'Blog', 'Index:index');
        $acl->allow('anonymous', 'Blog', 'Auth:login');
        $acl->allow('anonymous', 'Blog', 'Auth:authenticate');
		$acl->allow('anonymous', 'Blog', 'Post:postCat');
		$acl->allow('anonymous', 'Blog', 'Post:postId');
        $acl->allow('admin', null);  
		//$acl->deny('anonymous', 'blog', 'album:hello'); // denies access to route: /blog/hello
		//$acl->allow('anonymous', 'blog', 'album:view');
		//$acl->allow('anonymous', 'blog', 'album:edit'); // also allows: http://ololo/blog/edit/1
		//$acl->deny('anonymous', 'blog', 'blog:song');
		################ end PERMISSIONS #####################
		
		
        $controller = $e->getTarget();
        $controllerClass = get_class($controller);
        $moduleName = substr($controllerClass, 0, strpos($controllerClass, '\\'));
        $role = (! $this->getSessContainer()->storage ) ? 'anonymous' : $this->getSessContainer()->storage['role'];

		$routeMatch = $e->getRouteMatch();
		
        $actionName = $routeMatch->getParam('action', 'not-found');	// get the action name	
        $controllerName = $routeMatch->getParam('controller', 'not-found');	// get the controller name	
		$controllerName = explode('\\', $controllerName);
        $controllerName = array_pop($controllerName);
        
        /*
		print '<br>$moduleName: '.$moduleName.'<br>'; 
		print '<br>$controllerClass: '.$controllerClass.'<br>'; 
		print '$controllerName: '.$controllerName.'<br>'; 
		print '$action: '.$actionName.'<br>';
        print '$role: '.$role.'<br>';
        echo '<pre>';
        print_r($this->getSessContainer()->storage);
        echo '</pre>';
        */

		#################### Check Access ########################
        if ( ! $acl->isAllowed($role, $moduleName, $controllerName.':'.$actionName)){
            $router = $e->getRouter();
            // $url    = $router->assemble(array(), array('name' => 'Login/auth')); // assemble a login route
            $url    = $router->assemble(array(), array('name' => 'login'));
            $response = $e->getResponse();
            $response->setStatusCode(302);
            // redirect to login page or other page.
            $response->getHeaders()->addHeaderLine('Location', $url);
            $e->stopPropagation();            
        }
		
		
		
    }
}