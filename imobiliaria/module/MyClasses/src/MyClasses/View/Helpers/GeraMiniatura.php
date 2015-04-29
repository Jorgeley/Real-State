<?php
/**
 * Helper utilizdo para gerar miniaturas na view
 * @author Rogério Martins 01/09/2010
 *
 */
class USINAWEB_Helper_GeraMiniatura extends Zend_View_Helper_Abstract{

	/**
	 * Gera a miniatura da imagem
	 * @param $imagem caminho completo da imagem
	 * @param $tamanho tamanho maximo
	 * @return tag html
	 */
	public function geraminiatura($imagem,$tamanho,$align = null){
		$manipularImagem = new USINAWEB_Util_ManipulacaoImagens();
		return $manipularImagem->getImagemMiniaturaHTML($imagem,$tamanho,$align);
	}
}