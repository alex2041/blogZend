<?php
// module/Blog/src/Blog/Model/Post.php:
namespace Blog\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
 
class Post
{
    public $id;
    public $title;
    public $content;
    public $create_time;
    public $update_time;
    public $id_user;
    public $id_cat;
    protected $inputFilter;
 
    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->title = (isset($data['title'])) ? $data['title'] : null;
        $this->content  = (isset($data['content'])) ? $data['content'] : null;
        $this->create_time  = (isset($data['create_time'])) ? $data['create_time'] : null;
        $this->update_time  = (isset($data['update_time'])) ? $data['update_time'] : null;
        $this->id_user  = (isset($data['id_user'])) ? $data['id_user'] : null;
        $this->id_cat  = (isset($data['id_cat'])) ? $data['id_cat'] : null;
        $this->name_cat = (isset($data['name_cat'])) ? $data['name_cat'] : null;
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
                 'name'     => 'title',
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
                             'max'      => 28,
                         ),
                     ),
                 ),
             ));
             
             $inputFilter->add(array(
                 'name'     => 'id_cat',
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'Int'),
                 ),
             ));

             $inputFilter->add(array(
                 'name'     => 'content',
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
                             'min'      => 1
                         ),
                     ),
                 ),
             ));

             $this->inputFilter = $inputFilter;
         }

         return $this->inputFilter;
     }
}