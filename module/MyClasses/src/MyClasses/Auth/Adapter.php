<?php

/**
 * Classe adaptadora para trabalhar com a parte de autenticação integrando Zend framework ao Doctrine framework
 * @author Rogério Martins da Silva 27/08/2010
 */

class USINAWEB_Auth_Adapter implements Zend_Auth_Adapter_Interface{

	private $login;
	private $senha;
	public function __construct($login, $senha) {
		$this->login = $login;
		$this->senha = $senha;
	}


	/**
	 * Método sobrescrito utilizado na autenticação do usuário
	 * @see library/Zend/Auth/Adapter/Zend_Auth_Adapter_Interface#authenticate()
	 */
	public function authenticate() {
		$usuario = new Usuarios();
		$listaUsuarios =  $usuario->login($this->login,$this->senha);
		//verifica se foi retornado algum registro pela busca
		if(sizeof($listaUsuarios) > 0)
		{
			return $this->resultado(Zend_Auth_Result::SUCCESS, "logado com sucesso!."); //resultado ok
		}else {
			return $this->resultado(Zend_Auth_Result::FAILURE, "Senha ou login inválidos."); //resultado falhou
		}
	}
	
	
	/**
	 * Esse método é responsável por retornar as mensagens do sistema em um objeto Zend_Auth_Result
	 * @param $code codigo da mensagem
	 * @param $mensagens mensagem a ser exibida
	 * @return Zend_Auth_Result
	 */
	public function resultado($code, $mensagens=null) {
		if (!is_array($mensagens)) {
			$mensagens = array($mensagens);
		}
		return new Zend_Auth_Result($code, $this->login, $mensagens);
	}

}