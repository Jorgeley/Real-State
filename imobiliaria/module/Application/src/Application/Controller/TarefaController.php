<?php
/**
 * Task Controller
 */
namespace Application\Controller;

use Zend\View\Model\ViewModel,
    Doctrine\Common\Collections\Criteria,
    MyClasses\Controllers\PadraoController,
    MyClasses\Entities\Tarefa;

class TarefaController extends PadraoController{
    
    public function __construct(){
        parent::__construct();
    }
    
    /**
     * list tasks
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction(){
        $tarefasPessoais = null;
        $usuario = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                    ->findOneBy(array("id"=>$this->identity()[0]->getId()));
        if ($this->identity()[1]=="adm"){
            $criterio = new Criteria();
            $criterio->where($criterio->expr()->eq('usuario', $usuario))
                    ->orWhere($criterio->expr()->isNull('usuario'));
            $projetos = $this->getEm()->getRepository('MyClasses\Entities\Projeto')->matching($criterio);
        }else{
            $projetos = $usuario->getProjetos();
            $idsProjetos[] = 0;
            foreach ($projetos as $projeto)
                $idsProjetos[] = $projeto->getId();
            $tarefasPessoais = $usuario->getTarefasCorrelatas($idsProjetos);
            //echo $tarefasPessoais->count();
            //$tarefasEquipe = $usuario;//TODO get the user's team tasks
        }
        return new ViewModel(array('projetos'=>$projetos, 'tarefasPessoais'=>$tarefasPessoais));
    }
    
    /**
     * new task form
     * @return \Zend\View\Model\ViewModel
     */
    public function novoAction(){
        if ($this->identity()[1]=="adm")
            return new ViewModel(array(
                "usuarios"=>$this->getEm()->getRepository('MyClasses\Entities\AclUsuario')->findAll(),
                "projetos"=>$this->getEm()->getRepository('MyClasses\Entities\Projeto')->findAll()
            ));
        else{
            $usuario = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                    ->findOneBy(array("id"=>$this->identity()[0]->getId()));
            return new ViewModel(array(
                    "projetos"=>$usuario->getProjetos()
            ));
        }
    }
    
    /**
     * view the task to edit
     * @return \Zend\View\Model\ViewModel
     */
    public function editaAction(){
        $tarefa = $this->getEm()->getRepository('MyClasses\Entities\Tarefa')
                ->findOneBy(array("id"=>$this->Params("id")));
        return new ViewModel(array(
                "tarefa"=>$tarefa,
                "usuarios"=>$this->getEm()->getRepository('MyClasses\Entities\AclUsuario')->findAll(),
                "projetos"=>$this->getEm()->getRepository('MyClasses\Entities\Projeto')->findAll()
        ));
    }
    
    /**
     * delete task
     * @return \Zend\View\Model\ViewModel
     */
    public function excluiAction(){
        $tarefa = $this->getEm()->getRepository('MyClasses\Entities\Tarefa')
                ->findOneBy(array("id"=>$this->Params("id")));
        if ($tarefa->getProjeto()->getEquipe()!=null){
            $tarefa->setModificado();
            $tarefa->setStatus("excluir");
        }else
            /*$tarefa->getUsuario()->setNovasTarefas(true);*/
            $this->getEm()->remove($tarefa);
        $this->getEm()->flush();
        return new ViewModel(array("id"=>$tarefa->getId()));
    }
    
    /**
     * save task
     * @return \Zend\View\Model\ViewModel
     */
    public function gravaAction(){
        if ($this->getRequest()->isPost()){
            if ($this->getRequest()->getPost("tarefa")) //update task
                $tarefa = $this->getEm()->getRepository('MyClasses\Entities\Tarefa')
                          ->findOneBy(array("id"=>$this->getRequest()->getPost("tarefa")));
            else //new task
                $tarefa = new Tarefa();
            $tarefa->setNome($this->getRequest()->getPost("nome"));
            $tarefa->setDescricao($this->getRequest()->getPost("descricao"));
            $data = $this->getRequest()->getPost("vencimento");
            if (strpos($data, "/") == 2)
                $data = substr($data, 6, 4)."/".
                        substr($data, 3, 2)."/".
                        substr($data, 0, 2);
            $tarefa->setVencimento($data);
            if ($this->identity()[1]=="adm" && $this->getRequest()->getPost("responsavel"))
                $usuario = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                                        ->findOneBy(array("id"=>$this->getRequest()->getPost("responsavel")));
            else
                $usuario = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                                    ->findOneBy(array("id"=>$this->identity()[0]->getId()));
            /*$usuario->setNovasTarefas(true);//flag to webservice knows that there are updates*/
            $tarefa->setUsuario($usuario);
            $projeto = $this->getEm()->getRepository('MyClasses\Entities\Projeto')
                        ->findOneBy(array("id"=>$this->getRequest()->getPost("projeto")));
            $tarefa->setProjeto($projeto);
            $tarefa->setModificado();
            $tarefa->setStatus("aberta");
            /*//if is a team task, notify the user's team that the task was updated
            if ($projeto->getEquipe() != null)
                foreach ($projeto->getEquipe()->getUsuarios() as $usuario)
                    $usuario->setNovasTarefas(true);*/
            $this->getEm()->persist($tarefa);
            $this->getEm()->flush();
            return new ViewModel(array("id"=>$tarefa->getId()));
        }
    }
    
}
?>