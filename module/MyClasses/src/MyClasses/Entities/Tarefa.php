<?php
namespace MyClasses\Entities;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity
 */
class Tarefa{
    
    public function __construct(){
        $this->tarefas = new ArrayCollection();
        $this->comentarios = new ArrayCollection();
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
 
    /**
     * @ManyToOne(targetEntity="AclUsuario", inversedBy="tarefas")
     * @JoinColumn(name="usuario_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $usuario;
    
    /** @OneToMany(targetEntity="DialogoUsuarioTarefa", mappedBy="tarefa", cascade="persist") */
    private $comentarios;
    
    /** adiciona comentario da Tarefa ao DialogoUsuarioTarefa
     *  @param DialogoUsuarioTarefa $comentario */
    public function addComentario(DialogoUsuarioTarefa $comentario){
        $this->comentarios->add($comentario);
        $comentario->setTarefa($this);
    }
    
    /** @return Doctrine\Common\Collections\ArrayCollection */
    public function getComentarios(){
        return $this->comentarios;
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
	    return $this->vencimento;
	}

	public function getUsuario() {
		return $this->usuario;
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
	    $this->vencimento = $vencimento;
	}

	/** @param AclUsuario $usuario */
	public function setUsuario(AclUsuario $usuario) {
		$this->usuario = $usuario;
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