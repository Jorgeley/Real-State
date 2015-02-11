<?php
namespace MyClasses\Entities;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity
 */
class AclRecurso{
    
    public function __construct(){
    	$this->perfis = new ArrayCollection();
    }
        
    /** @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO") */
    private $id;
 
    /** @Column(type="string", length=100) */
    private $recurso;
    
    /** @OneToMany(targetEntity="AclUsuariosRecursos", mappedBy="recurso", cascade="persist") */
    private $perfis;
    
    /** adiciona perfil ao recurso 
     *  @param AclPerfil $perfil */
    public function addPerfil(AclUsuariosRecursos $perfil){
    	$this->perfis->add($perfil);
    	$perfil->setRecurso($this);
    }
    
    /** @return Doctrine\Common\Collections\ArrayCollection */
    public function getPerfis(){
        return $this->perfis;
    }
    	
	public function getId() {
		return $this->id;
	}

	public function getRecurso() {
		return $this->recurso;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setRecurso($recurso) {
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