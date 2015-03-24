<?php
// module/Blog/src/Blog/Model/Category.php:
namespace Blog\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
 
class Category
{
    public $id;
    public $name_cat;
    public $id_block;
    protected $inputFilter;
 
    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->name_cat = (isset($data['name_cat'])) ? $data['name_cat'] : null;
        $this->id_block = (isset($data['id_block'])) ? $data['id_block'] : null;
        $this->name_bl = (isset($data['name_bl'])) ? $data['name_bl'] : null;
    }
    
    public function getArrayCopy()
     {
         return get_object_vars($this);
     }
    
    public function setInputFilter(InputFilterInterface $inputFilter)
     {
         throw new \Exception("Not used");
     }

    public function getInputFilter()
     {
         if (!$this->inputFilter) {
             $inputFilter = new InputFilter();

             $inputFilter->add(array(
                 'name'     => 'id',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'Int'),
                 ),
             ));
             
             $inputFilter->add(array(
                 'name'     => 'id_block',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'Int'),
                 ),
             ));

             $inputFilter->add(array(
                 'name'     => 'name_cat',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                 ),
                 'validators' => array(
                     array(
                         'name'    => 'StringLength',
                         'options' => array(
                             'encoding' => 'UTF-8',
                             'min'      => 1,
                             'max'      => 100,
                         ),
                     ),
                 ),
             ));
             
             $this->inputFilter = $inputFilter;
         }

         return $this->inputFilter;
     }
}