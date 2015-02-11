<?php
namespace MyClasses\Entities;
/**
 * @Entity
 */
class DialogoUsuarioTarefa{
        
    /** @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO") */
    private $id;
    
    /** @Column(type="text", nullable=false) */
    private $comentario;
    
    /** @Column(type="datetime", nullable=false) */
    private $data;
    
    /**
     * @ManyToOne(targetEntity="AclUsuario", inversedBy="comentarios")
     * @JoinColumn(name="usuario_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $usuario;
    
    /**
     * @ManyToOne(targetEntity="Tarefa", inversedBy="comentarios")
     * @JoinColumn(name="tarefa_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $tarefa;    

    public function getId() {
		return $this->id;
	}

	public function getComentario() {
		return $this->comentario;
	}
	
	public function getData(){
	    return $this->data;
	}

	/** @return AclUsuario */
	public function getUsuario() {
		return $this->usuario;
	}

	/** @return Tarefa */
	public function getTarefa() {
		return $this->tarefa;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setComentario($comentario) {
		$this->comentario = $comentario;
	}
	
	public function setData($data){
	    $this->data = $data;
	}

	/** @param AclUsuario $usuario */
	public function setUsuario(AclUsuario $usuario) {
		$this->usuario = $usuario;
	}

	/** @param Tarefa $tarefa */
	public function setTarefa(Tarefa $tarefa) {
		$this->tarefa = $tarefa;
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