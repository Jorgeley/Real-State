<?php

namespace MyClasses\Entities\Proxies\__CG__\MyClasses\Entities;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Projeto extends \MyClasses\Entities\Projeto implements \Doctrine\ORM\Proxy\Proxy
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
            return array('__isInitialized__', '' . "\0" . 'MyClasses\\Entities\\Projeto' . "\0" . 'id', '' . "\0" . 'MyClasses\\Entities\\Projeto' . "\0" . 'nome', '' . "\0" . 'MyClasses\\Entities\\Projeto' . "\0" . 'descricao', '' . "\0" . 'MyClasses\\Entities\\Projeto' . "\0" . 'vencimento', '' . "\0" . 'MyClasses\\Entities\\Projeto' . "\0" . 'equipe', '' . "\0" . 'MyClasses\\Entities\\Projeto' . "\0" . 'usuario', '' . "\0" . 'MyClasses\\Entities\\Projeto' . "\0" . 'tarefas');
        }

        return array('__isInitialized__', '' . "\0" . 'MyClasses\\Entities\\Projeto' . "\0" . 'id', '' . "\0" . 'MyClasses\\Entities\\Projeto' . "\0" . 'nome', '' . "\0" . 'MyClasses\\Entities\\Projeto' . "\0" . 'descricao', '' . "\0" . 'MyClasses\\Entities\\Projeto' . "\0" . 'vencimento', '' . "\0" . 'MyClasses\\Entities\\Projeto' . "\0" . 'equipe', '' . "\0" . 'MyClasses\\Entities\\Projeto' . "\0" . 'usuario', '' . "\0" . 'MyClasses\\Entities\\Projeto' . "\0" . 'tarefas');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Projeto $proxy) {
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
    public function setEquipe(\MyClasses\Entities\AclPerfil $equipe)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEquipe', array($equipe));

        return parent::setEquipe($equipe);
    }

    /**
     * {@inheritDoc}
     */
    public function getEquipe()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEquipe', array());

        return parent::getEquipe();
    }

    /**
     * {@inheritDoc}
     */
    public function setUsuario(\MyClasses\Entities\AclUsuario $usuario)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setUsuario', array($usuario));

        return parent::setUsuario($usuario);
    }

    /**
     * {@inheritDoc}
     */
    public function getUsuario()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUsuario', array());

        return parent::getUsuario();
    }

    /**
     * {@inheritDoc}
     */
    public function addTarefa(\MyClasses\Entities\Tarefa $tarefa)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addTarefa', array($tarefa));

        return parent::addTarefa($tarefa);
    }

    /**
     * {@inheritDoc}
     */
    public function getTarefas()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTarefas', array());

        return parent::getTarefas();
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
    public function getDescricao()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDescricao', array());

        return parent::getDescricao();
    }

    /**
     * {@inheritDoc}
     */
    public function getVencimento()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVencimento', array());

        return parent::getVencimento();
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
    public function setDescricao($descricao)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDescricao', array($descricao));

        return parent::setDescricao($descricao);
    }

    /**
     * {@inheritDoc}
     */
    public function setVencimento($vencimento)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setVencimento', array($vencimento));

        return parent::setVencimento($vencimento);
    }

    /**
     * {@inheritDoc}
     */
    public function sets(array $atributos)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'sets', array($atributos));

        return parent::sets($atributos);
    }

}
