<?php
namespace MyClasses\Entities;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity
 */
class Tarefa{
    
    public function __construct(){
        $this->comentarios = new ArrayCollection();
        $this->usuariosExclusaoSincronizada = new ArrayCollection();
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

	/** @param AclUsuario $usuario */
	public function setUsuario(AclUsuario $usuario) {
		$this->usuario = $usuario;
		//$this->setEquipe($usuario->getPerfil());
	}

	/** @return AclUsuario */
	public function getUsuario() {
		return $this->usuario;
	}
    
    /** Ao excluir uma tarefa de equipe, nao pode exclui-la do BD, pois os usuarios da equipe 
     * nao sincronizaram, se excluir eles ficarao com o XML desatualizado contendo a tarefa 
     * ainda, entao seto a tarefa para modificada e a cada sincronizaçao vou adicionando os 
     * usuarios que ja sincronizaram nesse ArrayCollection $usuarioExclusaoSincronizada, 
     * quando esse ArrayCollection tiver todos os usuarios da equipe ae sim excluo do BD
     * @OneToMany(targetEntity="AclUsuario", mappedBy="tarefaExclusaoSincronizada") */
    private $usuariosExclusaoSincronizada;
    
    /** adiciona AclUsuario que ja sincronizou a exclusao da tarefa
     *  @param AclUsuario $usuario */
    public function addUsuarioExclusaoSincronizada(AclUsuario $usuario){
        $this->usuariosExclusaoSincronizada->add($usuario);
        $usuario->setTarefaExclusaoSincronizada($this);
    }
    
    /** @return Doctrine\Common\Collections\ArrayCollection */
    public function getUsuariosExclusaoSincronizada(){
        return $this->usuariosExclusaoSincronizada;
    }
 
    /**
     * @ManyToOne(targetEntity="Projeto", inversedBy="tarefas")
     * @JoinColumn(name="projeto_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $projeto;

	/** @param Projeto $projeto */
	public function setProjeto(Projeto $projeto) {
		$this->projeto = $projeto;
	}

	/** @return Projeto */
	public function getProjeto() {
		return $this->projeto;
	}
    
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
    
    /** @Column(type="datetime", nullable=true) */
    private $modificado;
    
    /** @Column(type="string", length=9, options={"default":"aberta"}) */
    private $status;

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
    	    ? $this->vencimento//->format('d/m/Y H:i:s')
    	    : null;
	}
	
	public function getModificado(){
	    return (isset($this->modificado))
    	    ? $this->modificado
    	    : null;
	}

	public function getStatus() {
		return $this->status;
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
    * valores possiveis:
    * aberta (padrao)
    * concluir (colaborador)
    * rejeitada (administrador)
    * concluida (administrador)
    * arquivada (todos)
    * excluir (flag para excluir somente qdo toda a equipe tiver sincronizado a exclusao)
    */
	public function setStatus($status) {
		$this->status = $status;
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