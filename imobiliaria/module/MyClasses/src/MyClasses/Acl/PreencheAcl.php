<?php
class USINAWEB_Acl_PreencheAcl
{

	/**
	 * Create the acl
	 * @param $roles
	 * @param $resources
	 */
	public function criaAcl(array $roles, array $resources)
	{
		//Creating the Acl object
		$acl = new Zend_Acl();

		foreach ($roles as $role) {
			//Creating the roles and adding on Acl
			$acl->addRole(new Zend_Acl_Role($role));
		}

		foreach ($resources as $resource) {
			//Creating the resources and adding on acl
			$acl->addResource(new Zend_Acl_Resource($resource));
		}

		//Creating a namespace (session) for the Acl object
		$ns = new Zend_Session_Namespace();
		//clean the session
		unset($ns->acl);
		$ns->acl = $acl;

	}

	/**
	 * Set the permissions
	 * @param $usuario logged user
	 */
	public function setPrivilegios($usuario)
	{
		//retrieve the namespace acl(session)
		$ns = new Zend_Session_Namespace();
		if(isset($ns->acl)) {
			$usr = new Usuarios();
			//bring the permissions of logged user
			$permissoes = $usr->getPermissoesUsuario($usuario);

			foreach ($permissoes as $permissao) {
				//authorize the user
				$ns->acl->allow($permissao->roles,$permissao->resources,$permissao->privileges);
			}
		}
	}

}