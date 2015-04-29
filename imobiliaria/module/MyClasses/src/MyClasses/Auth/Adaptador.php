<?php
namespace MyClasses\Auth;
use Zend\Authentication\Adapter\AdapterInterface,
	Zend\Authentication\Adapter\DbTable as AuthAdapter,
	Zend\Authentication\Result as Result,
	Zend\Db\Adapter\Adapter as DbAdapter;

class Adaptador implements AdapterInterface{
	protected $login;
	protected $senha;
	protected $entity;
	protected $identidade;
	protected $credencial;
	protected $permissoes;
	
	public function __construct($entity, $login, $senha, $identidade='login', $credencial='senha', $permissoes=false){
		$this->login = $login;
		$this->senha = $senha;
		$this->entity = $entity;
		$this->identidade = $identidade;
		$this->credencial = $credencial;
		$this->permissoes = $permissoes;
	}
	
	/*
	 * @return \Zend\Authentication\Result
	 * @throws \Zend\Authentication\Adapter\Exception\ExceptionInterface
	 */
	public function authenticate(){
		// conexao
		$dbAdapter = new DbAdapter(array(
			'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
			'dbname'   => 'RioVerdeShopping',
		));
		// setando identificação
		$authAdapter = new AuthAdapter($dbAdapter);
		$authAdapter
			->setTableName($this->entity)
			->setIdentityColumn($this->identidade)
			->setCredentialColumn($this->credencial);
		$authAdapter
			->setIdentity($this->login)
			->setCredential($this->senha);
		//autentica
		$result = $authAdapter->authenticate();
		switch ($result->getCode()) {
			case Result::FAILURE_IDENTITY_NOT_FOUND:
				$msg = "Login inexistente!";
				break;
			case Result::FAILURE_CREDENTIAL_INVALID:
				$msg = "Senha inválida!";
				break;
			case Result::SUCCESS:
				$registro = $result->getIdentity();
				$msg = "Seja bem vindo(a) ".$registro['nome'];
				break;
			default:
				$msg = "Falha na tentativa de autenticação!";
				break;
		}
		return $msg;		
	}

}