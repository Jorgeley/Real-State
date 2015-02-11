<?php
class Classes_Helpers_RegistrosDependentes extends Zend_View_Helper_Abstract{
	protected $dados;
   
	public function registrosDependentes($dependentes,$controlador){
		$fc = Zend_Controller_Front::getInstance();
		$this->dados = "<img src='".$fc->getBaseUrl()."/images/attention.png' style='float:left;width:120px;height:120px'>";
		$this->dados .= "<p style='font-size:18px'>Há <b>".count($dependentes)." registro(s)</b> relacionado(s) à este(a) ".$controlador."</p>";
		$this->dados .= "Caso este(a) ".$controlador." seja deletado(a) os registros abaixo (<u>e todos os outros relacionados a cada um deles</u>) também serão.<br>
						<div id='dependentes'>Realmente confirma a exclusão?
						<a href='".$fc->getBaseUrl()."/".$controlador."/deleta/codigo/".$fc->getRequest()->getParam('codigo')."'>SIM</a>
						<a href='".$fc->getBaseUrl()."/".$controlador."'>NÃO</a></div>
						<i style='color:red'>Obs: esta operação não poderá ser desfeita!</i>";
		$this->dados .= "<ul>";
		foreach ($dependentes as $dependente)
			foreach ($dependente as $valor)
	      		$this->dados .= "<li>".$valor."</li>";
	    $this->dados .= "</ul>";
      	return $this->dados;
   }
}