<?php
namespace Blog\Navigation;
 
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\Navigation\Navigation;
 
class MyNavigation extends DefaultNavigationFactory
{
    protected function getPages(ServiceLocatorInterface $serviceLocator)
    {
        if (null === $this->pages) {
            //FETCH data from table menu :
            $fetchMenu = $serviceLocator->get('menu')->fetchAll();
            
            $configuration['navigation'][$this->getName()]['News']['label'] = 'News';
            $configuration['navigation'][$this->getName()]['News']['route'] = 'home';
            
            foreach($fetchMenu as $row)
            {
                $configuration['navigation'][$this->getName()][$row['name_bl']]['label'] = $row['name_bl'];
                $configuration['navigation'][$this->getName()][$row['name_bl']]['uri'] = '#';
                $configuration['navigation'][$this->getName()][$row['name_bl']]['pages'][$row['name_cat']] = array(
                            'label' => $row['name_cat'],
                            'uri' => '/category/'.$row['id'].'/'.$row['name_cat'].'/',
                    );
            }
             
            if (!isset($configuration['navigation'])) {
                throw new Exception\InvalidArgumentException('Could not find navigation configuration key');
            }
            if (!isset($configuration['navigation'][$this->getName()])) {
                throw new Exception\InvalidArgumentException(sprintf(
                    'Failed to find a navigation container by the name "%s"',
                    $this->getName()
                ));
            }
 
            $application = $serviceLocator->get('Application');
            $routeMatch  = $application->getMvcEvent()->getRouteMatch();
            $router      = $application->getMvcEvent()->getRouter();
            $pages       = $this->getPagesFromConfig($configuration['navigation'][$this->getName()]);
 
            $this->pages = $this->injectComponents($pages, $routeMatch, $router);
            
        }
        return $this->pages;
    }
}
?>