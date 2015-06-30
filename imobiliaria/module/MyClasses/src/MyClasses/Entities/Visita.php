<?php

namespace MyClasses\Entities;

/**
 * @Entity
 */
class Visita {

    /** @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO") */
    private $id;

    /** @Column(type="datetime", nullable=false) */
    private $data;

    /** @Column(type="text", nullable=true) */
    private $fechamento;

    /** @Column(type="string", length=10, options={"default":"agendada"}) */
    private $status;

    /**
     * @ManyToOne(targetEntity="Locatario", inversedBy="visitas")
     * @JoinColumn(name="locatario_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $locatario;

    /**
     * @ManyToOne(targetEntity="Locador", inversedBy="visitas")
     * @JoinColumn(name="locador_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $locador;

    /**
     * @ManyToOne(targetEntity="Imovel", inversedBy="comentarios")
     * @JoinColumn(name="imovel_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $imovel;

    public function getId() {
        return $this->id;
    }

    public function getData() {
        return (isset($this->data)) ? $this->data->format('d/m/Y H:i:s') : null;
    }

    function getFechamento() {
        return $this->fechamento;
    }

    function getStatus() {
        return $this->status;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setData($data) {
        $data =   substr($data, 6, 4)
                        .substr($data, 2, 4)
                        .substr($data, 0, 2)
                        .substr($data, 11, 8);
        $this->data = new \DateTime($data);
    }

    function setFechamento($fechamento) {
        $this->fechamento = $fechamento;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    /** @param Imovel $imovel */
    public function setImovel(Imovel $imovel) {
        $this->imovel = $imovel;
    }

    /** @return Imovel */
    public function getImovel() {
        return $this->imovel;
    }

    /** @param Locatario $locatario */
    public function setLocatario(Locatario $locatario) {
        $this->locatario = $locatario;
    }

    /** @return Locatario */
    public function getLocatario() {
        return $this->locatario;
    }

    /** @param Locador $locador */
    public function setLocador(Locador $locador) {
        $this->locador = $locador;
    }

    /** @return Locador */
    public function getLocador() {
        return $this->locador;
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
