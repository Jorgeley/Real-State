<?php

/**
 * Controlador de Imoveis
 */

namespace Application\Controller;

use Zend\View\Model\ViewModel,
    Zend\Mvc\Controller\AbstractActionController,
    MyClasses\Conn\Conn;

class LocadorController extends AbstractActionController {

    public function indexAction() {
        return new ViewModel();
    }

    public function novoAction() {
        return new ViewModel();
    }

}

?>