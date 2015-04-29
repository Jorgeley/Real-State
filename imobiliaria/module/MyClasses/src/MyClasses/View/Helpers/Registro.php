<?php
class Classes_Helpers_Registro  extends Zend_View_Helper_Abstract{
   public function registro(){
      $fc = Zend_Controller_Front::getInstance();
      return $fc->registro;
   }
}