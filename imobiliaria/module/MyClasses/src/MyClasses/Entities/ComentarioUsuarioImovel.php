<?php
namespace MyClasses\Entities;
/**
 * @Entity
 */
class ComentarioUsuarioImovel{
        
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

	/** @param AclUsuario $usuario */
	public function setUsuario(AclUsuario $usuario) {
		$this->usuario = $usuario;
	}

	/** @return AclUsuario */
	public function getUsuario() {
		return $this->usuario;
	}
    
    /**
     * @ManyToOne(targetEntity="Imovel", inversedBy="comentarios")
     * @JoinColumn(name="imovel_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $imovel;

	/** @param Imovel $imovel */
	public function setImovel(Imovel $imovel) {
		$this->imovel = $imovel;
	}

	/** @return Imovel */
	public function getImovel() {
		return $this->imovel;
	}    

    public function getId() {
		return $this->id;
	}

	public function getComentario() {
		return $this->comentario;
	}
	
	public function getData(){
	    return (isset($this->data))
	    ? $this->data->format('d/m/Y H:i:s')
	    : null;
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setComentario($comentario) {
		$this->comentario = $comentario;
	}
	
	public function setData(){
	    $this->data = new \DateTime();
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