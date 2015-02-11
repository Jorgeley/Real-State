<?php
class Classes_Helpers_CxSelect extends Zend_View_Helper_Abstract{
   
	public function cxSelect(){
		$fc = Zend_Controller_Front::getInstance();
		if ($fc->getRequest()->getParam('empresa_id')!=NULL) 
			return $fc->getRequest()->getParam('empresa_id');
			else return $fc->registro['os_empresa_agrupadora_id']; 
   }
}