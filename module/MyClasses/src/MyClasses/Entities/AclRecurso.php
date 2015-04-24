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
    
    /** @ManyToMany(targetEntity="AclPerfil", inversedBy="recursos") */
    private $perfis;
    
    /** adiciona AclPerfil para o AclRecurso
     *  @param AclPerfil $perfil */
    public function addPerfil(AclPerfil $perfil){
        if ($this->perfis->contains($perfil))
            return;
        $this->perfis->add($perfil);
        $perfil->addRecurso($this);
    }
    
    /** deleta AclPerfil do AclRecurso
     *  @param AclPerfil $perfil */
    public function delPerfil(AclPerfil $perfil){
        if (!$this->perfis->contains($perfil))
            return;
        $this->perfis->removeElement($perfil);
        $perfil->delRecurso($this);
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