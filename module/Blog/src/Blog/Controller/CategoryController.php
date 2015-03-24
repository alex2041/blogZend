<?php
// module/Blog/src/Blog/Controller/CategoryController.php:
namespace Blog\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Model\Category;
use Blog\Form\CategoryForm;
 
class CategoryController extends AbstractActionController
{
    protected $categoryTable;
    protected $blockTable;
    
    public function getCategoryTable()
    {
     if (!$this->categoryTable) {
         $sm = $this->getServiceLocator();
         $this->categoryTable = $sm->get('Blog\Model\CategoryTable');
     }
     return $this->categoryTable;
    }
    
    public function getBlockTable()
    {
     if (!$this->blockTable) {
         $sm = $this->getServiceLocator();
         $this->blockTable = $sm->get('Blog\Model\BlockTable');
     }
     return $this->blockTable;
    }
     
    public function indexAction()
    {
        $paginator = $this->getCategoryTable()->fetchAll(true);
        $paginator->setCurrentPageNumber((int)$this->params()->fromQuery('page', 1));
        $paginator->setItemCountPerPage(10);
        return new ViewModel(array(
             'paginator' => $paginator
        ));
    }
    
    public function blockArray()
    {
        $block = $this->getBlockTable()->fetchAll();
        foreach($block as $block)
        {
            $blockArray[$block->id] = $block->name_bl;
        }
        return $blockArray;
    }
 
    public function addAction()
    {
        $block = $this->blockArray();
        $form = new CategoryForm();
        $form->get('submit')->setValue('Add');
        $request = $this->getRequest();
        if ($request->isPost()) {
         $category = new Category();
         $form->setInputFilter($category->getInputFilter());
         $form->setData($request->getPost());
        
         if ($form->isValid()) {
             $category->exchangeArray($form->getData());
             $this->getCategoryTable()->saveCategory($category);
        
             // Redirect to list of categorys
             return $this->redirect()->toRoute('admCat');
         }
        }
        return array(
            'form' => $form,
            'block' => $block
        );
    }
 
    public function editAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('admCat', array(
                 'action' => 'add'
             ));
         }

         // Get the Category with the specified id.  An exception is thrown
         // if it cannot be found, in which case go to the index page.
         try {
             $category = $this->getCategoryTable()->getCategory($id);
         }
         catch (\Exception $ex) {
             return $this->redirect()->toRoute('admCat', array(
                 'action' => 'index'
             ));
         }
         
         $block = $this->blockArray();
         $form = new CategoryForm();
         $form->bind($category);
         $form->get('submit')->setAttribute('value', 'Edit');

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setInputFilter($category->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $this->getCategoryTable()->saveCategory($category);

                 // Redirect to list of categorys
                 return $this->redirect()->toRoute('admCat');
             }
         }

         return array(
             'id' => $id,
             'form' => $form,
             'block' => $block
         );
     }
 
    public function deleteAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('admCat');
         }

         $request = $this->getRequest();
         if ($request->isPost()) {
             $del = $request->getPost('del', 'No');

             if ($del == 'Yes') {
                 $id = (int) $request->getPost('id');
                 $this->getCategoryTable()->deleteCategory($id);
             }

             // Redirect to list of categorys
             return $this->redirect()->toRoute('admCat');
         }

         return array(
             'id'    => $id,
             'category' => $this->getCategoryTable()->getCategory($id)
         );
     }
}
?>