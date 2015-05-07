<?php
/**
 * Controlador de Configurações
 */
namespace Application\Controller;

use Zend\View\Model\ViewModel,
    Zend\Mvc\Controller\AbstractActionController,
    MyClasses\Controllers\PadraoController;

class ConfController extends PadraoController{
    
    public function __construct(){
        parent::__construct();
    }
    
    /**
     * lista os usuários
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction(){
        return new ViewModel();
    }
    
}
?>