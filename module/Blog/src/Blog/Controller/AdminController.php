<?php
// module/Blog/src/Blog/Controller/AdminController.php:
namespace Blog\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Model\Post;
use Blog\Model\Block;
use Blog\Model\Category;
use Blog\Form\PostForm;
use Blog\Form\BlockForm;
use Blog\Form\CategoryForm;
 
class AdminController extends AbstractActionController
{
    public function indexAction()
    {
        if (! $this->getServiceLocator()
                 ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }

        return new ViewModel();
    }
    
    protected $postTable;
    protected $blockTable;
    protected $categoryTable;
    
    public function getPostTable()
    {
     if (!$this->postTable) {
         $sm = $this->getServiceLocator();
         $this->postTable = $sm->get('Blog\Model\PostTable');
     }
     return $this->postTable;
    }
    
    public function getBlockTable()
    {
     if (!$this->blockTable) {
         $sm = $this->getServiceLocator();
         $this->blockTable = $sm->get('Blog\Model\BlockTable');
     }
     return $this->blockTable;
    }
    
    public function getCategoryTable()
    {
     if (!$this->categoryTable) {
         $sm = $this->getServiceLocator();
         $this->categoryTable = $sm->get('Blog\Model\CategoryTable');
     }
     return $this->categoryTable;
    }
     
    public function admAction()
    {
        if (! $this->getServiceLocator()
                 ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('login');
        }
        
        return new ViewModel(array(
             'posts' => $this->getPostTable()->fetchAll(),
             'blocks' => $this->getBlockTable()->fetchAll(),
             'categorys' => $this->getCategoryTable()->fetchAll()
        ));
    }
    

}
?>