<?php

namespace MyClasses\Entities;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
 */
class Locatario {

    public function __construct() {
        $this->imoveis = new ArrayCollection();
        $this->visitas = new ArrayCollection();
        $this->contratos = new ArrayCollection();
        $this->comentarios = new ArrayCollection();
    }

    /** @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO") */
    private $id;

    /** @Column(type="string", length=100, nullable=false) */
    private $nome;

    /** @Column(type="string", columnDefinition="CHAR(1)") */
    private $sexo;

    /** @Column(type="date", nullable=true) */
    private $nascimento;

    /** @Column(type="string", nullable=false) */
    private $email;

    /** @Column(type="string", nullable=true) */
    private $foneFixo;

    /** @Column(type="string", nullable=true) */
    private $foneCelular;

    /** @Column(type="integer", nullable=true) */
    private $cep;

    /** @Column(type="string", nullable=true) */
    private $endereco;

    /** @Column(type="string", nullable=true) */
    private $bairro;

    /** @Column(type="string", nullable=true) */
    private $cidade;

    /** @Column(type="string", columnDefinition="CHAR(2)") */
    private $uf;

    /** @Column(type="string", nullable=true) */
    private $login;

    /** @Column(type="string", nullable=true) */
    private $senha;

    /** @OneToMany(targetEntity="Imovel", mappedBy="locatario") */
    private $imoveis;

    /** @OneToMany(targetEntity="Visita", mappedBy="imovel", cascade="persist") */
    private $visitas;

    /** @OneToMany(targetEntity="Contrato", mappedBy="locatario") */
    private $contratos;

    /** @OneToMany(targetEntity="ComentarioLocatarioImovel", mappedBy="locatario") */
    private $comentarios;

    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    function getSexo() {
        return $this->sexo;
    }

    function getNascimento() {
        return $this->nascimento;
    }

    function getEmail() {
        return $this->email;
    }

    function getFoneFixo() {
        return $this->foneFixo;
    }

    function getFoneCelular() {
        return $this->foneCelular;
    }

    function getCep() {
        return $this->cep;
    }

    function getEndereco() {
        return $this->endereco;
    }

    function getBairro() {
        return $this->bairro;
    }

    function getCidade() {
        return $this->cidade;
    }

    function getUf() {
        return $this->uf;
    }

    function getLogin() {
        return $this->login;
    }

    function getSenha() {
        return $this->senha;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    function setNascimento($nascimento) {
        $this->nascimento = $nascimento;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setFoneFixo($foneFixo) {
        $this->foneFixo = $foneFixo;
    }

    function setFoneCelular($foneCelular) {
        $this->foneCelular = $foneCelular;
    }

    function setCep($cep) {
        $this->cep = $cep;
    }

    function setEndereco($endereco) {
        $this->endereco = $endereco;
    }

    function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    function setUf($uf) {
        $this->uf = $uf;
    }

    function setLogin($login) {
        $this->login = $login;
    }

    function setSenha($senha) {
        $this->senha = $senha;
    }

    /** add property to tenant
     *  @param Imovel $imovel */
    public function addImovel(Imovel $imovel) {
        $this->imoveis->add($imovel);
        $imovel->setLocatario($this);
    }

    /** @return Doctrine\Common\Collections\ArrayCollection */
    public function getImoveis() {
        return $this->imoveis;
    }

    /** @return Doctrine\Common\Collections\ArrayCollection */
    public function getVisitas() {
        return $this->visitas;
    }

    /**  @param Visita $visita */
    public function addVisita(Visita $visita) {
        $this->visitas->add($visita);
        $visita->setImovel($this);
    }

    /** add Contract to tenant
     *  @param Contrato $contrato */
    public function addContrato(Contrato $contrato) {
        $this->contrato->add($contrato);
        $contrato->setLocatario($this);
    }

    /** @return Doctrine\Common\Collections\ArrayCollection */
    public function getContratos() {
        return $this->contratos;
    }

    /** add ComentarioLocatarioImovel to tenant
     *  @param ComentarioLocatarioImovel $comentario */
    public function addComentario(ComentarioLocatarioImovel $comentario) {
        $this->comentarios->add($comentario);
        $comentario->setLocatario($this);
    }

    /** @return Doctrine\Common\Collections\ArrayCollection */
    public function getComentarios() {
        return $this->comentarios;
    }

    /**
     * set all atributes
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
