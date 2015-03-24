<?php

namespace Blog\Model;
 
use Zend\Db\ResultSet\ResultSet; 
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class BlockTable
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
             $select->from('tbl_block'); 
             $resultSetPrototype = new ResultSet();
             $resultSetPrototype->setArrayObjectPrototype(new Block());
             // create a new pagination adapter object
             $paginatorAdapter = new DbSelect(
                 // our configured select object
                 $select,//order('id DESC'),
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
    
    public function getBlock($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        
        return $row;
        
    }
 
    public function saveBlock(Block $block)
    {
        $data = array(
            'name_bl' => $block->name_bl,
        );
 
        $id = (int)$block->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getBlock($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }
 
    public function deleteBlock($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}