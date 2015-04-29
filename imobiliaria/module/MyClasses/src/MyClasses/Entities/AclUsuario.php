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
        $this->projetos = new ArrayCollection();
        $this->tarefas = new ArrayCollection();
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
    
    /** adiciona AclPerfil para o AclUsuario
     *  @param AclPerfil $equipe */
    public function addEquipe(AclPerfil $equipe){
        if ($this->equipes->contains($equipe))
            return;
        $this->equipes->add($equipe);
        $equipe->addUsuario($this);
    }
    
    /** deleta AclPerfil do AclUsuario
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
       
    /** @OneToMany(targetEntity="Projeto", mappedBy="usuario") */
    private $projetos;
    
    /** adiciona Projeto para o AclUsuario
     *  @param Projeto $projeto */
    public function addProjeto(Projeto $projeto){
        $this->projetos->add($projeto);
        $projeto->setUsuario($this);
    }
    
    /** @return Doctrine\Common\Collections\ArrayCollection */
    public function getProjetos(){
        return $this->projetos;
    }
    
    /** @OneToMany(targetEntity="Tarefa", mappedBy="usuario", cascade="persist") */
    private $tarefas;
    
    /** adiciona Tarefa para o AclUsuario
     *  @param Tarefa $tarefa */
    public function addTarefa(Tarefa $tarefa){
        $this->tarefas->add($tarefa);
        $tarefa->setUsuario($this);
    }
    
    /** @return Doctrine\Common\Collections\ArrayCollection */
    public function getTarefas(){
        return $this->tarefas;
    }
    
    /**
     * @ManyToOne(targetEntity="Tarefa", inversedBy="usuariosExclusaoSincronizada")
     * @JoinColumn(name="tarefa_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $tarefaExclusaoSincronizada;

	/** @param Tarefa $tarefa */
	public function setTarefaExclusaoSincronizada(Tarefa $tarefa) {
		$this->tarefaExclusaoSincronizada = $tarefa;
	}

	/** @return Tarefa */
	public function getTarefaExclusaoSincronizada() {
		return $this->tarefaExclusaoSincronizada;
	}
    
    /**
     * @param array $idsProjetos
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getTarefasCorrelatas(array $idsProjetos){
        $tarefas = Conn::getConn()->createQuery("
                                        SELECT t FROM MyClasses\Entities\Tarefa t
                                        WHERE
                                            t.usuario = ".$this->getId()."
                                            AND
                                            t.projeto NOT IN (".implode(",", $idsProjetos).")"
                                        )->getResult();
        return $tarefas;
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