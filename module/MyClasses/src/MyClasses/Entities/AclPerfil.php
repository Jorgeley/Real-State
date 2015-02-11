<?php
namespace MyClasses\Entities;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity
 */
class AclPerfil{

	public function __construct(){
    	$this->usuarios = new ArrayCollection();
    	$this->recursos = new ArrayCollection();
    }
        
    /** @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO") */
    private $id;
 
    /** @Column(type="string", length=100) */
    private $perfil;
    
    /** @OneToMany(targetEntity="AclUsuariosRecursos", mappedBy="perfil", cascade="persist") */
    private $recursos;
    
    /** adiciona recursos ao perfil 
     *  @param AclRecurso $recurso */
    public function addRecurso(AclUsuariosRecursos $recurso){
    	$this->recursos->add($recurso);
    	$recurso->setPerfil($this);
    }
    
    /** @return Doctrine\Common\Collections\ArrayCollection */
    public function getRecursos(){
        return $this->recursos;
    }
    
    /**
     * @OneToMany(targetEntity="AclUsuario", mappedBy="perfil", cascade="persist")
     */
    private $usuarios;
    
    /** adiciona usuario ao perfil 
     *  @param AclUsuario $usuario */
    public function addUsuario(AclUsuario $usuario){
    	$this->usuarios->add($usuario);
    	$usuario->setPerfil($this);
    }

    /** @return Doctrine\Common\Collections\ArrayCollection */
    public function getUsuarios() {
    	return $this->usuarios;
    }
    	
	public function getId() {
		return $this->id;
	}

	public function getPerfil() {
		return $this->perfil;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setPerfil($perfil) {
		$this->perfil = $perfil;
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