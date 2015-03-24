<?php
// module/Blog/src/Blog/Controller/IndexController.php:
namespace Blog\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
 
class IndexController extends AbstractActionController
{
    protected $postTable;
    
    public function getPostTable()
    {
     if (!$this->postTable) {
         $sm = $this->getServiceLocator();
         $this->postTable = $sm->get('Blog\Model\PostTable');
     }
     return $this->postTable;
    }
     
    public function indexAction()
    {
        $paginator = $this->getPostTable()->fetchAll(true);
        $paginator->setCurrentPageNumber((int)$this->params()->fromQuery('page', 1));
        $paginator->setItemCountPerPage(4);
        return new ViewModel(array(
             'paginator' => $paginator
        ));
    }
}
?>