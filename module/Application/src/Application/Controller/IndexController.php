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
	Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController{
    private $adapter;
	private $auth;
	
	public function __construct(){
		$this->adapter = new ObjectRepository();
		$this->auth = new AuthenticationService();
		//$this->auth->setStorage(new SessionStorage('usuario'));
	}

	public function indexAction(){
	    if ($this->auth->hasIdentity()){	//existe identidade?
	        $usuario = $this->auth->getIdentity()[0];
	        $this->layout()->id = $usuario->getId();
	        $this->layout()->nome = $usuario->getNome();
	    }else
	        $this->redirect()->toRoute("login");
	}
	
	public function loginAction(){
	    return new ViewModel();
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
			    $perfil = $result->getIdentity()->getPerfil();
			    $acl = new Acl();
			    $acl->addRole(new Role($perfil->getPerfil()));
			    $recursos = $perfil->getRecursos();
			    foreach ($recursos as $recurso){
			        if (! $acl->hasResource($recurso->getRecurso()->getRecurso()))
			            $acl->addResource(new Resource($recurso->getRecurso()->getRecurso()));
			        $acl->allow(
			            $perfil->getPerfil(),
			            $recurso->getRecurso()->getRecurso(),
			            $recurso->getPermissao()
			        );
			    }
				$this->auth->getStorage()->write(array($result->getIdentity(),$perfil->getPerfil(),$acl));
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