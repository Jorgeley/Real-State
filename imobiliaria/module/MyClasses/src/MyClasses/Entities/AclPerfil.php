<?php
namespace MyClasses\Entities;
use Doctrine\Common\Collections\ArrayCollection,
    MyClasses\Entities\AclUsuario,
    MyClasses\Entities\AclRecurso,
    MyClasses\Entities\Projeto;
/**
 * @Entity
 */
class AclPerfil{

	public function __construct(){
    	$this->usuarios = new ArrayCollection();
    	$this->projetos = new ArrayCollection();
    	$this->recursos = new ArrayCollection();
    }
        
    /** @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO") */
    private $id;
 
    /** @Column(type="string", length=100) */
    private $perfil;
    
    /** @ManyToMany(targetEntity="AclUsuario", mappedBy="equipes") */
    private $usuarios;
    
    /** adiciona AclUsuario para o AclPerfil
     *  @param AclUsuario $usuario */
    public function addUsuario(AclUsuario $usuario){
        if ($this->usuarios->contains($usuario))
            return;
        $this->usuarios->add($usuario);
        $usuario->addEquipe($this);
    }
    
    /** deleta AclUsuario do AclPerfil
     *  @param AclUsuario $usuario */
    public function delUsuario(AclUsuario $usuario){
        if (!$this->usuarios->contains($usuario))
            return;
        $this->usuarios->removeElement($usuario);
        $usuario->delEquipe($this);
    }
    
    /** @return Doctrine\Common\Collections\ArrayCollection */
    public function getUsuarios(){
        return $this->usuarios;
    }
    
    /** @ManyToMany(targetEntity="AclRecurso", mappedBy="perfis") */
    private $recursos;
    
    /** adiciona AclRecurso para o AclPerfil
     *  @param AclRecurso $recurso */
    public function addRecurso(AclRecurso $recurso){
        if ($this->recursos->contains($recurso))
            return;
        $this->recursos->add($recurso);
        $recurso->addPerfil($this);
    }
    
    /** deleta AclRecurso do AclPerfil
     *  @param AclRecurso $recurso */
    public function delRecurso(AclRecurso $recurso){
        if (!$this->recursos->contains($recurso))
            return;
        $this->recursos->removeElement($recurso);
        $recurso->delPerfil($this);
    }
    
    /** @return Doctrine\Common\Collections\ArrayCollection */
    public function getRecursos(){
        return $this->recursos;
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