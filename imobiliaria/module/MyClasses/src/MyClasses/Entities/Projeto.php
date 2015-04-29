<?php
namespace MyClasses\Entities;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity
 */
class Projeto{
    
    public function __construct(){
        $this->tarefas = new ArrayCollection();
    }
        
    /** @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO") */
    private $id;
    
    /** @Column(type="string", length=100) */
    private $nome;
    
    /** @Column(type="text", nullable=true) */
    private $descricao;
    
    /** @Column(type="datetime", nullable=true) */
    private $vencimento;
    
    /** @Column(type="datetime", nullable=true) */
    private $modificado;
 
    /**
     * @ManyToOne(targetEntity="AclPerfil", inversedBy="projetosEquipe")
     * @JoinColumn(name="perfil_id", referencedColumnName="id", onDelete="SET NULL")
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
 
    /**
     * @ManyToOne(targetEntity="AclUsuario", inversedBy="projetos")
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
    
    /** @OneToMany(targetEntity="Tarefa", mappedBy="projeto", cascade="persist") */
    private $tarefas;
    
    /** adiciona Tarefa ao Projeto
     *  @param Tarefa $tarefa */
    public function addTarefa(Tarefa $tarefa){
        $this->tarefas->add($tarefa);
        $tarefa->setProjeto($this);
    }
    
    /** @return Doctrine\Common\Collections\ArrayCollection */
    public function getTarefas(){
        return $this->tarefas;
    }
    
    public function getId() {
		return $this->id;
	}

	public function getNome() {
		return $this->nome;
	}

	public function getDescricao() {
		return $this->descricao;
	}
	
    public function getVencimento(){
	    return (isset($this->vencimento))
    	    ? $this->vencimento->format('d/m/Y H:i:s')
    	    : null;
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

	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}
	
    public function setVencimento($vencimento){
	    $this->vencimento = new \DateTime($vencimento);
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