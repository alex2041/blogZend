<?php
// module/Blog/src/Blog/Controller/BlockController.php:
namespace Blog\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Model\Block;
use Blog\Form\BlockForm;
 
class BlockController extends AbstractActionController
{
    protected $blockTable;
    
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
        $paginator = $this->getBlockTable()->fetchAll(true);
        $paginator->setCurrentPageNumber((int)$this->params()->fromQuery('page', 1));
        $paginator->setItemCountPerPage(4);
        return new ViewModel(array(
             'paginator' => $paginator
        ));
    }
 
    public function addAction()
    {
        $form = new BlockForm();
        $form->get('submit')->setValue('Add');
        $request = $this->getRequest();
        if ($request->isPost()) {
         $block = new Block();
         $form->setInputFilter($block->getInputFilter());
         $form->setData($request->getPost());
        
         if ($form->isValid()) {
             $block->exchangeArray($form->getData());
             $this->getBlockTable()->saveBlock($block);
        
             // Redirect to list of blocks
             return $this->redirect()->toRoute('admBl');
         }
        }
        return array('form' => $form);
    }
 
    public function editAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('admBl', array(
                 'action' => 'add'
             ));
         }

         // Get the Block with the specified id.  An exception is thrown
         // if it cannot be found, in which case go to the index page.
         try {
             $block = $this->getBlockTable()->getBlock($id);
         }
         catch (\Exception $ex) {
             return $this->redirect()->toRoute('admBl', array(
                 'action' => 'index'
             ));
         }

         $form = new BlockForm();
         $form->bind($block);
         $form->get('submit')->setAttribute('value', 'Edit');

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setInputFilter($block->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $this->getBlockTable()->saveBlock($block);

                 // Redirect to list of blocks
                 return $this->redirect()->toRoute('admBl');
             }
         }

         return array(
             'id' => $id,
             'form' => $form,
         );
     }
 
    public function deleteAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('admBl');
         }

         $request = $this->getRequest();
         if ($request->isPost()) {
             $del = $request->getPost('del', 'No');

             if ($del == 'Yes') {
                 $id = (int) $request->getPost('id');
                 $this->getBlockTable()->deleteBlock($id);
             }

             // Redirect to list of blocks
             return $this->redirect()->toRoute('admBl');
         }

         return array(
             'id'    => $id,
             'block' => $this->getBlockTable()->getBlock($id)
         );
     }
}
?>