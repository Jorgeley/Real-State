<?php

namespace MyClasses\Entities;

use Doctrine\Common\Collections\ArrayCollection,
    DoctrineModule\Paginator\Adapter\Collection,
    Doctrine\ORM\Tools\Pagination\Paginator,
    MyClasses\Conn\Conn;
    //Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @Entity
 */
class Locador {

    public function __construct() {
        $this->imoveis = new ArrayCollection();
        $this->visitas = new ArrayCollection();
        $this->contratos = new ArrayCollection();
    }

    /** @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO") */
    private $id;

    /** @Column(type="string", length=100) */
    private $nome;

    /** @Column(type="string", columnDefinition="CHAR(1)") */
    private $sexo;

    /** @Column(type="date", nullable=true) */
    private $nascimento;

    /** @Column(type="string", nullable=true) */
    private $email;

    /** @Column(type="string", nullable=true) */
    private $foneFixo;

    /** @Column(type="string", nullable=true) */
    private $foneCelular;

    /** @Column(type="integer", nullable=true) */
    private $cep;

    /** @Column(type="string", nullable=false) */
    private $endereco;

    /** @Column(type="string", nullable=false) */
    private $bairro;

    /** @Column(type="string", nullable=false) */
    private $cidade;

    /** @Column(type="string", columnDefinition="CHAR(2)") */
    private $uf;

    /** @Column(type="string", nullable=false) */
    private $login;

    /** @Column(type="string", nullable=false) */
    private $senha;

    /** @Column(type="string", nullable=true, length=9, options={"default":"inativo"}) */
    private $status;

    /** @OneToMany(targetEntity="Imovel", mappedBy="locador", cascade="persist") */
    private $imoveis;

    /** @OneToMany(targetEntity="Visita", mappedBy="locador") */
    private $visitas;

    /** @OneToMany(targetEntity="Contrato", mappedBy="locatario") */
    private $contratos;

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
        return (isset($this->vencimento))
    	    ? $this->nascimento//->format('d/m/Y H:i:s')
    	    : null;
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

    public function getStatus() {
        return $this->status;
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
        if (substr($nascimento, 2, 1)=="/")
            $nascimento =   substr($nascimento, 6, 4)
                            .substr($nascimento, 2, 4)
                            .substr($nascimento, 0, 2);
        $this->nascimento = new \DateTime($nascimento);
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

    /**
     * possible values:
     * inativo (default, not visible)
     * ativo (visible)
     * expirado (not visible)
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /** add property to locator
     *  @param Imovel $imovel */
    public function addImovel(Imovel $imovel) {
        $this->imoveis->add($imovel);
        $imovel->setLocador($this);
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
    
    /**
     * returns all locator properties paged 5 by 5
     * @param int $inicio
     * @param int $limite
     * @return Paginator
     */
    public function getImoveisPaginados($inicio = 0, $limite = 5){
        $qb = Conn::getConn()->createQueryBuilder();
        $qb->select('i')
            ->from('MyClasses\Entities\Imovel', 'i')
            ->orderBy('i.publicacao')
            ->setMaxResults($limite)
            ->setFirstResult($inicio);
        $paginador = new Paginator($qb->getQuery());
        return $paginador;
    }
    
    /**
     * returns all locator visits paged 5 by 5
     * @param int $inicio
     * @param int $limite
     * @return Paginator
     */
    public function getVisitasPaginadas($inicio = 0, $limite = 5){
        $qb = Conn::getConn()->createQueryBuilder();
        $qb->select('v')
            ->from('MyClasses\Entities\Visita', 'v')
            ->orderBy('v.data')
            ->setMaxResults($limite)
            ->setFirstResult($inicio);
        $paginador = new Paginator($qb->getQuery());
        return $paginador;
    }

}