<?php

namespace MyClasses\Entities\Proxies\__CG__\MyClasses\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Locador extends \MyClasses\Entities\Locador implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array();



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return array('__isInitialized__', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'id', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'nome', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'sexo', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'nascimento', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'email', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'foneFixo', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'foneCelular', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'cep', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'endereco', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'bairro', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'cidade', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'uf', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'login', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'senha', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'status', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'imoveis', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'visitas', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'contratos');
        }

        return array('__isInitialized__', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'id', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'nome', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'sexo', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'nascimento', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'email', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'foneFixo', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'foneCelular', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'cep', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'endereco', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'bairro', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'cidade', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'uf', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'login', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'senha', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'status', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'imoveis', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'visitas', '' . "\0" . 'MyClasses\\Entities\\Locador' . "\0" . 'contratos');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Locador $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', array());
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', array());
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', array());

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function getNome()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNome', array());

        return parent::getNome();
    }

    /**
     * {@inheritDoc}
     */
    public function getSexo()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSexo', array());

        return parent::getSexo();
    }

    /**
     * {@inheritDoc}
     */
    public function getNascimento()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNascimento', array());

        return parent::getNascimento();
    }

    /**
     * {@inheritDoc}
     */
    public function getEmail()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmail', array());

        return parent::getEmail();
    }

    /**
     * {@inheritDoc}
     */
    public function getFoneFixo()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFoneFixo', array());

        return parent::getFoneFixo();
    }

    /**
     * {@inheritDoc}
     */
    public function getFoneCelular()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFoneCelular', array());

        return parent::getFoneCelular();
    }

    /**
     * {@inheritDoc}
     */
    public function getCep()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCep', array());

        return parent::getCep();
    }

    /**
     * {@inheritDoc}
     */
    public function getEndereco()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEndereco', array());

        return parent::getEndereco();
    }

    /**
     * {@inheritDoc}
     */
    public function getBairro()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBairro', array());

        return parent::getBairro();
    }

    /**
     * {@inheritDoc}
     */
    public function getCidade()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCidade', array());

        return parent::getCidade();
    }

    /**
     * {@inheritDoc}
     */
    public function getUf()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUf', array());

        return parent::getUf();
    }

    /**
     * {@inheritDoc}
     */
    public function getLogin()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLogin', array());

        return parent::getLogin();
    }

    /**
     * {@inheritDoc}
     */
    public function getSenha()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSenha', array());

        return parent::getSenha();
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStatus', array());

        return parent::getStatus();
    }

    /**
     * {@inheritDoc}
     */
    public function setId($id)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setId', array($id));

        return parent::setId($id);
    }

    /**
     * {@inheritDoc}
     */
    public function setNome($nome)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNome', array($nome));

        return parent::setNome($nome);
    }

    /**
     * {@inheritDoc}
     */
    public function setSexo($sexo)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSexo', array($sexo));

        return parent::setSexo($sexo);
    }

    /**
     * {@inheritDoc}
     */
    public function setNascimento($nascimento)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNascimento', array($nascimento));

        return parent::setNascimento($nascimento);
    }

    /**
     * {@inheritDoc}
     */
    public function setEmail($email)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmail', array($email));

        return parent::setEmail($email);
    }

    /**
     * {@inheritDoc}
     */
    public function setFoneFixo($foneFixo)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFoneFixo', array($foneFixo));

        return parent::setFoneFixo($foneFixo);
    }

    /**
     * {@inheritDoc}
     */
    public function setFoneCelular($foneCelular)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFoneCelular', array($foneCelular));

        return parent::setFoneCelular($foneCelular);
    }

    /**
     * {@inheritDoc}
     */
    public function setCep($cep)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCep', array($cep));

        return parent::setCep($cep);
    }

    /**
     * {@inheritDoc}
     */
    public function setEndereco($endereco)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEndereco', array($endereco));

        return parent::setEndereco($endereco);
    }

    /**
     * {@inheritDoc}
     */
    public function setBairro($bairro)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBairro', array($bairro));

        return parent::setBairro($bairro);
    }

    /**
     * {@inheritDoc}
     */
    public function setCidade($cidade)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCidade', array($cidade));

        return parent::setCidade($cidade);
    }

    /**
     * {@inheritDoc}
     */
    public function setUf($uf)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUf', array($uf));

        return parent::setUf($uf);
    }

    /**
     * {@inheritDoc}
     */
    public function setLogin($login)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLogin', array($login));

        return parent::setLogin($login);
    }

    /**
     * {@inheritDoc}
     */
    public function setSenha($senha)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSenha', array($senha));

        return parent::setSenha($senha);
    }

    /**
     * {@inheritDoc}
     */
    public function setStatus($status)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStatus', array($status));

        return parent::setStatus($status);
    }

    /**
     * {@inheritDoc}
     */
    public function addImovel(\MyClasses\Entities\Imovel $imovel)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addImovel', array($imovel));

        return parent::addImovel($imovel);
    }

    /**
     * {@inheritDoc}
     */
    public function getImoveis()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getImoveis', array());

        return parent::getImoveis();
    }

    /**
     * {@inheritDoc}
     */
    public function getVisitas()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVisitas', array());

        return parent::getVisitas();
    }

    /**
     * {@inheritDoc}
     */
    public function addVisita(\MyClasses\Entities\Visita $visita)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addVisita', array($visita));

        return parent::addVisita($visita);
    }

    /**
     * {@inheritDoc}
     */
    public function addContrato(\MyClasses\Entities\Contrato $contrato)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addContrato', array($contrato));

        return parent::addContrato($contrato);
    }

    /**
     * {@inheritDoc}
     */
    public function getContratos()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getContratos', array());

        return parent::getContratos();
    }

    /**
     * {@inheritDoc}
     */
    public function sets(array $atributos)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'sets', array($atributos));

        return parent::sets($atributos);
    }

    /**
     * {@inheritDoc}
     */
    public function getImoveisPaginados($inicio = 0, $limite = 10)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getImoveisPaginados', array($inicio, $limite));

        return parent::getImoveisPaginados($inicio, $limite);
    }

}
