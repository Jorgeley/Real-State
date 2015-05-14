<?php
/** CONTROLOADOR PADRÃO PARA TODOS OS CONTROLADORES DO MÓDULO "usuario"
 * @author jorgeley 
 * @version **/

namespace MyClasses\Controllers;
use MyClasses\Conn\Conn,
    MyClasses\Entities\Locador,
    Zend\Authentication\AuthenticationService,
    Zend\EventManager\EventManagerInterface,
    Zend\Mvc\Controller\AbstractActionController;

abstract class PadraoController extends AbstractActionController{
    /** @var Doctrine\ORM\EntityManager */
    private $em;
    /** @var Zend\Authentication\AuthenticationService */
    protected $auth;
    protected $identity;
    /**
     *
     * @var Locador
     */
    protected $locador;
	
    public function __construct(){
        $this->auth = new AuthenticationService();
    }
    
    /**
     * 
     * @return Doctrine\ORM\EntityManager
     */
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
        $this->identity = $this->auth->getStorage()->read();
        if (!$this->auth->hasIdentity()){//nao existe identidade?
            $this->redirect()->toRoute('Locador/logoff');
        }else{
            $this->locador = $this->identity[0];
            $this->layout()->id = $this->locador->getId();
            $this->layout()->nome = $this->locador->getNome();
        }
    }
    
}