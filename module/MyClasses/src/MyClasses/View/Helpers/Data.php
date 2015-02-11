<?php
/**
 * Helper utilizdo para retornar as informações relativas a data em formato pt-br junto com o nome do dia
 * @author Rogério Martins 13/01/2011
 *
 */
class USINAWEB_Helper_Data extends Zend_View_Helper_Abstract{

	public function data($dt){
		$datas = new USINAWEB_Util_Datas();
		$datas->setData($dt);
		return $datas->getDataPtBr() . " " . $datas->getDia();
	}
}