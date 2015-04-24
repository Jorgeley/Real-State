<?php
/**
 * Controlador Login
 */
namespace Application\Controller;

use MyClasses\Conn\Conn,
	DoctrineModule\Authentication\Adapter\ObjectRepository,
	Zend\Authentication\AuthenticationService,
	Zend\Session\Storage\SessionStorage as SessionStorage,
	Zend\Permissions\Acl\Acl,
	Zend\Permissions\Acl\Role\GenericRole as Role,
	Zend\Permissions\Acl\Resource\GenericResource as Resource,
	Zend\Mvc\Controller\AbstractActionController,
	Zend\View\Model\ViewModel,
    Zend\Soap\Server as Soap,
    Zend\Soap\AutoDiscover;

class IndexController extends AbstractActionController{
    private $adapter;
	private $auth;
	
	public function __construct(){
		$this->adapter = new ObjectRepository();
		$this->auth = new AuthenticationService();
		//$this->auth->setStorage(new SessionStorage('usuario'));
	}

	public function indexAction(){
	    /**if (!$this->auth->hasIdentity())	//ñ existe identidade?
	        $this->redirect()->toRoute("login");*/
	}
	
	public function loginAction(){
	    if (null===$this->Params('wsdl'))
	       return new ViewModel();
	    elseif ($this->Params('wsdl')=='login')
	        $this->wsdl();	        
	    elseif ($this->Params('wsdl')=='logado')
	        $this->soap();
	}
	
	/**
	 * autentica o usuário
	 */
	public function autenticaAction(){
		if ($this->getRequest()->isPost()) {
            $this->adapter->setOptions(array(
					'object_manager' => Conn::getConn(),
					'identity_class' => 'MyClasses\Entities\AclUsuario',
					'identity_property' => 'login',
					'credential_property' => 'senha'
				)
			);
			$this->adapter->setIdentityValue($this->getRequest()->getPost('login'));
			$this->adapter->setCredentialValue(sha1($this->getRequest()->getPost('senha')));
			$result = $this->auth->authenticate($this->adapter);
			if ($result->isValid()){
			    $equipes = $result->getIdentity()->getEquipes();
			    $acl = new Acl();
			    $acl->addRole(new Role($equipes[0]->getPerfil()));
			    $recursos = $equipes[0]->getRecursos();
			    foreach ($recursos as $recurso){
			        if (! $acl->hasResource($recurso->getRecurso())){
			            /* echo "add recurso: ".
			            $perfil->getPerfil().", ".
			            $recurso->getRecurso()->getRecurso().", ".
			            $recurso->getPermissao(); */ 
			            $acl->addResource(new Resource($recurso->getRecurso()));
			        $acl->allow(
			            $equipes[0]->getPerfil(),
			            $recurso->getRecurso()/* ,
			            $recurso->getPermissao() */
			        );
			        }
			    }
				$this->auth->getStorage()->write(array($result->getIdentity(),$equipes[0]->getPerfil(),$acl));
				$this->layout()->id = $result->getIdentity()->getId();
				$this->layout()->nome = $result->getIdentity()->getNome();
				return new ViewModel(array('nome' => $result->getIdentity()->getNome()));
			}else
				return new ViewModel(array('erro' => array_pop($result->getMessages())));
		}
	}
	
	/**
	 * faz o logoff
	 */
	public function logoffAction(){
	    $this->auth->clearIdentity();
	    $this->redirect()->toRoute('inicio');
	}

}
