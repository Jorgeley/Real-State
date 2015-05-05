<?php
namespace MyClasses\Entities;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @Entity
 */
class Bairro{
    
    public function __construct(){
        $this->imoveis = new ArrayCollection();
    }
        
    /** @Id @Column(type="integer")
     * @GeneratedValue(strategy="AUTO") */
    private $id;
    
    /** @Column(type="string", length=100) */
    private $nome;
    
    /** @Column(type="text", nullable=true) */
    private $descricao;
    
    /** @Column(type="boolean", nullable=true) */
    private $saneamento;
    
    /** @Column(type="boolean", nullable=true) */
    private $redeFluvial;
    
    /** @Column(type="boolean", nullable=true) */
    private $asfalto;
    
    /** @Column(type="boolean", nullable=true) */
    private $saude;
    
    /** @Column(type="boolean", nullable=true) */
    private $escola;
    
    /** @Column(type="boolean", nullable=true) */
    private $lazer;
    
    /** @OneToMany(targetEntity="Imovel", mappedBy="bairro") */
    private $imoveis;
    
    /** adiciona Imovel ao Bairro
     *  @param Imovel $imovel */
    public function addImovel(Imovel $imovel){
        $this->imoveis->add($imovel);
        $imovel->setBairro($this);
    }
    
    /** @return Doctrine\Common\Collections\ArrayCollection */
    public function getImoveis(){
        return $this->imoveis;
    }
    
    public function getId() {
		return $this->id;
	}

	public function getNome() {
		return $this->nome;
	}

	public function getDescricao() {
		return $this->descricao;
	}	public function getSaneamento(){
		return $this->saneamento;
	}

	public function setSaneamento($saneamento){
		$this->saneamento = $saneamento;
	}

	public function getRedeFluvial(){
		return $this->redeFluvial;
	}

	public function setRedeFluvial($redeFluvial){
		$this->redeFluvial = $redeFluvial;
	}

	public function getAsfalto(){
		return $this->asfalto;
	}

	public function getSaude(){
		return $this->saude;
	}

	public function getEscola(){
		return $this->escola;
	}

	public function getLazer(){
		return $this->lazer;
	}

	public function setAsfalto($asfalto){
		$this->asfalto = $asfalto;
	}
	
	public function setId($id) {
		$this->id = $id;
	}

	public function setSaude($saude){
		$this->saude = $saude;
	}

	public function setNome($nome) {
		$this->nome = $nome;
	}

	public function setEscola($escola){
		$this->escola = $escola;
	}

	public function setDescricao($descricao) {
		$this->descricao = $descricao;
	}

	public function setLazer($lazer){
		$this->lazer = $lazer;
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