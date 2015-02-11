<?php
/**
 * Controlador de Projetos
 */
namespace Application\Controller;

use Zend\View\Model\ViewModel,
    Zend\Mvc\Controller\AbstractActionController,
    MyClasses\Controllers\PadraoController;

class TarefaController extends PadraoController{
    
    public function __construct(){
        parent::__construct();
    }
    
    /**
     * lista as tarefas
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction(){
        return new ViewModel();
    }
    
}
?>