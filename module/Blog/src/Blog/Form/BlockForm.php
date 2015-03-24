<?php
namespace Blog\Form;

use Zend\Form\Form;

class BlockForm extends Form
{
 public function __construct($name = null)
 {
     // we want to ignore the name passed
     parent::__construct('block');

     $this->add(array(
         'name' => 'id',
         'type' => 'Hidden',
     ));
     $this->add(array(
         'name' => 'name_bl',
         'type' => 'Text',
         'options' => array(
             'label' => 'Name',
         ),
         'attributes' => array(
             'class' => 'form-control',
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