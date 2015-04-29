<?php
class USINAWEB_Acl_PreencheAcl
{

	/**
	 * Cria a acl a ser usada no sistema
	 * @param $roles papéis, ou seja, os usuários
	 * @param $resources recursos, ou seja, os módulos
	 */
	public function criaAcl(array $roles, array $resources)
	{
		//Criando o objeto Acl
		$acl = new Zend_Acl();

		foreach ($roles as $role) {
			//Crio as roles e as adiciona na acl
			$acl->addRole(new Zend_Acl_Role($role));
		}

		foreach ($resources as $resource) {
			//Crio as $resources e as adiciona na acl
			$acl->addResource(new Zend_Acl_Resource($resource));
		}

		//Crio um namespace (sessão) para o objeto Acl
		$ns = new Zend_Session_Namespace();
		//apaga a variavel acl dentro da sessão caso a mesma exista
		unset($ns->acl);
		$ns->acl = $acl;

	}

	/**
	 * Seta as permissões do usuário no sistema
	 * @param $usuario usuário logado
	 */
	public function setPrivilegios($usuario)
	{
		//recupera a acl do namespace (sessão)
		$ns = new Zend_Session_Namespace();
		if(isset($ns->acl)) {
			$usr = new Usuarios();
			//busca as permissões do usuário logado
			$permissoes = $usr->getPermissoesUsuario($usuario);

			foreach ($permissoes as $permissao) {
				//autoriza o usuário ao acesso
				$ns->acl->allow($permissao->roles,$permissao->resources,$permissao->privileges);
			}
		}
	}

}