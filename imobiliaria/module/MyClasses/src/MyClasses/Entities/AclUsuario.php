<?php
namespace MyClasses\Entities;

use Doctrine\Common\Collections\ArrayCollection,
    MyClasses\Conn\Conn,
    MyClasses\Entities\Projeto,
    MyClasses\Entities\Tarefa,
    MyClasses\Entities\EquipeUsuario;

/**
 * @Entity
 */
class AclUsuario{
    
    public function __construct(){
        $this->equipes = new ArrayCollection();
    }
        
    /** @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO") */
    private $id;
    
    /** @Column(type="string", length=100) */
    private $nome;
    
    /** @Column(type="string", length=100) */
    private $cargo;

    /** @Column(type="string", length=100) */
    private $login;
    
    /** @Column(type="string", length=200, nullable=true) */
    private $senha;
    
    /** @Column(type="datetime", nullable=true) */
    private $modificado;
    
    /** @ManyToMany(targetEntity="AclPerfil", inversedBy="usuarios") */
    private $equipes;
    
    /** add AclPerfil to AclUsuario
     *  @param AclPerfil $equipe */
    public function addEquipe(AclPerfil $equipe){
        if ($this->equipes->contains($equipe))
            return;
        $this->equipes->add($equipe);
        $equipe->addUsuario($this);
    }
    
    /** delete AclPerfil from AclUsuario
     *  @param AclPerfil $equipe */
    public function delEquipe(AclPerfil $equipe){
        if (!$this->equipes->contains($equipe))
            return;
        $this->equipes->removeElement($equipe);
        $equipe->delUsuario($this);
    }
    
    /** @return Doctrine\Common\Collections\ArrayCollection */
    public function getEquipes(){
        return $this->equipes;
    }
    
    public function getId() {
		return $this->id;
	}

	public function getNome() {
		return $this->nome;
	}

	public function getCargo() {
		return $this->cargo;
	}
	
	public function getLogin() {
		return $this->login;
	}

	public function getSenha() {
		return $this->senha;
	}
	
	public function getModificado(){
	    return (isset($this->modificado))
    	    ? $this->modificado
    	    : null;
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
	
	public function setLogin($login) {
		$this->login = $login;
	}

	public function setSenha($senha) {
		$this->senha = sha1($senha);
	}
	
	public function setModificado(){
	    $this->modificado = new \DateTime("now");
	}

	/**
	 * set all atributes
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