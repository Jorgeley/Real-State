<?php
class Classes_Helpers_ControladorNome extends Zend_View_Helper_Abstract{
   public function controladorNome(){
      $fc = Zend_Controller_Front::getInstance();
      return strtoupper($fc->getRequest()->getControllerName());
   }
}