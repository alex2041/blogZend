<?php
namespace Blog\Form;

use Zend\Form\Form;

class PostForm extends Form
{
 public function __construct($name = null)
 {
     // we want to ignore the name passed
     parent::__construct('post');

     $this->add(array(
         'name' => 'id',
         'type' => 'Hidden',
     ));
     $this->add(array(
         'name' => 'title',
         'type' => 'Text',
         'options' => array(
             'label' => 'Title',
         ),
         'attributes' => array(
             'class' => 'form-control',
         ),
     ));
     $this->add(array(
         'name' => 'id_cat',
         'type' => 'Select',
         'options' => array(
             'label' => 'Category',
              'disable_inarray_validator' => true,
         ),
         'attributes' => array(
             'class' => 'form-control',
         ),
     ));
     $this->add(array(
         'name' => 'content',
         'type' => 'Textarea',
         'options' => array(
             'label' => 'Content',
             
         ),
         'attributes' => array(
             'class' => 'form-control',
             'cols' => '128',
             'rows' => '20',
         ),
     ));
     $this->add(array(
         'name' => 'submit',
         'type' => 'Submit',
         'attributes' => array(
             'value' => 'Go',
             'id' => 'submitbutton',
             'class' => 'btn btn-default #337ab7',
         ),
     ));
 }
}