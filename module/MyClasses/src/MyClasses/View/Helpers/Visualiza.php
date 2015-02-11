<?php
class Classes_Helpers_Visualiza extends Zend_View_Helper_Abstract{
	protected $dados;
   
	public function visualiza($registro,$controlador){
		$this->dados = "<table>";
		foreach ($registro as $id=>$valor)
      		$this->dados .= "<tr><td id='$id'><b>$id</b></td><td>$valor</td></tr>";
      	$this->dados .="</table>";
      	return $this->dados;
   }
}