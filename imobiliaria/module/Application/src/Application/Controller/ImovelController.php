<?php
/**
 * Controlador de Imoveis
 */
namespace Application\Controller;

use Zend\View\Model\ViewModel,
    Zend\Mvc\Controller\AbstractActionController,
    MyClasses\Controllers\PadraoController;

class ImovelController extends AbstractActionController{
	
	public function indexAction(){
	}
    
	public function visualizaAction(){
        return new ViewModel(array('id'=>$this->Params('id'), 'mais'=>$this->Params('mais')));
	}

}
?>