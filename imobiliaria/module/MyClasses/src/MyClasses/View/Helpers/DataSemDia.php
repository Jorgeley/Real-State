<?php
/**
 * Helper utilizdo para retornar as informações relativas a data em formato pt-br sem o dia
 * @author Rogério Martins 28/01/2011
 *
 */
class USINAWEB_Helper_DataSemDia extends Zend_View_Helper_Abstract{

	public function dataSemDia($dt){
		$datas = new USINAWEB_Util_Datas();
		$datas->setData($dt);
		return $datas->getDataPtBr();
	}
}