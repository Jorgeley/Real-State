<?php
/**
 * Controlador de Projetos
 */
namespace Application\Controller;

use Zend\View\Model\ViewModel,
    MyClasses\Conn\Conn,
    MyClasses\Entities\AclPerfil,
    MyClasses\Controllers\PadraoController;

class UsuarioController extends PadraoController{
    /** @var Doctrine\ORM\EntityManager */
    private $em;
    
    public function __construct(){
        parent::__construct();
    }
    
    /**
     * lista os usuários
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction(){
        $perfis = $this->getEm()->getRepository('MyClasses\Entities\AclPerfil')->findAll();
        return new ViewModel(array('perfis'=>$perfis));
    }
    
    /** @return Doctrine\ORM\EntityManager */
    public function getEm(){
        if (null === $this->em){
            $this->em = Conn::getConn();
        }
        return $this->em;
    }
    
}
?>