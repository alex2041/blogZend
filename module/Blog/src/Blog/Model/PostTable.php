<?php

namespace Blog\Model;

use Zend\Db\ResultSet\ResultSet; 
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class PostTable
{
    protected $tableGateway;
 
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
 
    public function fetchAll($paginated = false)
    {
        if ($paginated) {
             $select = new Select();
             $select->from('tbl_post')
                    ->join('tbl_category', 'tbl_post.id_cat=tbl_category.id', 'name_cat', 'LEFT')
                    ->join('tbl_block', 'tbl_category.id_block=tbl_block.id', 'name_bl', 'LEFT');  
             $resultSetPrototype = new ResultSet();
             $resultSetPrototype->setArrayObjectPrototype(new Post());
             // create a new pagination adapter object
             $paginatorAdapter = new DbSelect(
                 // our configured select object
                 $select->order('id DESC'),
                 // the adapter to run it against
                 $this->tableGateway->getAdapter(),
                 // the result set to hydrate
                 $resultSetPrototype
             );
             $paginator = new Paginator($paginatorAdapter);
             return $paginator;
         }
         
        $resultSet = $this->tableGateway->select();
        $resultSet->buffer();
        return $resultSet;
    }
 

    
    public function getPost($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        
        return $row;
        
    }
    
    public function getPostCat($id, $paginated = false)
    {
        //$id  = (int) $id;
//        //$resultSet = $this->tableGateway->select(array('id_cat' => $id), );
//        $resultSet = $this->tableGateway->select(function (Select $select) {
//            $select->where(array('id_cat' => $id));
//            $select->order('id DESC');    
//        });
//        if (!$resultSet) {
//            throw new \Exception("Could not find resultSet $id");
//        }
//        $resultSet->buffer();
//        return $resultSet;
        if ($paginated) {
             // create a new Select object for the table post
             $select = new Select('tbl_post');
             // create a new result set based on the Post entity
             $resultSetPrototype = new ResultSet();
             $resultSetPrototype->setArrayObjectPrototype(new Post());
             // create a new pagination adapter object
             $paginatorAdapter = new DbSelect(
                 // our configured select object
                 $select->where(array('id_cat' => $id))->order('id DESC'),
                 // the adapter to run it against
                 $this->tableGateway->getAdapter(),
                 // the result set to hydrate
                 $resultSetPrototype
             );
             $paginator = new Paginator($paginatorAdapter);
             return $paginator;
         }
         
        $resultSet = $this->tableGateway->select(array('id_cat' => $id));
        $resultSet->buffer();
        return $resultSet;
    
    }
 
    public function savePost(Post $post)
    {
        $data = array(
            'title'  => $post->title,
            'content' => $post->content,
            'id_cat' =>$post->id_cat,
        );
 
        $id = (int)$post->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getPost($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }
 
    public function deletePost($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}