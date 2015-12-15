<?php
namespace Application\webservices;

use MyClasses\Conn\Conn,
	DoctrineModule\Authentication\Adapter\ObjectRepository,
    Zend\Authentication\AuthenticationService,
    XmlWriter,
    Doctrine\Common\Collections\Criteria
    /* Zend\Config\Config,
    Zend\Config\Writer\Xml */;

/**
 * Webservice to android app
 */
class gpa{
    private $em;

    /**
     * Test webservice
     * @return string
     */
    public function testa(){
        return "webservice funç~ionando...";
    }
    
    /**
     * Authenticate user by webservice
     * @param string $usuario
     * @param string $senha
     * @return array
     */
    public function autentica(string $login, string $senha){
        $adapter = new ObjectRepository();
        $auth = new AuthenticationService();
        $adapter->setOptions(array(
                                'object_manager' => Conn::getConn(),
                                'identity_class' => 'MyClasses\Entities\AclUsuario',
                                'identity_property' => 'login',
                                'credential_property' => 'senha'
                                )
                            );
        $adapter->setIdentityValue($login);
        $adapter->setCredentialValue(sha1($senha));
        $resultado = $auth->authenticate($adapter);
        if ($resultado->isValid()){
            foreach ($resultado->getIdentity()->getEquipes() as $equipe)
                $equipes[] = array($equipe->getId(), mb_convert_encoding($equipe->getPerfil(),'UTF-8'));
            return array(   0 => array(
                                    0 => array(
                                            $resultado->getIdentity()->getId(),
                                            mb_convert_encoding($resultado->getIdentity()->getNome(),'UTF-8')
                                        )
                                    ),
                            1 => $equipes);
            /* return base64_encode(
                        gzcompress(
                            serialize($resultado->getIdentity()))); */
        }else
            return array(0=>"login invalido");
        //throw new Exception('Exception with some special chars: Äßö');
    }
    
    /**
     * Sync the app with webservice.
     * Returns the ID's of new tasks
     * @param int $idUsuario
     * @param string $ultimaSincronizacao
     * @return array
     */
    public function sincroniza(int $idUsuario, string $ultimaSincronizacao){
        $usuario = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                                ->findOneBy(array("id"=>$idUsuario));
        foreach ($usuario->getEquipes() as $equipe)
            $idsEquipes[] = $equipe->getId();
        $ultimaSincronizacao = new \DateTime($ultimaSincronizacao);
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
        if ( in_array(1, $idsEquipes)) //if user is administrator
            $query->setParameter("status", array("aberta", "concluir", "arquivada", "excluir"));
        else            
            $query->setParameter("status", array("aberta", "concluida", "rejeitada", "arquivada", "excluir"));
        $projetosGeral = $query->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        $projetos = $query->getQuery()->getResult();
        foreach($projetos as $projeto){
            foreach($projeto->getTarefas() as $tarefa){
                switch ($tarefa->getStatus()){
                    case "concluida": 
                        $tarefa->setStatus("arquivada");
                        $tarefa->setModificado(); 
                        $this->getEm()->flush();
                        break;
                    case "rejeitada": 
                        $tarefa->setStatus("aberta"); 
                        $tarefa->setModificado();
                        $this->getEm()->flush();
                        break;
                    case "excluir":                         
                        if (!$tarefa->getUsuariosExclusaoSincronizada()->contains($usuario)
                            && in_array($tarefa->getProjeto()->getEquipe()->getId(), $idsEquipes)){
                                $tarefa->addUsuarioExclusaoSincronizada($usuario);
                                $this->getEm()->flush();
                        }
                        break;
                }
            }
        }
        $idsTarefas = null;
        $hoje = new \DateTime();
        $nSemanaHoje = date("w");
        $inicioSemana = new \DateTime("-".$nSemanaHoje." days");
        $fimSemana = new \DateTime("+".(6-$nSemanaHoje)." days");
        $atualizar[0] = false; //if true, signs to update personal projects
        $atualizar[1] = false; //if true, signs to update team projects
        $atualizar[2] = false; //if true, signs to update today projects
        $atualizar[3] = false; //if true, signs to update week projects
        $atualizar[4] = false; //if true, signs to update filed tasks
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
                if (!$atualizar[4] && $tarefa['status']=="concluida")
                    $atualizar[4] = true;
                $idsTarefas[] = $tarefa['id'];
            }
        }
        //finally delete task after notify all user's team
        $queryExcluir = $this->getEm()->createQueryBuilder();
        $queryExcluir->select("t")
            ->from("MyClasses\Entities\Tarefa", "t")
            ->where($queryExcluir->expr()->eq("t.status", "'excluir'"));
        $tarefasExcluir = $queryExcluir->getQuery()->getResult();
        foreach($tarefasExcluir as $tarefa){
            if ($tarefa->getUsuariosExclusaoSincronizada()->count() == $tarefa->getProjeto()->getEquipe()->getUsuarios()->count()){
                $this->getEm()->remove($tarefa);
                $this->getEm()->flush();
            }
        }
        if ($idsTarefas != null)
            return array(   0 => $idsTarefas,
                            1 => $this->getXmlArray($projetosGeral),
                            2 => $atualizar
                        );
        else return null;
    }
    
    /**
     * Conclude (administrator) or as for conclusion (colaborator) of a task
     * @param int $idUsuario
     * @param int $idTarefa
     * @param string $confirma
     * @return string
     */
    public function concluiTarefa(int $idUsuario, int $idTarefa, string $confirma){
        $usuario = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                                ->findOneBy(array("id"=>$idUsuario));
        $tarefa = $this->getEm()->getRepository('MyClasses\Entities\Tarefa')
                                ->findOneBy(array("id"=>$idTarefa));
        $equipeAdm = $this->getEm()->getRepository('MyClasses\Entities\AclPerfil')
                                ->findOneBy(array("id" => 1));
        if ($usuario->getEquipes()->contains($equipeAdm) || ($tarefa->getUsuario() == $usuario && $tarefa->getProjeto()->getUsuario() == $usuario) ){
            if ($confirma == "sim"){
                $tarefa->setStatus("concluida");
                $this->gravaComentario($idUsuario, $idTarefa, "TAREFA CONCLUIDA!!!");
                $resposta = "concluida";
            }elseif ($usuario->getEquipes()->contains($equipeAdm)){
                $tarefa->setStatus("rejeitada");
                $this->gravaComentario($idUsuario, $idTarefa, "REJEITADA CONCLUSAO!!!");
                $resposta = "rejeitada";
            }
        }else{
            $tarefa->setStatus("concluir");
            $this->gravaComentario($idUsuario, $idTarefa, "SOLICITA CONCLUSAO");
            $resposta = "concluir";
        }
        $tarefa->setModificado();
        $this->getEm()->persist($tarefa);
        $this->getEm()->flush();
        if ($tarefa->getId())
            return $resposta;
        else
            return null;
    }
    
    /**
     * Returns all user's projects
     * @param int $idUsuario
     * @param boolean $login
     * @return string
     */
    public function projetosPessoais(int $idUsuario, $forcarAtualizacao){
        $forcarAtualizacao = ($forcarAtualizacao == null) ? false : $forcarAtualizacao;
        $usuario = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                                ->findOneBy(array("id"=>$idUsuario));
        foreach ($usuario->getEquipes() as $equipe)
            $idsEquipes[] = $equipe->getId();
       /**
        * if 'getNovasTarefas' is True, there are updates of the task
        * TODO I have to improve this, because get the entire xml instead of just the updated part
        */ 
        $xmlProjetosPessoais = null;
        if ($forcarAtualizacao){
            $query = $this->getEm()->createQueryBuilder();
            /*if ($usuario->getEquipes()[0]->getPerfil()=="adm"){
                $query->select("p, up, t, ut, c, uc")
                    ->from("MyClasses\Entities\Projeto", "p")            
                    ->leftJoin("p.usuario", "up")
                    ->leftJoin("p.tarefas", "t")
                    ->leftJoin("t.usuario", "ut")
                    ->leftJoin("t.comentarios", "c")
                    ->leftJoin("c.usuario", "uc")
                    ->where(
                        $query->expr()->orX(
                            $query->expr()->eq("p.usuario", $usuario->getId()),
                            $query->expr()->isNull("p.usuario")
                        )
                    )
                    ->andWhere($query->expr()->in("t.status", ":status"));
                $criterio = new Criteria();
                //              criterio dos projetos do proprio administrador
                $criterio->where($criterio->expr()->eq('usuario', $usuario))
                //              criterio dos projetos das equipes (s/ usuario)
                        ->orWhere($criterio->expr()->isNull('usuario'));
                $projetosPessoais = $this->getEm()->getRepository('MyClasses\Entities\Projeto')->matching($criterio);            
            }else{*/
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
                                                $query->expr()->eq("p.usuario", $usuario->getId()),
                                                $query->expr()->eq("t.usuario", $usuario->getId()),
                                                $query->expr()->in("p.equipe", $idsEquipes)
                                                )
                        )
                        ->andWhere($query->expr()->in("t.status", ":status"));
                /*$projetosPessoais = $usuario->getProjetos();
                $idsProjetos[] = 0;
                foreach ($projetosPessoais as $projetoPessoal)
                    $idsProjetos[] = $projetoPessoal->getId();
                $tarefasCorrelatas = $usuario->getTarefasCorrelatas($idsProjetos);*/
            /*}*/
        if ( in_array(1, $idsEquipes)) //se faz parte da equipe ADM
            $query->setParameter("status", array("aberta", "concluir"));
        else            
            $query->setParameter("status", array("aberta", "concluida", "rejeitada"));
            $projetosPessoais = $query->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
            $xmlProjetosPessoais = $this->getXmlArray($projetosPessoais);
            /*$usuario->setNovasTarefas(false);//signs to webservice knows that the task is already updated
            $this->getEm()->flush();*/
        }
        return $xmlProjetosPessoais;
    }
    
    /**
     * Returns all the user's teams project
     * @param int $idUsuario
     * @return string
     */
    public function projetosEquipes(int $idUsuario, $forcarAtualizacao){
        $forcarAtualizacao = ($forcarAtualizacao == null) ? false : $forcarAtualizacao;
        $usuario = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                                ->findOneBy(array("id"=>$idUsuario));
       /**
        * if 'getNovasTarefas' is True, there are updates
        * TODO I have to improve this, because get the entire xml instead of just the updated part
        */ 
        $xmlProjetosEquipes = null;
        if ($forcarAtualizacao){
            foreach ($usuario->getEquipes() as $equipe)
                foreach ($equipe->getProjetos() as $projeto)
                    $projetosEquipes[] = $projeto;
            $xmlProjetosEquipes = $this->getXmlObjeto($projetosEquipes, null);
            /*$usuario->setNovasTarefas(false);//signs to webservice knows that the task is already updated
            $this->getEm()->flush();*/
        }
        return $xmlProjetosEquipes;
    }
    
    /**
     * Returns all today projects
     * @param int $idUsuario
     * @return string
     */
    public function projetosHoje(int $idUsuario, $forcarAtualizacao){
        $forcarAtualizacao = ($forcarAtualizacao == null) ? false : $forcarAtualizacao;
        $usuario = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                                ->findOneBy(array("id"=>$idUsuario));
        foreach ($usuario->getEquipes() as $equipe)
            $idsEquipes[] = $equipe->getId();
       /**
        * if 'getNovasTarefas' is True, there are updates
        * TODO I have to improve this, because get the entire xml instead of just the updated part
        */ 
        $xmlProjetosHoje = null;
        if ($forcarAtualizacao){
            $hoje = new \DateTime();
            $query = $this->getEm()->createQueryBuilder();
            $query->select("p, up, t, ut, c, uc")
                    ->from("MyClasses\Entities\Projeto", "p")
                    ->leftJoin("p.usuario", "up")
                    ->leftJoin("p.tarefas", "t")
                    ->leftJoin("t.usuario", "ut")
                    ->leftJoin("t.comentarios", "c")
                    ->leftJoin("c.usuario", "uc")
                    ->where($query->expr()->eq("t.vencimento", "'".$hoje->format("Y/m/d")."'"))
                    ->andWhere(
                        $query->expr()->orX(
                                $query->expr()->eq("t.usuario", $usuario->getId()),
                                $query->expr()->in("p.equipe", $idsEquipes)
                        )
                    )
                    ->andWhere($query->expr()->in("t.status", ":status"));
        if ( in_array(1, $idsEquipes)) //se faz parte da equipe ADM
            $query->setParameter("status", array("aberta", "concluir"));
        else            
            $query->setParameter("status", array("aberta", "concluida", "rejeitada"));
            /*$query = $this->getEm()->createQuery("SELECT p,t FROM MyClasses\Entities\Projeto p 
                                                JOIN p.tarefas t WHERE t.vencimento = :hoje")
                                ->setParameter("hoje", $hoje->format("Y-m-d 00:00:00"));*/
            $projetosHoje = $query->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
            $xmlProjetosHoje = $this->getXmlArray($projetosHoje);
            /*$usuario->setNovasTarefas(false);//signs to webservice knows that the task is already updated
            $this->getEm()->flush();*/
        }
        return $xmlProjetosHoje;
    }
    
    /**
     * Returns all the week projects
     * @param int $idUsuario
     * @return string
     */
    public function projetosSemana(int $idUsuario, $forcarAtualizacao){
        $forcarAtualizacao = ($forcarAtualizacao == null) ? false : $forcarAtualizacao;
        $usuario = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                                ->findOneBy(array("id"=>$idUsuario));
        foreach ($usuario->getEquipes() as $equipe)
            $idsEquipes[] = $equipe->getId();
       /**
        * if 'getNovasTarefas' is True, there are updates
        * TODO I have to improve this, because get the entire xml instead of just the updated part
        */ 
        $xmlProjetosSemana = null;
        if ($forcarAtualizacao){
            $nSemanaHoje = date("w");
            $inicioSemana = new \DateTime("-".$nSemanaHoje." days");
            $fimSemana = new \DateTime("+".(6-$nSemanaHoje)." days");
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
                ->andWhere($query->expr()->orX(
                                                $query->expr()->eq("t.usuario", $usuario->getId()),
                                                $query->expr()->in("p.equipe", $idsEquipes)
                                            )
                        )
                ->andWhere($query->expr()->in("t.status", ":status"));
            if ( in_array(1, $idsEquipes)) //if user is administrator
                $query->setParameter("status", array("aberta", "concluir"));
            else            
                $query->setParameter("status", array("aberta", "concluida", "rejeitada"));
            $projetos = $query->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
            $xmlProjetosSemana = $this->getXmlArray($projetos);
            /*$usuario->setNovasTarefas(false);//signs to webservice knows that the task is already updated
            $this->getEm()->flush();*/
        }
        return $xmlProjetosSemana;
    }
    
    /**
     * Returns all the filed tasks
     * @param int $idUsuario
     * @return string
     */
    public function tarefasArquivadas(int $idUsuario, $forcarAtualizacao){        
        $forcarAtualizacao = ($forcarAtualizacao == null) ? false : $forcarAtualizacao;
        $usuario = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                                ->findOneBy(array("id"=>$idUsuario));
        foreach ($usuario->getEquipes() as $equipe)
            $idsEquipes[] = $equipe->getId();
        $xmlTarefasArquivadas = null;
        if ($forcarAtualizacao){
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
                                            $query->expr()->eq("p.usuario", $usuario->getId()),
                                            $query->expr()->eq("t.usuario", $usuario->getId()),
                                            $query->expr()->in("p.equipe", $idsEquipes)
                                            )
                    )
                    ->andWhere($query->expr()->in("t.status", ":status"))
                    ->setParameter("status", array("concluida", "arquivada"));;
            $projetos = $query->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
            $xmlTarefasArquivadas = $this->getXmlArray($projetos);
        }
        return $xmlTarefasArquivadas;
    }
    
    /**
     * save comment of a task
     * @param int $idUsuario
     * @param int $idTarefa
     * @param String $textoComentario
     * @return array
     */
    public function gravaComentario(int $idUsuario, int $idTarefa, String $textoComentario){
        $usuarioComentario = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
            ->findOneBy(array("id"=>$idUsuario));
        $tarefa = $this->getEm()->getRepository('MyClasses\Entities\Tarefa')
            ->findOneBy(array("id"=>$idTarefa));
        $comentario = new \MyClasses\Entities\DialogoUsuarioTarefa();
        $comentario->setUsuario($usuarioComentario);
        $comentario->setTarefa($tarefa);
        $comentario->setComentario($textoComentario);
        $comentario->setData();
        $this->getEm()->persist($comentario);
        $this->getEm()->flush();
        if ($comentario->getId()){
            /*$equipe = $tarefa->getProjeto()->getEquipe();
            //if is a team project, notify all user's team that the task was updated
            if ($equipe != null)
                foreach ($equipe->getUsuarios() as $usuario)
                    $usuario->setNovasTarefas(true);
            $em->flush();*/
            $tarefa->setModificado();
            $this->getEm()->flush();            
            
            foreach ($usuarioComentario->getEquipes() as $equipe)
                $idsEquipes[] = $equipe->getId();
            $hoje = new \DateTime();
            $nSemanaHoje = date("w");
            $inicioSemana = new \DateTime("-".$nSemanaHoje." days");
            $fimSemana = new \DateTime("+".(6-$nSemanaHoje)." days");
            $atualizar[0] = false; //if true, signs to update personal projects
            $atualizar[1] = false; //if true, signs to update team projects
            $atualizar[2] = false; //if true, signs to update today projects
            $atualizar[3] = false; //if true, signs to update week projects
            if (!$atualizar[0] && $tarefa->getUsuario() == $usuarioComentario)
                $atualizar[0] = true;
            if (!$atualizar[1] && $tarefa->getProjeto()->getEquipe()!=null && in_array($tarefa->getProjeto()->getEquipe()->getId(), $idsEquipes))
                $atualizar[1] = true;
            if (!$atualizar[2] && $tarefa->getVencimento()->format('Y/m/d') == $hoje->format('Y/m/d'))
                $atualizar[2] = true;
            if (!$atualizar[3] && ($tarefa->getVencimento()->format('Y/m/d') >= $inicioSemana->format('Y/m/d')
                                    && $tarefa->getVencimento()->format('Y/m/d') <= $fimSemana->format('Y/m/d')) )
                $atualizar[3] = true;
            
            return array(
                            0 => '['.(string) $comentario->getData().']'.
                                '<i>'.mb_convert_encoding($usuarioComentario->getNome(),'UTF-8').'</i>: '.
                                mb_convert_encoding($comentario->getComentario(),'UTF-8'),
                            1 => $atualizar
            );
        }else
            return null;
        /*I don't know why this didn't work, somehow the XML got mixed
        array(   0 => $this->projetosPessoais($idUsuario, true),
                            1 => $this->projetosEquipes($idUsuario, true),
                            2 => $this->projetosHoje($idUsuario, true),
                            3 => $this->projetosSemana($idUsuario, true),
                            4 => '['.(string) $comentario->getData().']'.
                                '<i>'.$usuario->getNome().'</i>: '.
                                $comentario->getComentario()
            );*/
    }
    
    /**
     * Save Project
     * @param string $nomeProjeto
     * @param string $descricaoProjeto
     * @param string $vencimentoProjeto
     * @param int $equipeProjeto
     * @param int $idUsuario
     * @return boolean saved project
     */
    public function gravaProjeto(string $nomeProjeto, string $descricaoProjeto, string $vencimentoProjeto, int $equipeProjeto, int $idUsuario){
        $Projeto = new \MyClasses\Entities\Projeto();
        $Projeto->setNome($nomeProjeto);
        $Projeto->setDescricao($descricaoProjeto);
        $Projeto->setVencimento($vencimentoProjeto);
        $Projeto->setModificado();
        if ($equipeProjeto != null){
            $equipe = $this->getEm()->getRepository('MyClasses\Entities\AclPerfil')
                                    ->findOneBy(array("id" => $equipeProjeto));
            $Projeto->setEquipe($equipe);
        }
        if ($idUsuario != null){
            $usuario = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                                    ->findOneBy(array("id" => $idUsuario));
            $Projeto->setUsuario($usuario);
        }
        $this->getEm()->persist($Projeto);
        $this->getEm()->flush();
        if ($Projeto->getId()){
            return true;
        }else
            return false;
    }
    
    /**
     * Save task
     * @param int $id
     * @param string $nome
     * @param string $descricao
     * @param string $vencimento
     * @param int $projeto
     * @param int $responsavel
     * @return boolean saved task
     */
    public function gravaTarefa(int $id, string $nome, string $descricao, string $vencimento, int $projeto, int $responsavel){
        if ($id == null){
            $tarefa = new \MyClasses\Entities\Tarefa();            
            $tarefa->setStatus("aberta");
        }else            
            $tarefa = $this->getEm()->getRepository('MyClasses\Entities\Tarefa')
                                    ->findOneBy(array("id"=>$id));
        $tarefa->setNome($nome);
        $tarefa->setDescricao($descricao);
        $tarefa->setVencimento($vencimento);
        $projeto = $this->getEm()->getRepository('MyClasses\Entities\Projeto')
                                ->findOneBy(array("id" => $projeto));
        $tarefa->setProjeto($projeto);
        $responsavel = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                                ->findOneBy(array("id" => $responsavel));
        $tarefa->setUsuario($responsavel);
        $tarefa->setModificado();
        $this->getEm()->persist($tarefa);
        $this->getEm()->flush();
        if ($tarefa->getId())            
            return true;
        else
            return false;
    }
    
    /**
     * Delete task
     * @param int $idTarefa
     * @param int $idUsuario
     * @return array $atualizar
     */
    public function excluiTarefa(int $idTarefa, int $usuario){
        $tarefa = $this->getEm()->getRepository('MyClasses\Entities\Tarefa')
                                ->findOneBy(array("id"=>$idTarefa));
        $usuario = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                                ->findOneBy(array("id"=>$usuario));
        foreach ($usuario->getEquipes() as $equipe)
            $idsEquipes[] = $equipe->getId();
        $hoje = new \DateTime();
        $nSemanaHoje = date("w");
        $inicioSemana = new \DateTime("-".$nSemanaHoje." days");
        $fimSemana = new \DateTime("+".(6-$nSemanaHoje)." days");
        $atualizar[0] = false; //if true, signs to update personal projects
        $atualizar[1] = false; //if true, signs to update team projects
        $atualizar[2] = false; //if true, signs to update today projects
        $atualizar[3] = false; //if true, signs to update week projects
        if (!$atualizar[0] && $tarefa->getUsuario() == $usuario)
            $atualizar[0] = true;
        if (!$atualizar[1] && $tarefa->getProjeto()->getEquipe()!=null && in_array($tarefa->getProjeto()->getEquipe()->getId(), $idsEquipes))
            $atualizar[1] = true;
        if (!$atualizar[2] && $tarefa->getVencimento()->format('Y/m/d') == $hoje->format('Y/m/d'))
            $atualizar[2] = true;
        if (!$atualizar[3] && ($tarefa->getVencimento()->format('Y/m/d') >= $inicioSemana->format('Y/m/d')
                                && $tarefa->getVencimento()->format('Y/m/d') <= $fimSemana->format('Y/m/d')) )
            $atualizar[3] = true;
        /**
         * if is a team task, can not delete yet, because have to notify all the team first
        */
        if ($tarefa->getProjeto()->getEquipe()!=null){
            $tarefa->setModificado();
            $tarefa->setStatus("excluir");
            $this->getEm()->flush();
            return $atualizar;
        }else{
            $this->getEm()->remove($tarefa);
            $this->getEm()->flush();
            if (!$tarefa->getId())
                return $atualizar;
            else
                return null;
        }
    }
    
    /** 
     * @return String xml with teams
     */
    public function getEquipes(){
        $query = $this->getEm()->createQueryBuilder();
        $query->select("e")->from('MyClasses\Entities\AclPerfil', 'e')->orderBy('e.perfil', 'ASC');
        $equipes = $query->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
        return $this->getXmlArrayEquipes($equipes);
    }
    
    /** 
     * @param int $idUsuario
     * @param string $ultimaSincronizacao
     * @return String xml with projects
     */
    public function getProjetos(int $usuario, string $ultimaSincronizacao){
        $usuarioProjetos = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                                ->findOneBy(array("id" => $usuario));
        $equipeAdm = $this->getEm()->getRepository('MyClasses\Entities\AclPerfil')
                                ->findOneBy(array("id" => 1));        
        $ultimaSincronizacao = new \DateTime($ultimaSincronizacao);
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
                                                    $query->expr()->eq("p.usuario", $usuario),
                                                    $query->expr()->isNull("p.usuario")
                                                    )
                           );
            }else
                $query->Where($query->expr()->eq("p.usuario", $usuario));
            $projetos = $query->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
            return $this->getXmlArrayProjetos($projetos);
        }else
            return null;
    }
    
    /** 
     * @param string $ultimaSincronizacao
     * @return String xml with users
     */
    public function getUsuarios(string $ultimaSincronizacao){
        $ultimaSincronizacao = new \DateTime($ultimaSincronizacao);
        $query = $this->getEm()->createQueryBuilder();
        $query->select("u")
                ->from('MyClasses\Entities\AclUsuario', 'u')
                ->where($query->expr()->gt("u.modificado", "'".$ultimaSincronizacao->format("Y/m/d H:i:s")."'"))
                ->setMaxResults(1);
        if (count($query->getQuery()->getResult())>0){
            $query = $this->getEm()->createQueryBuilder();
            $query->select("u")->from('MyClasses\Entities\AclUsuario', 'u')->orderBy('u.nome', 'ASC');
            $usuarios = $query->getQuery()->getResult(\Doctrine\ORM\AbstractQuery::HYDRATE_ARRAY);
            return $this->getXmlArrayUsuarios($usuarios);
        }else
            return null;
    }
    
    /**
     * 
     * @param Doctrine\Common\ArrayCollection $projetos
     * @param Doctrine\Common\ArrayCollection $tarefas
     * @return string
     */
    private function getXmlObjeto($projetos, $tarefasCorrelatas){
        $xml = new XmlWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('projetos');
        foreach ($projetos as $projeto){
            $xml->startElement('projeto');
            $xml->writeAttribute('id',$projeto->getId());
            $xml->writeElement('nome',mb_convert_encoding($projeto->getNome(),'UTF-8'));
            if ($projeto->getEquipe()){
                $xml->startElement('equipe');
                $xml->writeAttribute('id',$projeto->getEquipe()->getId());
                $xml->text(mb_convert_encoding($projeto->getEquipe()->getPerfil(),'UTF-8'));
                $xml->endElement();
            }
            if ($projeto->getUsuario()){
                $xml->startElement('responsavel');
                $xml->writeAttribute('id',$projeto->getUsuario()->getId());
                $xml->text(mb_convert_encoding($projeto->getUsuario()->getNome(),'UTF-8'));
                $xml->endElement();
            }
            foreach ($projeto->getTarefas() as $tarefa){
                if ($tarefa->getStatus() != "arquivada"){
                    $xml->startElement('tarefa');
                    $xml->writeAttribute('id',$tarefa->getId());
                    $xml->writeElement('nome',mb_convert_encoding($tarefa->getNome(),'UTF-8'));
                    if ($tarefa->getUsuario()){
                        $xml->startElement('responsavel');
                        $xml->writeAttribute('id',$tarefa->getUsuario()->getId());
                        $xml->text(mb_convert_encoding($tarefa->getUsuario()->getNome(),'UTF-8'));
                        $xml->endElement();
                    }
                    $xml->writeElement('descricao',mb_convert_encoding($tarefa->getDescricao(),'UTF-8'));
                    if (!$tarefa->getComentarios()->isEmpty()){
                        $xml->startElement('comentarios');
                        foreach ($tarefa->getComentarios() as $comentario)
                            $xml->writeElement( 'comentario',
                                                '['.$comentario->getData().']'.
                                                '<i>'.mb_convert_encoding($comentario->getUsuario()->getNome(),'UTF-8').'</i>: '.
                                                mb_convert_encoding($comentario->getComentario(),'UTF-8'));
                        $xml->endElement();
                    }
                    $xml->writeElement('vencimento',$tarefa->getVencimento()->format("d/m/Y H:i:s"));
                    $xml->writeElement('status',mb_convert_encoding($tarefa->getStatus(),'UTF-8'));
                    $xml->endElement();
                }
            }
            $xml->endElement();
        }
        foreach ($tarefasCorrelatas as $tarefaCorrelata){
            if ($tarefaCorrelata->getStatus() != "arquivada"){
                $xml->startElement('projeto');
                $xml->writeAttribute('id',$tarefaCorrelata->getProjeto()->getId());
                $xml->writeElement('nome',mb_convert_encoding($tarefaCorrelata->getProjeto()->getNome(),'UTF-8'));
                $xml->startElement('tarefa');
                $xml->writeAttribute('id',$tarefaCorrelata->getId());
                $xml->writeElement('nome',mb_convert_encoding($tarefaCorrelata->getNome(),'UTF-8'));
                $xml->writeElement('descricao',mb_convert_encoding($tarefaCorrelata->getDescricao(),'UTF-8'));
                if (!$tarefaCorrelata->getComentarios()->isEmpty()){
                    $xml->startElement('comentarios');
                    foreach ($tarefaCorrelata->getComentarios() as $comentario)
                        $xml->writeElement( 'comentario',
                                '['.$comentario->getData().']'.
                                '<i>'.mb_convert_encoding($comentario->getUsuario()->getNome(),'UTF-8').'</i>: '.
                                $comentario->getComentario());
                    $xml->endElement();
                }
                $xml->writeElement('vencimento',$tarefaCorrelata->getVencimento());
                $xml->writeElement('status',mb_convert_encoding($tarefaCorrelata->getStatus(),'UTF-8'));
                $xml->endElement();
                $xml->endElement();
            }
        }
        $xml->endElement();
        $xml->endDocument();
        return $xml->outputMemory(true);
    }
    
    /**
     * 
     * @param array $projetos
     * @return string
     */
    private function getXmlArray($projetos){
        $xml = new XmlWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('projetos');
        foreach ($projetos as $projeto){
            $xml->startElement('projeto');
            $xml->writeAttribute('id',$projeto['id']);
            $xml->writeElement('nome',mb_convert_encoding($projeto['nome'],'UTF-8'));
            if (isset($projeto['equipe'])){
                $xml->startElement('equipe');
                $xml->writeAttribute('id',$projeto['equipe']['id']);
                $xml->text(mb_convert_encoding($projeto['equipe']['perfil'],'UTF-8'));
                $xml->endElement();
            }
            if (isset($projeto['usuario'])){
                $xml->startElement('responsavel');
                $xml->writeAttribute('id',$projeto['usuario']['id']);
                $xml->text(mb_convert_encoding($projeto['usuario']['nome'],'UTF-8'));
                $xml->endElement();
            }
            foreach ($projeto['tarefas'] as $tarefa){
                $xml->startElement('tarefa');
                $xml->writeAttribute('id',$tarefa['id']);
                $xml->writeElement('nome',mb_convert_encoding($tarefa['nome'],'UTF-8'));
                if ($tarefa['usuario'] != null){
                    $xml->startElement('responsavel');
                    $xml->writeAttribute('id',$tarefa['usuario']['id']);
                    $xml->text(mb_convert_encoding($tarefa['usuario']['nome'],'UTF-8'));
                    $xml->endElement();
                }
                $xml->writeElement('descricao',mb_convert_encoding($tarefa['descricao'],'UTF-8'));
                if ($tarefa['comentarios'] != null){
                    $xml->startElement('comentarios');
                    foreach ($tarefa['comentarios'] as $comentario)
                        $xml->writeElement( 'comentario',
                                            '['.$comentario['data']->format("d/m/Y H:i:s").']'.
                                            '<i>'.mb_convert_encoding($comentario['usuario']['nome'],'UTF-8').'</i>: '.
                                            mb_convert_encoding($comentario['comentario'],'UTF-8'));
                    $xml->endElement();
                }
                $xml->writeElement('vencimento',$tarefa['vencimento']->format("d/m/Y H:i:s"));
                $xml->writeElement('status',mb_convert_encoding($tarefa['status'],'UTF-8'));
                $xml->endElement();
            }
            $xml->endElement();
        }
        $xml->endElement();
        $xml->endDocument();
        return $xml->outputMemory(true);
    }
    
    /**
     * 
     * @param array $equipes
     * @return string
     */
    private function getXmlArrayEquipes($equipes){
        $xml = new XmlWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('equipes');
        foreach ($equipes as $equipe){
            $xml->startElement('equipe');
            $xml->writeAttribute('id',$equipe['id']);
            $xml->writeElement('nome',mb_convert_encoding($equipe['perfil'],'UTF-8'));
            $xml->endElement();
        }
        $xml->endElement();
        $xml->endDocument();
        return $xml->outputMemory(true);
    }
    
    /**
     * 
     * @param array $projetos
     * @return string
     */
    private function getXmlArrayProjetos($projetos){
        $xml = new XmlWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('projetos');
        foreach ($projetos as $projeto){
            $xml->startElement('projeto');
            $xml->writeAttribute('id',$projeto['id']);
            $xml->writeElement('nome',mb_convert_encoding($projeto['nome'],'UTF-8'));
            $xml->endElement();
        }
        $xml->endElement();
        $xml->endDocument();
        return $xml->outputMemory(true);
    }
    
    /**
     * 
     * @param array $usuarios
     * @return string
     */
    private function getXmlArrayUsuarios($usuarios){
        $xml = new XmlWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->startDocument('1.0', 'UTF-8');
        $xml->startElement('usuarios');
        foreach ($usuarios as $usuario){
            $xml->startElement('usuario');
            $xml->writeAttribute('id',$usuario['id']);
            $xml->writeElement('nome',mb_convert_encoding($usuario['nome'],'UTF-8'));
            $xml->endElement();
        }
        $xml->endElement();
        $xml->endDocument();
        return $xml->outputMemory(true);
    }
    
    /** @return Doctrine\ORM\EntityManager */
    private function getEm(){
        if (null === $this->em){
            $this->em = Conn::getConn();
        }
        return $this->em;
    }
       
}
?>