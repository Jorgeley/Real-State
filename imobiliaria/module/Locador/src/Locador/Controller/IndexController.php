<?php
/**
 * Locator controller
 */
namespace Locador\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    DoctrineModule\Authentication\Adapter\ObjectRepository,
    Zend\Authentication\AuthenticationService,
    Zend\Session\Storage\SessionStorage as SessionStorage,
    Zend\Permissions\Acl\Acl,
    Zend\Permissions\Acl\Role\GenericRole as Role,
    Zend\Permissions\Acl\Resource\GenericResource as Resource,
    MyClasses\Conn\Conn,
    Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController{
    private $adapter;
    private $auth;

    public function __construct(){
            $this->adapter = new ObjectRepository();
            $this->auth = new AuthenticationService();
            //$this->auth->setStorage(new SessionStorage('usuario'));
    }
    
    public function indexAction() {
        return new ViewModel();
    }

    /**
     * authenticate user
     */
    public function loginAction(){
        if ($this->getRequest()->isPost()) {
            $this->adapter->setOptions(array(
                            'object_manager' => Conn::getConn(),
                            'identity_class' => 'MyClasses\Entities\Locador',
                            'identity_property' => 'login',
                            'credential_property' => 'senha'
                    )
            );
            $this->adapter->setIdentityValue($this->getRequest()->getPost('login'));
            $this->adapter->setCredentialValue(sha1($this->getRequest()->getPost('senha')));
            $resultado = $this->auth->authenticate($this->adapter);
            if ($resultado->isValid()){
                if ($resultado->getIdentity()->getStatus()=="ativo"){
                    $acl = new Acl();
                    $this->auth->getStorage()->write(array( $resultado->getIdentity(),
                                                            $resultado->getIdentity()->getNome(),
                                                            $acl));
                    $this->layout()->id = $resultado->getIdentity()->getId();
                    $this->layout()->nome = $resultado->getIdentity()->getNome();
                    $view = new ViewModel(array('nome' => $resultado->getIdentity()->getNome()));
                }else
                    $view = new ViewModel(array('erro' => "Cadastro inativo.<br>Acesse seu e-mail para ativar seu cadastro!"));
            }else
                $view = new ViewModel(array('erro' => "usuario ou senha invalidos"));
            $view->setTerminal(true);
            return $view;
        }else
            return new ViewModel();
    }

    /**
     * logoff the user
     */
    public function logoffAction(){
        $this->auth->clearIdentity();
        $this->redirect()->toRoute('Locador');
    }
    
}