<?php
/** CONTROLOADOR PADRÃO PARA TODOS OS CONTROLADORES DO MÓDULO "usuario"
 * @author jorgeley 
 * @version **/

namespace MyClasses\Controllers;
use MyClasses\Conn\Conn,
    Zend\Authentication\AuthenticationService,
    Zend\Mvc\MvcEvent,
    Zend\EventManager\EventManagerInterface,
    Zend\Mvc\Router\SimpleRouteStack,
    Zend\Http\Response,
    Zend\Mvc\Router\Http\Literal,
    Zend\Mvc\Controller\AbstractActionController;

abstract class PadraoController extends AbstractActionController{
    /** @var MyClasses\Conn\Conn */
    protected $conn;
    /** @var Doctrine\ORM\EntityManager */
    private $em;
    /** @var Zend\Authentication\AuthenticationService */
    protected $auth;
    protected $identity;
    //protected $usuario;
	
    public function __construct(){
        $this->conn = Conn::getConn();
        $this->auth = new AuthenticationService();
        $this->identity = $this->auth->getIdentity()[0];//getStorage()->read();
        //$this->usuario = $this->conn->find('MyClasses\Entities\AclUsuario',$this->identity->getId());
    }
    
    /** @return Doctrine\ORM\EntityManager */
    public function getEm(){
        if (null === $this->em){
            $this->em = Conn::getConn();
        }
        return $this->em;
    }
	
    /**
     * sobrepondo o método setEventManager do AbstractActionControler
     * para que chame a autenticacao no "dispatch"
     * @see Zend\Mvc\Controller.AbstractController::setEventManager()
     */
    public function setEventManager(EventManagerInterface $events){
        parent::setEventManager($events);
        $controller = $this;
        $events->attach('dispatch', function ($e) use ($controller) {
            if (is_callable(array($controller, 'verificaAuth'))){
                call_user_func(array($controller, 'verificaAuth'));
            }
        }, 100);
    }

	/**
	 * verifica autenticação
	 */
    public function verificaAuth(){
        $identity = $this->auth->getStorage()->read();
        if (!$this->auth->hasIdentity()){//nao existe identidade?
            $this->redirect()->toRoute('Locador/logoff');
        }else{
            $this->layout()->id = $identity[0]->getId();
            $this->layout()->nome = $identity[0]->getNome();
        }
    }
    
}