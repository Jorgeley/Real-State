<?php
namespace MyClasses\Entities;
/**
 * @Entity
 */
class AclUsuariosRecursos{
        
    /** @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO") */
    private $id;
    
    /** @Column(type="string", length=100) */
    private $permissao;
    
    /**
     * @ManyToOne(targetEntity="AclPerfil", inversedBy="recursos")
     * @JoinColumn(name="perfil_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $perfil;
    
    /**
     * @ManyToOne(targetEntity="AclRecurso", inversedBy="perfis")
     * @JoinColumn(name="recurso_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $recurso;    

	public function getId() {
		return $this->id;
	}

	public function getPermissao() {
		return $this->permissao;
	}

	/** @return AclPerfil */
	public function getPerfil() {
		return $this->perfil;
	}

	public function getRecurso() {
		return $this->recurso;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setPermissao($permissao) {
		$this->permissao = $permissao;
	}

	public function setPerfil(AclPerfil $perfil) {
		$this->perfil = $perfil;
	}

	public function setRecurso(AclRecurso $recurso) {
		$this->recurso = $recurso;
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