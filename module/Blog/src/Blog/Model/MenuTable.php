<?php
namespace Blog\Model;
 
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterAwareInterface;
//use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;
//use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;



 
class MenuTable extends AbstractTableGateway
    implements AdapterAwareInterface
{
 
    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        //$this->resultSetPrototype = new ResultSet();
        //$this->initialize();
    }
 
    public function fetchAll()
    {
        $sql = new Sql($this->adapter);
        $select1 = $sql->select();
        $select1->from('tbl_category')->join('tbl_block', 'tbl_category.id_block=tbl_block.id', 'name_bl', 'LEFT');
        $statement = $sql->prepareStatementForSqlObject($select1);
        $resultCat = $statement->execute();
        
        $resultSet1 = new ResultSet; // Zend\Db\ResultSet
        $resultSet1->initialize($resultCat);
        
        $resultSet1->buffer();
        
        
        //$resultSet = $this->select();
        //$resultSet = $resultSet->toArray();
 
        return $resultSet1;
    }
}