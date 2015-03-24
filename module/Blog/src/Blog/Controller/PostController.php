<?php
// module/Blog/src/Blog/Controller/PostController.php:
namespace Blog\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Model\Post;
use Blog\Form\PostForm;
 
class PostController extends AbstractActionController
{
    protected $postTable;
    protected $categoryTable;
    
    public function getPostTable()
    {
     if (!$this->postTable) {
         $sm = $this->getServiceLocator();
         $this->postTable = $sm->get('Blog\Model\PostTable');
     }
     return $this->postTable;
    }
    
    public function getCategoryTable()
    {
     if (!$this->categoryTable) {
         $sm = $this->getServiceLocator();
         $this->categoryTable = $sm->get('Blog\Model\CategoryTable');
     }
     return $this->categoryTable;
    }
    
    public function indexAction()
    {
        $paginator = $this->getPostTable()->fetchAll(true);
        $paginator->setCurrentPageNumber((int)$this->params()->fromQuery('page', 1));
        $paginator->setItemCountPerPage(10);
        return new ViewModel(array(
             'paginator' => $paginator
        ));
    }
    
    public function catArray()
    {
        $cat = $this->getCategoryTable()->fetchAll();
        foreach($cat as $cat)
        {
            $catArray[$cat->id] = $cat->name_cat;
        }
        return $catArray;
    }
    
    public function postIdAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
            if (!$id) {
                return $this->redirect()->toRoute('postId');
            }    
            
        return new ViewModel(array(
             'post' => $this->getPostTable()->getPost($id),
        ));
    }
    
    public function postCatAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if(!$id)
        {
            return $this->redirect()->toRoute('postCat');
        }    
        $paginator = $this->getPostTable()->getPostCat($id, $paginated = true);
        $paginator->setCurrentPageNumber((int)$this->params()->fromQuery('page', 1));
        $paginator->setItemCountPerPage(4);
        return new ViewModel(array(
             'paginator' => $paginator,
        ));
    }
 
    public function addAction()
    {
        $cat = $this->catArray();
        $form = new PostForm();
        $form->get('submit')->setValue('Add');
        $request = $this->getRequest();
        if ($request->isPost()) {
         $post = new Post();
         $form->setInputFilter($post->getInputFilter());
         $form->setData($request->getPost());
        
         if ($form->isValid()) {
             $post->exchangeArray($form->getData());
             $this->getPostTable()->savePost($post);
        
             // Redirect to list of posts
             return $this->redirect()->toRoute('admPost');
         }
        }
        return array(
            'form' => $form,
            'cat' => $cat
        );
    }
 
    public function editAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('admPost', array(
                 'action' => 'add'
             ));
         }

         // Get the Post with the specified id.  An exception is thrown
         // if it cannot be found, in which case go to the index page.
         try {
             $post = $this->getPostTable()->getPost($id);
         }
         catch (\Exception $ex) {
             return $this->redirect()->toRoute('admPost', array(
                 'action' => 'index'
             ));
         }
         
         $cat = $this->catArray();   
         $form = new PostForm();
         $form->bind($post);
         $form->get('submit')->setAttribute('value', 'Edit');

         $request = $this->getRequest();
         if ($request->isPost()) {
             $form->setInputFilter($post->getInputFilter());
             $form->setData($request->getPost());

             if ($form->isValid()) {
                 $this->getPostTable()->savePost($post);

                 // Redirect to list of posts
                 return $this->redirect()->toRoute('admPost');
             }
         }

         return array(
             'id' => $id,
             'form' => $form,
             'cat' => $cat
         );
     }
 
    public function deleteAction()
     {
         $id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('admPost');
         }

         $request = $this->getRequest();
         if ($request->isPost()) {
             $del = $request->getPost('del', 'No');

             if ($del == 'Yes') {
                 $id = (int) $request->getPost('id');
                 $this->getPostTable()->deletePost($id);
             }

             // Redirect to list of posts
             return $this->redirect()->toRoute('admPost');
         }

         return array(
             'id'    => $id,
             'post' => $this->getPostTable()->getPost($id)
         );
     }
}
?>