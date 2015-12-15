<?php
/**
 * Projects Controller
 */
namespace Application\Controller;

use Zend\View\Model\ViewModel,
    Doctrine\Common\Collections\Criteria,
    MyClasses\Controllers\PadraoController,
    MyClasses\Entities\Projeto;

class ProjetoController extends PadraoController{
    
    public function __construct(){
        parent::__construct();
    }
    
    /**
     * list the projects
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction(){
        $usuario = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                    ->findOneBy(array("id"=>$this->identity()[0]->getId()));
        if ($this->identity()[1]=="adm"){
            $criterio = new Criteria();
            $criterio->where($criterio->expr()->eq('usuario', $usuario))
                    ->orWhere($criterio->expr()->isNull('usuario'));
            $projetos = $this->getEm()->getRepository('MyClasses\Entities\Projeto')->matching($criterio);
        }else        
            $projetos = $usuario->getProjetos();
        return new ViewModel(array('projetos'=>$projetos));
    }
    
    /**
     * new project form
     * @return \Zend\View\Model\ViewModel
     */
    public function novoAction(){
        $equipes = $this->getEm()->getRepository('MyClasses\Entities\AclPerfil')->findAll();
        return new ViewModel(array("equipes"=>$equipes));
    }
    
    /**
     * view the project to edit
     * @return \Zend\View\Model\ViewModel
     */
    public function editaAction(){
        $projeto = $this->getEm()->getRepository('MyClasses\Entities\Projeto')
                    ->findOneBy(array("id"=>$this->Params("id")));
        return new ViewModel(array(
                "projeto"=>$projeto,
                "equipes"=>
                    $this->getEm()->getRepository('MyClasses\Entities\AclPerfil')->findAll()));
    }
    
    /**
     * delete project
     * @return \Zend\View\Model\ViewModel
     */
    public function excluiAction(){
        $projeto = $this->getEm()->getRepository('MyClasses\Entities\Projeto')
                    ->findOneBy(array("id"=>$this->Params("id")));
        if ($projeto->getEquipe()!=null)
            foreach ($projeto->getTarefas as $tarefa){
                //$tarefa->getUsuario()->setNovasTarefas(true);
                $tarefa->setModificado();
                $tarefa->setStatus("excluir");
            }
        else    
            $this->getEm()->remove($projeto);
        $this->getEm()->flush();
        return new ViewModel(array("id"=>$projeto->getId()));
    }
    
    /**
     * save the project
     * @return \Zend\View\Model\ViewModel
     */
    public function gravaAction(){
        if ($this->getRequest()->isPost()){
            if ($this->getRequest()->getPost("projeto")) //update project
                $projeto = $this->getEm()->getRepository('MyClasses\Entities\Projeto')
                          ->findOneBy(array("id"=>$this->getRequest()->getPost("projeto")));
            else //new project
                $projeto = new Projeto();
            $projeto->setNome($this->getRequest()->getPost("nome"));
            $projeto->setDescricao($this->getRequest()->getPost("descricao"));
            $data = $this->getRequest()->getPost("vencimento");
            if (strpos($data, "/") == 2)
                $data = substr($data, 6, 4)."/".
                        substr($data, 3, 2)."/".
                        substr($data, 0, 2);
            $projeto->setVencimento($data);
            $projeto->setModificado();
            /* $usuario = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                        ->findOneBy(array("id"=>$this->getRequest()->getPost("responsavel")));
            $projeto->setResponsavel($usuario); */
            if ($this->getRequest()->getPost("equipe")){
                $equipe = $this->getEm()->getRepository('MyClasses\Entities\AclPerfil')
                    ->findOneBy(array("id"=>$this->getRequest()->getPost("equipe")));
                $projeto->setEquipe($equipe);
                /*foreach ($projeto->getTarefas() as $tarefa)
                    $tarefa->setModificado();*/
                /*//notificar todos os usuarios da equipe que a tarefa foi atualizada
                foreach ($equipe->getUsuarios() as $usuario)
                    $usuario->setNovasTarefas(true);*/
            }else{
                $projeto->setUsuario($this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                          ->findOneBy(array("id"=>$this->identity()[0]->getId())));
                //$projeto->getUsuario()->setNovasTarefas(true);//flag to webservice knows that there are update
            }
            $this->getEm()->persist($projeto);
            $this->getEm()->flush();
            return new ViewModel(array("id"=>$projeto->getId()));
        }
    }
    
}
?>