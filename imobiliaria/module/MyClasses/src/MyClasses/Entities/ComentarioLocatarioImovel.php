<?php

namespace MyClasses\Entities;

/**
 * @Entity
 */
class ComentarioLocatarioImovel {

    /** @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO") */
    private $id;

    /** @Column(type="text", nullable=false) */
    private $comentario;

    /** @Column(type="datetime", nullable=false) */
    private $data;

    /**
     * @ManyToOne(targetEntity="Locatario", inversedBy="comentarios")
     * @JoinColumn(name="locatario_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $locatario;

    /**
     * @ManyToOne(targetEntity="Imovel", inversedBy="comentarios")
     * @JoinColumn(name="imovel_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $imovel;

    public function getId() {
        return $this->id;
    }

    public function getComentario() {
        return $this->comentario;
    }

    public function getData() {
        return (isset($this->data)) ? $this->data->format('d/m/Y H:i:s') : null;
    }

    /** @return Locatario */
    public function getLocatario() {
        return $this->locatario;
    }

    /** @return Imovel */
    public function getImovel() {
        return $this->imovel;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setComentario($comentario) {
        $this->comentario = $comentario;
    }

    public function setData() {
        $this->data = new \DateTime();
    }

    /** @param Locatario $locatario */
    public function setLocatario(Locatario $locatario) {
        $this->locatario = $locatario;
    }

    /** @param Imovel $imovel */
    public function setImovel(Imovel $imovel) {
        $this->imovel = $imovel;
    }

    /**
     * seta todos os atributos
     * @param array $atributos
     */
    public function sets(array $atributos) {
        foreach ($atributos as $atributo => $valor) {
            switch ($atributo) {
                case 'senha': $this->$atributo = sha1($_POST[$atributo]);
                    break;
                default: $this->$atributo = $_POST[$atributo];
                    break;
            }
        }
    }

}
