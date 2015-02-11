<?php
class Zend_View_Helper_Acao
{
   public function acao()
   {
      $fc = Zend_Controller_Front::getInstance();
      return $fc->getRequest()->getActionName();
   }
}