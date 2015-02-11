<?php
/**
 * Helper utilizdo para retornar as informações relativas a data e hora
 * @author Rogério Martins 28/01/2011
 *
 */
class USINAWEB_Helper_DataHora extends Zend_View_Helper_Abstract{

	public function dataHora($dt){
		$datas = new USINAWEB_Util_Datas();
		$datas->setData($dt);
		return $datas->getDataHoraPtBr();
	}
}