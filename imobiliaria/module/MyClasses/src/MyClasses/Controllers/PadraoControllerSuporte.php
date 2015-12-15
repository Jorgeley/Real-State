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
/** default controller of module support
 * @author jorgeley 
 * @version **/

/**
 * maybe a bug? i don't know why, but the proxies are not loaded
 * ...something to do with session i think, because the object is loaded from session
 */
//require_once __DIR__.'/../Entities/Proxies/__CG__MyClassesEntitiesAclPerfil.php';

abstract class PadraoControllerSuporte extends AbstractActionController{
	
/**
 * overriding setEventManager method of AbstractActionControler
 * to call the authentication on "dispatch"
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
		//break the URL
		$uri = explode('/',substr($_SERVER['REQUEST_URI'],1));
		$uri[2] = !isset($uri[2]) ? "index" : $uri[2];
	    $auth = new AuthenticationService();
	    $identity = $auth->getStorage()->read();
	    $acl = $identity[2];
	    //verify authenticity
		if (!$auth->hasIdentity())	//there is no id?
			$this->redirect()->toRoute('suporte/logoff');
		elseif (	!$acl->hasResource( $uri[0].'/'.$uri[1] ) || //there is no resources on acl?
	    			!$acl->isAllowed( $identity[1], $uri[0].'/'.$uri[1], $uri[2] ) ) //or there is, but not allowed, resource, action, privilege?
						$this->redirect()->toRoute('suporte/logoff');
			else{
	    	    $this->layout()->id = $identity[0]->getId();
	    	    $this->layout()->nome = $identity[0]->getNome();
	    	    $this->layout()->cargo = $identity[0]->getCargo();
	    	    $this->layout()->recursos = $acl->getResources();
	    	}
	}
	
	/** generate PDF of a record
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