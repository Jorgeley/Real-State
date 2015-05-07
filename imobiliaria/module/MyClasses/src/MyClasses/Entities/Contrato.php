<?php

namespace MyClasses\Entities;

/**
 * @Entity
 */
class Contrato {

    /** @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO") */
    private $id;

    /** @Column(type="date", nullable=false) */
    private $dataInicial;

    /** @Column(type="date", nullable=false) */
    private $dataFinal;

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
     * @OneToOne(targetEntity="Imovel")
     * @JoinColumn(name="imovel_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $imovel;

    public function getId() {
        return $this->id;
    }

    public function getDataInicial() {
        return (isset($this->dataInicial)) ? $this->dataInicial->format('d/m/Y H:i:s') : null;
    }

    public function getDataFinal() {
        return (isset($this->dataFinal)) ? $this->dataFinal->format('d/m/Y H:i:s') : null;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setDataInicial() {
        $this->dataInicial = new \DateTime();
    }

    public function setDataFinal() {
        $this->dataFinal = new \DateTime();
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
