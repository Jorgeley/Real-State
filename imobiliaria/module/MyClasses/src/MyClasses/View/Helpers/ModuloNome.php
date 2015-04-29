<?php
class Classes_Helpers_ModuloNome extends Zend_View_Helper_Abstract{
   public function moduloNome(){
      $fc = Zend_Controller_Front::getInstance();
      return $fc->getRequest()->getModuleName();
   }
}