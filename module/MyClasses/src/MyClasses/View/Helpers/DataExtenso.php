<?php
/**
 * Helper utilizdo para buscar as configurações do cliente
 * @author Rogério Martins 01/09/2010
 *
 */
class USINAWEB_Helper_DataExtenso extends Zend_View_Helper_Abstract{

	public function dataExtenso($dt){
		$datas = new USINAWEB_Util_Datas();
		$datas->setData($dt);
		return $datas->getDataExtenso();
	}
}