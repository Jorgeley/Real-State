<?php
class Classes_Helpers_Servicos extends Zend_View_Helper_Abstract{
	protected $modelo;
	protected $dados;
	
	public function servicos(){
		$this->modelo = new Servico();
		$servicos = $this->modelo->buscaLimite(3);
		$this->dados = "";
		foreach ($servicos as $servico){
				$this->dados.="<li>";
				$this->dados.="<a href='".Zend_Controller_Front::getInstance()->getBaseUrl()."/servico/visualiza/id/".$servico["id"]."'>";
				$this->dados.="<img src='".Zend_Controller_Front::getInstance()->getBaseUrl()."/images/options_2.gif' style='float:left;height:20px' />";
				$this->dados.="<p>".$servico["titulo"]."</p>";
				$this->dados.="<b>R$".number_format($servico["preco"],2,",",".")."</b>";
				$this->dados.="</a>";
				$this->dados.="</li>";
		}
		return $this->dados;
	}

}