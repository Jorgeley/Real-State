<?php
/**
 * Controlador de Usuários
 */
namespace Application\Controller;

use Zend\View\Model\ViewModel,
    MyClasses\Conn\Conn,
    MyClasses\Entities\AclPerfil,
    MyClasses\Entities\AclUsuario,
    MyClasses\Entities\AclRecurso,
    MyClasses\Controllers\PadraoController;

class UsuarioController extends PadraoController{
    
    public function __construct(){
        parent::__construct();
    }
    
    /**
     * lista os usuários
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction(){
        $usuarios = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')->findAll();
        return new ViewModel(array('usuarios'=>$usuarios));
    }
    
    /**
     * formulário novo usuario
     * @return \Zend\View\Model\ViewModel
     */
    public function novoAction(){
        $perfis = $this->getEm()->getRepository('MyClasses\Entities\AclPerfil')->findAll();
        return new ViewModel(array("perfis"=>$perfis));
    }
    
    /**
     * visualiza usuario para edição
     * @return \Zend\View\Model\ViewModel
     */
    public function editaAction(){
        $usuario = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                ->findOneBy(array("id"=>$this->Params("id")));
        /* $idsProjetos[] = 0;
        foreach ($usuario->getProjetos() as $projeto)
            $idsProjetos[] = $projeto->getId();
        $tarefas = Conn::getConn()->createQuery("
                                        SELECT t FROM MyClasses\Entities\Tarefa t
                                        WHERE
                                            t.usuario = ".$usuario->getId()."
                                            AND
                                            t.projeto NOT IN (".implode(",", $idsProjetos).")"
                                        )->getResult();
        foreach ($tarefas as $tarefa)
            echo $tarefa->getNome()."<br>"; */
        return new ViewModel(array(
                "usuario"=>$usuario,
                "perfis"=>$this->getEm()->getRepository('MyClasses\Entities\AclPerfil')->findAll()
        ));
    }
    
    /**
     * exclui a usuario do BD
     * @return \Zend\View\Model\ViewModel
     */
    public function excluiAction(){
        $usuario = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                ->findOneBy(array("id"=>$this->Params("id")));
        $this->getEm()->remove($usuario);
        $this->getEm()->flush();
        return new ViewModel(array("id"=>$usuario->getId()));
    }
    
    /**
     * persiste usuario no BD
     * @return \Zend\View\Model\ViewModel
     */
    public function gravaAction(){
        if ($this->getRequest()->isPost()){
            if ($this->getRequest()->getPost("usuario")) //altera usuario
                $usuario = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                          ->findOneBy(array("id"=>$this->getRequest()->getPost("usuario")));
            else //novo usuario
                $usuario = new AclUsuario();
            $usuario->setNome($this->getRequest()->getPost("nome"));
            $usuario->setCargo($this->getRequest()->getPost("cargo"));
            $usuario->setLogin($this->getRequest()->getPost("login"));
            $usuario->setSenha($this->getRequest()->getPost("senha"));
            if ($this->getRequest()->getPost("acesso")=="a"){ //perfil adm
                $equipeAdm = $this->getEm()->getRepository('MyClasses\Entities\AclPerfil')
                        ->findOneBy(array("id"=>1));
                $usuario->addEquipe($equipeAdm);
            }
            $equipes = $this->getRequest()->getPost("equipe");
            if ($usuario->getEquipes()->count() != count($equipes))
                foreach ($usuario->getEquipes() as $equipe)
                    echo $usuario->delEquipe($equipe);
            $this->getEm()->persist($usuario);
            $this->getEm()->flush();
            foreach ($equipes as $equipe){                
                $equipe = $this->getEm()->getRepository('MyClasses\Entities\AclPerfil')
                    ->findOneBy(array("id"=>$equipe));
                $usuario->addEquipe($equipe);
            }
            /* if ($this->getRequest()->getPost("acessoprojeto")){
                $recursoProjeto = $this->getEm()->getRepository('MyClasses\Entities\AclRecurso')
                          ->findOneBy(array("recurso"=>"projeto"));
                if (!$perfil->getRecursos()->contains($recursoProjeto))
                    $perfil->addRecurso($recursoProjeto);
            } */
            $this->getEm()->persist($usuario);
            $this->getEm()->flush();
            return new ViewModel(array("id"=>$usuario->getId()));
        }
    }
    
}
?>