<?php
/** default controller of user module
 * @author jorgeley 
 * @version **/

namespace MyClasses\Controllers;
use MyClasses\Conn\Conn,
    MyClasses\Entities\Locador,
    Zend\Authentication\AuthenticationService,
    Zend\EventManager\EventManagerInterface,
    Zend\Mvc\Controller\AbstractActionController;

abstract class PadraoControllerLocador extends AbstractActionController{
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
     * overriding setEventManager method of AbstractActionControler
     * to do the authentication on "dispatch"
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
	 * verify authentication
	 */
    public function verificaAuth(){
        $this->identity = $this->auth->getStorage()->read();
        if (!$this->auth->hasIdentity()){//there is no id?
            $this->redirect()->toRoute('Locador/logoff');
        }else{
            $this->locador = $this->identity[0];
            $this->layout()->locador = $this->locador;
            $visitas = $this->getEm()->getRepository("MyClasses\Entities\Locador")->find($this->locador->getId())
                        ->getVisitas()
                            ->filter(function($visita){
                                        return $visita->getStatus()=="agendada";
                                    })
                            ->count();
            $this->layout()->visitas = $visitas;
        }
    }
    
}