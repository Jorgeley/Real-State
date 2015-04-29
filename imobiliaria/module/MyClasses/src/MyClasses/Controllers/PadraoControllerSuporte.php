<?php
namespace MyClasses\Controllers;
use Zend\Authentication\AuthenticationService,
	Zend\Permissions\Acl\Acl,
	Zend\Mvc\MvcEvent,
	Zend\EventManager\EventManagerInterface,
	Zend\Mvc\Router\SimpleRouteStack,
	Zend\Http\Response,
	Zend\Mvc\Router\Http\Literal,
	Zend\Mvc\Controller\AbstractActionController;
/** CONTROLOADOR PADRÃO PARA TODOS OS CONTROLADORES DO MÓDULO "SUPORTE"
 * @author jorgeley 
 * @version **/

/**
 * parece ser um bug, não sei porque, mas o proxie não é carregado automaticamente...
 * ...deve ser alguma coisa com a sessão, pois o objeto é recarregado da sessão
 */
//require_once __DIR__.'/../Entities/Proxies/__CG__MyClassesEntitiesAclPerfil.php';

abstract class PadraoControllerSuporte extends AbstractActionController{
	
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
		//quebrando a URL
		$uri = explode('/',substr($_SERVER['REQUEST_URI'],1));
		$uri[2] = !isset($uri[2]) ? "index" : $uri[2];
	    $auth = new AuthenticationService();
	    $identity = $auth->getStorage()->read();
	    $acl = $identity[2];
	    //verificando autenticidade
		if (!$auth->hasIdentity())	//existe identidade?
			$this->redirect()->toRoute('suporte/logoff');
		elseif (	!$acl->hasResource( $uri[0].'/'.$uri[1] ) || //existe o recurso na acl?	    							 									
	    			!$acl->isAllowed( $identity[1], $uri[0].'/'.$uri[1], $uri[2] ) ) //ou existe, mas tem a permissão no perfil, recurso, ação, privilégio?
						$this->redirect()->toRoute('suporte/logoff');
			else{
	    	    $this->layout()->id = $identity[0]->getId();
	    	    $this->layout()->nome = $identity[0]->getNome();
	    	    $this->layout()->cargo = $identity[0]->getCargo();
	    	    $this->layout()->recursos = $acl->getResources();
	    	}
	}
	
	/** gera PDF de um registro
	 * @return void*/	 
	/* public function pdf(){
		Zend_Layout::getMvcInstance()->disableLayout();
		$this->pdf = new Classes_Pdf_TCPDF($this->controlador);
		$this->pdf->init();
		//self::indexAction();
		if ($this->modelo->_referenceMap!=NULL)
			$registro = $this->modelo->registro($this->id);
		else{
			$registro = $this->modelo->find($this->id)->toArray();
			$registro = $registro[0]; 
		}
		$dados = "<table border='1'><tr>";
		foreach ($registro as $chave=>$valor)
			$dados .= "<th width='10'>".$chave."</th><td width='50'>".$valor."</td></tr><tr>";
		$dados .= "</tr></table>";
		$this->pdf->escreve($dados);
		$this->pdf->gera();
	} */	
}