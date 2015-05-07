<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Zend\Soap\Client as SoapClient,
    Zend\Soap\AutoDiscover,
    Zend\Soap\Server as Soap,
    SoapFault,
    MyClasses\Conn\Conn,
    Doctrine\ORM\Query\ResultSetMappingBuilder,
	DoctrineModule\Authentication\Adapter\ObjectRepository,
    Zend\Authentication\AuthenticationService;

//ini_set("soap.wsdl_cache_enabled", "0"); // disabling WSDL cache
class WebserviceController extends AbstractActionController{
    //private static $SERVIDOR = "192.168.0.118:8888";
    //private static $SERVIDOR = "192.168.1.103:8888";
    private static $SERVIDOR = "http://www.grupo-gpa.com";
    // Armazena na variável o endereço do webserver no servidor
    //private $_WSDL_URI = "http://192.168.1.103:8888/WEB/GPA/public/webservice";
    private $_WSDL_URI = "http://www.grupo-gpa.com/webservice";
    private $em;
    
    public function indexAction(){
        $cliente = new SoapClient($this->_WSDL_URI.'/wsdl', array('encoding' => 'UTF-8'));
            return new ViewModel(array(
                                    'testa' => $cliente->testa(),
                                    /*'funcoes' => $cliente->getFunctions(),
                                    'cliente' => $cliente*/
                                ));
    }
    
    public function autenticaAction(){
        /*$adapter = new ObjectRepository();
        $auth = new AuthenticationService();
        $adapter->setOptions(array(
                                'object_manager' => Conn::getConn(),
                                'identity_class' => 'MyClasses\Entities\AclUsuario',
                                'identity_property' => 'login',
                                'credential_property' => 'senha'
                                )
                            );
        $adapter->setIdentityValue("jorgeley");
        $adapter->setCredentialValue(sha1("123"));
        $resultado = $auth->authenticate($adapter);
        if ($resultado->isValid()){
            foreach ($resultado->getIdentity()->getEquipes() as $equipe)
                $equipes[] = array($equipe->getId(), $equipe->getPerfil());
            print_r( array(   0 => array(
                                    0 => array(
                                            $resultado->getIdentity()->getId(),
                                            $resultado->getIdentity()->getNome()
                                        )
                                    ),
                            1 => $equipes));
        }else
            return 0;*/
        //try{
            $cliente = new SoapClient($this->_WSDL_URI.'/wsdl');
            //$usuario = unserialize(gzuncompress(base64_decode($cliente->autentica('jorgeley','123'))));
            $usuario = $cliente->autentica("jorgeley","123");
            $view = new ViewModel(array('autentica' => $usuario));
            $view->setTerminal(true);
            return $view;
        /*}catch (SoapFault $fault) {
            echo 'erro: ' . $fault->getMessage();
        }*/
    }
    
    public function sincronizaAction(){/*
        $usuario = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                                ->findOneBy(array("id"=>3));
        foreach ($usuario->getEquipes() as $equipe)
            $idsEquipes[] = $equipe->getId();
        $ultimaSincronizacao = new \DateTime("2015/01/01 09:00:00");
        $query = $this->getEm()->createQueryBuilder();
        $query->select("p, up, ep, t, ut, c, uc")
            ->from("MyClasses\Entities\Projeto", "p")            
            ->leftJoin("p.usuario", "up")
            ->leftJoin("p.equipe", "ep")
            ->leftJoin("p.tarefas", "t")
            ->leftJoin("t.usuario", "ut")
            ->leftJoin("t.comentarios", "c")
            ->leftJoin("c.usuario", "uc")
            ->where(
                $query->expr()->orX(
                    $query->expr()->eq("t.usuario", $usuario->getId()),
                    $query->expr()->in("p.equipe", $idsEquipes)
                )
            )
            ->andWhere($query->expr()->gt("t.modificado", "'".$ultimaSincronizacao->format("Y/m/d H:i:s")."'"))
            ->andWhere($query->expr()->in("t.status", ":status"));
        if ( in_array(1, $idsEquipes)) //se faz parte da equipe ADM
            $query->setParameter("status", array("aberta", "concluir", "arquivada"));
        else            
            $query->setParameter("status", array("aberta", "concluida", "rejeitada", "arquivada"));
        $projetosGeral = $query->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        $idsTarefas = null;
        $hoje = new \DateTime();
        $nSemanaHoje = date("w");
        $inicioSemana = new \DateTime("-".$nSemanaHoje." days");
        $fimSemana = new \DateTime("+".(6-$nSemanaHoje)." days");
        $atualizar[0] = false; //se true, sinaliza atualizar projetos pessoais
        $atualizar[1] = false; //se true, sinaliza atualizar projetos equipes
        $atualizar[2] = false; //se true, sinaliza atualizar projetos hoje
        $atualizar[3] = false; //se true, sinaliza atualizar projetos semana
        foreach ($projetosGeral as $projeto){
            if (!$atualizar[0] && $projeto['usuario']['id'] == $idUsuario)
                $atualizar[0] = true;
            if (!$atualizar[1] && in_array($projeto['equipe']['id'], $idsEquipes))
                $atualizar[1] = true;
            foreach ($projeto['tarefas'] as $tarefa){
                if (!$atualizar[2] && $tarefa['vencimento']->format('Y/m/d') == $hoje->format('Y/m/d'))
                    $atualizar[2] = true;
                if (!$atualizar[3] && ($tarefa['vencimento']->format('Y/m/d') >= $inicioSemana->format('Y/m/d')
                                        && $tarefa['vencimento']->format('Y/m/d') <= $fimSemana->format('Y/m/d')) )
                    $atualizar[3] = true;
                $idsTarefas[] = $tarefa['id'];
            }
        }
        if ($idsTarefas != null)
            print_r( array(   0 => $idsTarefas,
                            1 => $projetosGeral,
                            2 => $atualizar
                        ));
        else return null;*/
        $cliente = new SoapClient($this->_WSDL_URI.'/wsdl');
        $idsTarefas = $cliente->sincroniza(1, "2015/01/01 09:00:00");
        return new ViewModel(array('idsTarefas' => $idsTarefas));
    }    
    
    public function concluitarefaAction(){
        $cliente = new SoapClient($this->_WSDL_URI.'/wsdl');
        $resposta = $cliente->concluiTarefa(1, 37);
        return new ViewModel(array('resposta' => $resposta));
    }
    
    public function projetosAction(){
        $cliente = new SoapClient($this->_WSDL_URI.'/wsdl');
        //return new ViewModel(array("dados"=>$cliente->tarefas(1)));
        $response = $this->getResponse();
        $response->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
        $xml = $response->setContent($cliente->projetosPessoais(1, true));
        return $xml;
    }
    
    public function projetosequipesAction(){
        $cliente = new SoapClient($this->_WSDL_URI.'/wsdl');
        //return new ViewModel(array("dados"=>$cliente->tarefas(1)));
        $response = $this->getResponse();
        $response->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
        $xml = $response->setContent($cliente->projetosEquipes(1, true));
        return $xml;
    }
    
    public function projetoshojeAction(){
        $cliente = new SoapClient($this->_WSDL_URI.'/wsdl');
        //return new ViewModel(array("dados"=>$cliente->tarefas(1)));
        $response = $this->getResponse();
        $response->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
        $xml = $response->setContent($cliente->projetosHoje(1, true));
        return $xml;
    }
    
    public function projetossemanaAction(){
        $cliente = new SoapClient($this->_WSDL_URI.'/wsdl');
        //return new ViewModel(array("dados"=>$cliente->tarefas(1)));
        $response = $this->getResponse();
        $response->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
        $xml = $response->setContent($cliente->projetosSemana(1, true));
        return $xml;
        /*$nSemanaHoje = date("w");
        $inicioSemana = new \DateTime("-".$nSemanaHoje." days");
        $fimSemana = new \DateTime("+".(6-$nSemanaHoje)." days");
        $usuario = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                                ->findOneBy(array("id"=>3));
        foreach ($usuario->getEquipes() as $equipe)
            $idsEquipes[] = $equipe->getId();
        $query = $this->getEm()->createQueryBuilder();
        $query->select("p, up, t, ut, c, uc")
                ->from("MyClasses\Entities\Projeto", "p")
                ->leftJoin("p.usuario", "up")
                ->leftJoin("p.tarefas", "t")
                ->leftJoin("t.usuario", "ut")
                ->leftJoin("t.comentarios", "c")
                ->leftJoin("c.usuario", "uc")
            ->where($query->expr()->between("t.vencimento", 
                                                "'".$inicioSemana->format("Y/m/d")."'", 
                                                "'".$fimSemana->format("Y/m/d")."'"
                                           )
                   )
            ->andWhere(
                    $query->expr()->orX(
                            $query->expr()->eq("t.usuario", $usuario->getId()),
                            $query->expr()->in("p.equipe", $idsEquipes)
                    )
        );
        echo $query->getQuery()->getSql();
        $projetos = $query->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        print_r($projetos);*/
    }
    
    public function tarefasarquivadasAction(){$cliente = new SoapClient($this->_WSDL_URI.'/wsdl');
        //return new ViewModel(array("dados"=>$cliente->tarefas(1)));
        $response = $this->getResponse();
        $response->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
        $xml = $response->setContent($cliente->tarefasArquivadas(1, true));
        return $xml;
    }

    public function gravacomentarioAction(){
        /*$em = Conn::getConn();
        $usuarioComentario = $em->getRepository('MyClasses\Entities\AclUsuario')
            ->findOneBy(array("id"=>1));
        $tarefa = $em->getRepository('MyClasses\Entities\Tarefa')
            ->findOneBy(array("id"=>43));        
        $comentario = new \MyClasses\Entities\DialogoUsuarioTarefa();
        $comentario->setUsuario($usuarioComentario);
        $comentario->setTarefa($tarefa);
        $comentario->setComentario("comentario de exemplo");
        $comentario->setData();
        $em->persist($comentario);
        $em->flush();
        if ($comentario->getId()){
            $equipe = $tarefa->getProjeto()->getEquipe();
            //se o projeto tiver equipe, notificar todos os usuarios que a tarefa foi atualizada
            if ($equipe != null)
                foreach ($equipe->getUsuarios() as $usuario){
                    echo $usuario->getNome();
                    $usuario->setNovasTarefas(true);
                }
        }else echo "sem id";
        $em->flush();*/
        $cliente = new SoapClient($this->_WSDL_URI.'/wsdl');
        $comentario = $cliente->gravacomentario(1,37,"comentario de exemplo ");
        return new ViewModel(array('comentario' => $comentario));
    }
    
    public function gravaprojetoAction(){
        /*$query = $this->getEm()->createQueryBuilder();
        $query->select("e")->from('MyClasses\Entities\AclPerfil', 'e')->orderBy('e.perfil', 'ASC');
        return new ViewModel(array('equipes' => $query->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY)));*/
        $cliente = new SoapClient($this->_WSDL_URI.'/wsdl');
        new ViewModel(array('projeto' => $cliente->gravaProjeto("Projeto exemplo", 
                                                                "Projeto exemplo", 
                                                                "2015/12/31", 
                                                                null,
                                                                3)));
    }
    
    public function gravatarefaAction(){
        /*$id = 37;
        if ($id == null)
            $tarefa = new \MyClasses\Entities\Tarefa();
        else            
            $tarefa = $this->getEm()->getRepository('MyClasses\Entities\Tarefa')
                                    ->findOneBy(array("id"=>$id));
        $tarefa->setNome("Tarefa exemplo");
        $tarefa->setDescricao("Tarefa exemplo");
        $tarefa->setVencimento("2015/12/31");
        $projeto = $this->getEm()->getRepository('MyClasses\Entities\Projeto')
                                ->findOneBy(array("id" => 9));
        $tarefa->setProjeto($projeto);
        $responsavel = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                                ->findOneBy(array("id" => 1));
        $tarefa->setUsuario($responsavel);
        $tarefa->setModificado();
        $tarefa->setStatus("aberta");
        $this->getEm()->persist($tarefa);
        $this->getEm()->flush();
        if ($tarefa->getId())            
            return new ViewModel(array('tarefa' => true));
        else
            return new ViewModel(array('tarefa' => false));*/
        $cliente = new SoapClient($this->_WSDL_URI.'/wsdl');
        return new ViewModel(array('tarefa' => $cliente->gravaTarefa(null,
                                                              "Tarefa exemplo", 
                                                              "Tarefa exemplo", 
                                                              "2015/12/31", 
                                                              9,
                                                              1)));
    }
    
    public function excluitarefaAction(){
        $query = $this->getEm()->createQueryBuilder();
        $query->select("t")
            ->from("MyClasses\Entities\Tarefa", "t")
            ->where($query->expr()->eq("t.status", "'excluir'"));
        $tarefasExcluir = $query->getQuery()->getResult();
        foreach($tarefasExcluir as $tarefa){
            echo "count getUsuariosExclusaoSincronizada: ".$tarefa->getUsuariosExclusaoSincronizada()->count();
            echo "count equipe.usuarios: ".$tarefa->getProjeto()->getEquipe()->getUsuarios()->count();
            if ($tarefa->getUsuariosExclusaoSincronizada()->count() == $tarefa->getProjeto()->getEquipe()->getUsuarios()->count()){
                $this->getEm()->remove($tarefa);
                $this->getEm()->flush();
            }else echo "nao removido";
        }
        /*$cliente = new SoapClient($this->_WSDL_URI.'/wsdl');
        return new ViewModel(array('atualizar' => $cliente->excluiTarefa(43, 1)));*/
    }
    
    public function getequipesAction(){
        /*$query = $this->getEm()->createQueryBuilder();
        $query->select("e")->from('MyClasses\Entities\AclPerfil', 'e')->orderBy('e.perfil', 'ASC');
        return new ViewModel(array('equipes' => $query->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY)));*/
        $cliente = new SoapClient($this->_WSDL_URI.'/wsdl');
        $response = $this->getResponse();
        $response->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
        $xml = $response->setContent($cliente->getEquipes());
        return $xml;
    }    
 
    public function getprojetosAction(){        
        /*$usuarioProjetos = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                                ->findOneBy(array("id" => 1));
        $equipeAdm = $this->getEm()->getRepository('MyClasses\Entities\AclPerfil')
                                ->findOneBy(array("id" => 1));        
        $ultimaSincronizacao = new \DateTime("2025/01/01 09:00:00");
        $query = $this->getEm()->createQueryBuilder();
        $query->select("p")
                ->from('MyClasses\Entities\Projeto', 'p')
                ->where($query->expr()->gt("p.modificado", "'".$ultimaSincronizacao->format("Y/m/d H:i:s")."'"))
                ->setMaxResults(1);
        if (count($query->getQuery()->getResult())>0){
            $query = $this->getEm()->createQueryBuilder();
            $query->select("p")->from('MyClasses\Entities\Projeto', 'p');
            if ($usuarioProjetos->getEquipes()->contains($equipeAdm)) {
                    $query->Where($query->expr()->orX(
                                                    $query->expr()->eq("p.usuario", 1),
                                                    $query->expr()->isNull("p.usuario")
                                                    )
                           );
            }else
                $query->Where($query->expr()->eq("p.usuario", 1));
            print_r($query->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY));
        }else echo "nenhum projeto novo";*/
        /*$rsm = new ResultSetMappingBuilder($this->getEm());
        $rsm->addEntityResult("MyClasses\Entities\Projeto", "p");
        $rsm->addFieldResult("p", "id", "id");
        $rsm->addFieldResult("p", "nome", "nome");
        $sql = "SELECT 
                    IF ((modificado > '".$ultimaSincronizacao->format("Y/m/d H:i:s")."'), 
                        id, 
                        NULL) 
                    AS id,
                    IF ((modificado > '".$ultimaSincronizacao->format("Y/m/d H:i:s")."'), 
                        nome, 
                        NULL) 
                    AS nome
                FROM Projeto
                WHERE usuario_id=1";
        if ($usuarioProjetos->getEquipes()->contains($equipeAdm)) {
            $sql .=" OR usuario_id IS NULL";
        }
        $query = $this->getEm()->createNativeQuery($sql." ORDER BY nome ASC", $rsm);
        echo $query->getSql();
        print_r($query->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY));*/
        $cliente = new SoapClient($this->_WSDL_URI.'/wsdl');
        $response = $this->getResponse();
        $response->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
        $xml = $response->setContent($cliente->getProjetos(1, "2015/01/01 09:00:00"));
        return $xml;
    }
    
    public function getusuariosAction(){
        /*$query = $this->getEm()->createQueryBuilder();
        $query->select("e")->from('MyClasses\Entities\AclPerfil', 'e')->orderBy('e.perfil', 'ASC');
        return new ViewModel(array('equipes' => $query->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY)));*/
        $cliente = new SoapClient($this->_WSDL_URI.'/wsdl');
        $response = $this->getResponse();
        $response->getHeaders()->addHeaders(array('Content-type' => 'text/xml'));
        $xml = $response->setContent($cliente->getUsuarios("2015/01/01 09:00:00"));
        return $xml;
    }
    
    public function soapAction(){
        $soap = new Soap(null, array('uri' => $this->_WSDL_URI."/wsdl", 'encoding' => 'UTF-8'));
        $soap->setClass('\Application\Webservices\gpa');
        $soap->setEncoding('UTF-8'); 
        $soap->registerFaultException(array('Exception'));
        $soap->handle();
        return $this->setTerminal();
    }
    
    public function WsdlAction() {
        $wsdl = new AutoDiscover();
        $wsdl->setClass('\Application\Webservices\gpa')
            ->setUri($this->_WSDL_URI.'/soap');
            //print_r($wsdl);
        $wsdl->handle();
        
        /* $wsdl = $wsdl->generate();
        $wsdl = $wsdl->toDomDocument();
        // geramos o XML dando um echo no $wsdl->saveXML() 
        echo $wsdl->saveXML();
            
        header('Content-type: application/xml');
        echo $wsdl->toXml();
        exit();*/
        return $this->setTerminal();
    }
    
    private function setTerminal(){
        $view = new ViewModel();
        $view->setTerminal(true);
        return $view;
    }
    
    private function setTerminalDados($dados){
        $view = new ViewModel(array("dados"=>$dados));
        $view->setTerminal(true);
        return $view;
    }
    
    /** @return Doctrine\ORM\EntityManager */
    private function getEm(){
        if (null === $this->em){
            $this->em = Conn::getConn();
        }
        return $this->em;
    }

}