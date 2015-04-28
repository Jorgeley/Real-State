<?php
namespace MyClasses\Entities;
/**
 * @Entity
 */
class EquipeUsuario{
        
    /** @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO") */
    private $id;
    
    /**
     * @ManyToOne(targetEntity="AclUsuario", inversedBy="equipes")
     * @JoinColumn(name="usuario_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $usuario;

	/** @param AclUsuario $usuario */
	public function setUsuario(AclUsuario $usuario) {
		$this->usuario = $usuario;
	}

	/** @return AclUsuario */
	public function getUsuario() {
		return $this->usuario;
	}
    
    /**
     * @ManyToOne(targetEntity="AclPerfil", inversedBy="usuarios")
     * @JoinColumn(name="perfil_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $equipe;

	/** @param AclPerfil $equipe */
	public function setEquipe(AclPerfil $equipe) {
		$this->equipe = $equipe;
	}

	/** @return AclPerfil */
	public function getEquipe() {
		return $this->equipe;
	}    

    public function getId() {
		return $this->id;
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