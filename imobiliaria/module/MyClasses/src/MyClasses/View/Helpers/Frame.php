<?php
class Classes_Helpers_Frame extends Zend_View_Helper_Abstract{
   
	public function frame(){
		$fc = Zend_Controller_Front::getInstance();
		$fc->registro['os_empresa_agrupadora_id'] = $_REQUEST['os_empresa_agrupadora_id']!=NULL ? $_REQUEST['os_empresa_agrupadora_id'] : NULL;
		$modelo = new Empresa();
		$empresas = $modelo->arraySelect();	
			
			$dados = $_REQUEST['os_empresa_agrupadora_id'].$fc->getRequest()->getParam("os_empresa_agrupadora_id").$fc->registro['os_empresa_agrupadora_id'];
			$dados .= "<label for='os_empresa_agrupadora_id'>Empresa:</label>\n";
			$dados .= "<select name='os_empresa_agrupadora_id' id='os_empresa_agrupadora_id' required onchange=\"location.href='?os_empresa_agrupadora_id='+this.value;\">\n";
			$dados .= "<option></option>\n";
			foreach ($empresas as $id=>$empresa){
						$dados .= "<option ";
						$dados .= ($fc->registro['os_empresa_agrupadora_id']==$id)?'selected':NULL;
						$dados .= " value=".$id.">$empresa</option>\n";
			}
			$dados .= "</select><br>\n";
				
		$modelo = new Setor();
		$setores = $modelo->arraySelect($fc->registro['os_empresa_agrupadora_id']);	
			$dados .= "<label for='os_setor_id'>Setor:</label>\n";
			$dados .= "<select name='os_setor_id' id='os_setor_id' required>\n";
			$dados .= "<option></option>\n";
			foreach ($setores as $id=>$setor){
				$dados .= "<option ";
				$dados .= ($fc->registro['os_setor_id']==$id)?'selected':NULL;
				$dados .= " value=".$id.">".$setor."</option>\n";
			}
			$dados .= "</select><br>";
			file_put_contents(APPLICATION_PATH."\\..\www\\frame\Equipamento.html", $dados);
   }
}