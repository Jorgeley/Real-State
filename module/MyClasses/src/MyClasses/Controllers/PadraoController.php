<?php
namespace MyClasses\Controllers;
use MyClasses\Conn\Conn,
	Zend\Authentication\AuthenticationService,
	Zend\Mvc\MvcEvent,
	Zend\EventManager\EventManagerInterface,
	Zend\Mvc\Router\SimpleRouteStack,
	Zend\Http\Response,
	Zend\Mvc\Router\Http\Literal,
	Zend\Mvc\Controller\AbstractActionController;
/** CONTROLOADOR PADRÃO PARA TODOS OS CONTROLADORES DO MÓDULO "usuario"
 * @author jorgeley 
 * @version **/

/**
 * parece ser um bug, não sei porque, mas o proxie não é carregado automaticamente...
 * ...deve ser alguma coisa com a sessão, pois o objeto é recarregado da sessão
 */
//require_once __DIR__.'/../Entities/Proxies/__CG__MyClassesEntitiesAclPerfil.php';

abstract class PadraoController extends AbstractActionController{
	protected $conn;
	protected $auth;
	protected $identity;
	protected $usuario;
	
	public function __construct(){
		$this->conn = Conn::getConn();
		$this->auth = new AuthenticationService();
		$this->identity = $this->auth->getIdentity()[0];//getStorage()->read();
		//$this->usuario = $this->conn->find('MyClasses\Entities\AclUsuario',$this->identity->getId());
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
	    //verificando autenticidade
		if (!$this->auth->hasIdentity())	//existe identidade?
			$this->redirect()->toRoute('inicio');
		else{
    	    $this->layout()->id = $this->identity->getId();
    	    $this->layout()->nome = $this->identity->getNome();
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