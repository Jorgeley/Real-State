<?php
namespace MyClasses\Entities;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity
 */
class Imovel{
    /** @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO") */
    private $id;
    
    /** @Column(type="string", columnDefinition="CHAR(1)", nullable=false) */
    private $tipo;
    
    /** @Column(type="text", nullable=false) */
    private $descricao;
    
    /** @Column(type="integer", nullable=true) */
    private $qtdComodos;
    
    /** @Column(type="integer", nullable=true) */
    private $qtdQuartos;
    
    /** @Column(type="integer", nullable=true) */
    private $qtdSuites;
    
    /** @Column(type="integer", nullable=true) */
    private $qtdBanheiros;
    
    /** @Column(type="integer", nullable=true) */
    private $qtdGaragens;
    
    /** @Column(type="boolean", nullable=true) */
    private $condominio;
    
    /** @Column(type="text", nullable=true) */
    private $condominioAreasPrivativas;
    
    /** @Column(type="text", nullable=true) */
    private $condominioAreasComuns;
    
    /** @Column(type="decimal", nullable=true) */
    private $condominioValor;
    
    /** @Column(type="integer", nullable=true) */
    private $cep;
    
    /** @Column(type="string", nullable=false) */
    private $endereco;
     
    /**
     * @ManyToOne(targetEntity="Bairro", inversedBy="imoveis")
     * @JoinColumn(name="bairro_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $bairro;
    
    /** @Column(type="decimal", nullable=true) */
    private $latitude;
    
    /** @Column(type="decimal", nullable=true) */
    private $longitude;
    
    /** @Column(type="integer", nullable=true) */
    private $idade;
    
    /** @Column(type="decimal", nullable=true) */
    private $iptu;
    
    /** @Column(type="boolean", nullable=true) */
    private $hipoteca;
    
    /** @Column(type="string", nullable=true) */
    private $hipotecaBanco;
    
    /** @Column(type="decimal", nullable=true) */
    private $hipotecaValorFinanciado;
    
    /** @Column(type="integer", nullable=true) */
    private $hipotecaQtdParcelas;
    
    /** @Column(type="decimal", nullable=true) */
    private $hipotecaValorParcela;
    
    /** @Column(type="decimal", nullable=true) */
    private $hipotecaSaldoDevedor;
    
    /** @Column(type="decimal", nullable=true) */
    private $areaLote;
    
    /** @Column(type="decimal", nullable=true) */
    private $areaConstruida;
    
    /** @Column(type="decimal", nullable=true) */
    private $valorm2;
    
    /** @Column(type="decimal", nullable=false) */
    private $valor;
    
    /** @Column(type="datetime", nullable=true) */
    private $publicacao;
    
    /** @OneToMany(targetEntity="ComentarioUsuarioImovel", mappedBy="imovel") */
    private $comentarios;
    
    /** @Column(type="string", length=9, options={"default":"inativo"}) */
    private $status;
    
    public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getTipo(){
		return $this->tipo;
	}

	public function setTipo($tipo){
		$this->tipo = $tipo;
	}

	public function getDescricao(){
		return $this->descricao;
	}

	public function setDescricao($descricao){
		$this->descricao = $descricao;
	}

	public function getQtdComodos(){
		return $this->qtdComodos;
	}

	public function setQtdComodos($qtdComodos){
		$this->qtdComodos = $qtdComodos;
	}

	public function getQtdQuartos(){
		return $this->qtdQuartos;
	}

	public function setQtdQuartos($qtdQuartos){
		$this->qtdQuartos = $qtdQuartos;
	}

	public function getQtdSuites(){
		return $this->qtdSuites;
	}

	public function setQtdSuites($qtdSuites){
		$this->qtdSuites = $qtdSuites;
	}

	public function getQtdBanheiros(){
		return $this->qtdBanheiros;
	}

	public function setQtdBanheiros($qtdBanheiros){
		$this->qtdBanheiros = $qtdBanheiros;
	}

	public function getQtdGaragens(){
		return $this->qtdGaragens;
	}

	public function setQtdGaragens($qtdGaragens){
		$this->qtdGaragens = $qtdGaragens;
	}

	public function getCondominio(){
		return $this->condominio;
	}

	public function setCondominio($condominio){
		$this->condominio = $condominio;
	}

	public function getCondominioAreasPrivativas(){
		return $this->condominioAreasPrivativas;
	}

	public function setCondominioAreasPrivativas($condominioAreasPrivativas){
		$this->condominioAreasPrivativas = $condominioAreasPrivativas;
	}

	public function getCondominioAreasComuns(){
		return $this->condominioAreasComuns;
	}

	public function setCondominioAreasComuns($condominioAreasComuns){
		$this->condominioAreasComuns = $condominioAreasComuns;
	}

	public function getCondominioValor(){
		return $this->condominioValor;
	}

	public function setCondominioValor($condominioValor){
		$this->condominioValor = $condominioValor;
	}

	public function getCep(){
		return $this->cep;
	}

	public function setCep($cep){
		$this->cep = $cep;
	}

	public function getEndereco(){
		return $this->endereco;
	}

	public function setEndereco($endereco){
		$this->endereco = $endereco;
	}
    
	public function getLatitude(){
		return $this->latitude;
	}

	public function setLatitude($latitude){
		$this->latitude = $latitude;
	}

	public function getLongitude(){
		return $this->longitude;
	}

	public function setLongitude($longitude){
		$this->longitude = $longitude;
	}

	public function getIdade(){
		return $this->idade;
	}

	public function setIdade($idade){
		$this->idade = $idade;
	}

	public function getIptu(){
		return $this->iptu;
	}

	public function setIptu($iptu){
		$this->iptu = $iptu;
	}

	public function getHipoteca(){
		return $this->hipoteca;
	}

	public function setHipoteca($hipoteca){
		$this->hipoteca = $hipoteca;
	}

	public function getHipotecaBanco(){
		return $this->hipotecaBanco;
	}

	public function setHipotecaBanco($hipotecaBanco){
		$this->hipotecaBanco = $hipotecaBanco;
	}

	public function getHipotecaValorFinanciado(){
		return $this->hipotecaValorFinanciado;
	}

	public function setHipotecaValorFinanciado($hipotecaValorFinanciado){
		$this->hipotecaValorFinanciado = $hipotecaValorFinanciado;
	}

	public function getHipotecaQtdParcelas(){
		return $this->hipotecaQtdParcelas;
	}

	public function setHipotecaQtdParcelas($hipotecaQtdParcelas){
		$this->hipotecaQtdParcelas = $hipotecaQtdParcelas;
	}

	public function getHipotecaValorParcela(){
		return $this->hipotecaValorParcela;
	}

	public function setHipotecaValorParcela($hipotecaValorParcela){
		$this->hipotecaValorParcela = $hipotecaValorParcela;
	}

	public function getHipotecaSaldoDevedor(){
		return $this->hipotecaSaldoDevedor;
	}

	public function setHipotecaSaldoDevedor($hipotecaSaldoDevedor){
		$this->hipotecaSaldoDevedor = $hipotecaSaldoDevedor;
	}

	public function getAreaLote(){
		return $this->areaLote;
	}

	public function setAreaLote($areaLote){
		$this->areaLote = $areaLote;
	}

	public function getAreaConstruida(){
		return $this->areaConstruida;
	}

	public function setAreaConstruida($areaConstruida){
		$this->areaConstruida = $areaConstruida;
	}

	public function getValorm2(){
		return $this->valorm2;
	}

	public function setValorm2($valorm2){
		$this->valorm2 = $valorm2;
	}

	public function getValor(){
		return $this->valor;
	}

	public function setValor($valor){
		$this->valor = $valor;
	}

	public function getPublicacao(){
		return $this->publicacao;
	}

	public function setPublicacao($publicacao){
		$this->publicacao = $publicacao;
	}
    
    /**  @param ComentarioUsuarioImovel $comentario */
    public function addComentario(ComentarioUsuarioImovel $comentario){
        $this->comentarios->add($comentario);
        $comentario->setImovel($this);
    }
    
    /** @return Doctrine\Common\Collections\ArrayCollection */
    public function getComentarios(){
        return $this->comentarios;
    }

	public function getStatus(){
		return $this->status;
	}
    
    /**
    * valores possiveis:
    * inativo (padrao, nao visivel)
    * ativo (visivel)
    * expirado (nao visivel)
    */
	public function setStatus($status) {
		$this->status = $status;
	}

	/** @param Bairro $bairro */
	public function setBairro(Bairro $bairro) {
		$this->bairro = $bairro;
	}

	/** @return Bairro */
	public function getBairro() {
		return $this->bairro;
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