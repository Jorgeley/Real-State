<?php
namespace MyClasses\Entities;
/**
 * @Entity
 */
class AclUsuario{
        
    /** @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO") */
    private $id;
    
    /** @Column(type="string", length=100) */
    private $nome;
    
    /** @Column(type="string", length=100) */
    private $cargo;
 
    /**
     * @ManyToOne(targetEntity="AclPerfil", inversedBy="usuarios")
     * @JoinColumn(name="perfil_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $perfil;

    /** @Column(type="string", length=100) */
    private $login;
    
    /** @Column(type="string", length=200, nullable=true) */
    private $senha;

    public function getId() {
		return $this->id;
	}

	public function getNome() {
		return $this->nome;
	}

	public function getCargo() {
		return $this->cargo;
	}

	public function getPerfil() {
		return $this->perfil;
	}

	public function getLogin() {
		return $this->login;
	}

	public function getSenha() {
		return $this->senha;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setNome($nome) {
		$this->nome = $nome;
	}

	public function setCargo($cargo) {
		$this->cargo = $cargo;
	}

	/** @param AclPerfil $perfil */
	public function setPerfil(AclPerfil $perfil) {
		$this->perfil = $perfil;
	}

	public function setLogin($login) {
		$this->login = $login;
	}

	public function setSenha($senha) {
		$this->senha = $senha;
	}

	/**
	 * seta todos os atributos
	 * @param array $atributos
	 */
	public function sets(array $atributos){
		foreach ($atributos as $atributo=>$valor){
			switch ($atributo){
				case 'senha':	  $this->$atributo = sha1($_POST[$atributo]); break;
				default:		  $this->$atributo = $_POST[$atributo];break;
			}
		}
	}

}