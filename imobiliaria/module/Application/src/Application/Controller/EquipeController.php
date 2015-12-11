<?php
/**
 * Team controller
 */
namespace Application\Controller;

use Zend\View\Model\ViewModel,
    MyClasses\Conn\Conn,
    MyClasses\Entities\AclPerfil,
    MyClasses\Entities\AclUsuario,
    MyClasses\Entities\AclRecurso,
    MyClasses\Controllers\PadraoController;

class EquipeController extends PadraoController{
    
    public function __construct(){
        parent::__construct();
    }
    
    /**
     * list the teams
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction(){
        $perfis = $this->getEm()->getRepository('MyClasses\Entities\AclPerfil')->findAll();
        return new ViewModel(array('perfis'=>$perfis));
    }
    
    /**
     * form new team
     * @return \Zend\View\Model\ViewModel
     */
    public function novoAction(){
        $usuarios = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')->findAll();
        return new ViewModel(array("usuarios"=>$usuarios));
    }
    
    /**
     * view team for edit
     * @return \Zend\View\Model\ViewModel
     */
    public function editaAction(){
        $equipe = $this->getEm()->getRepository('MyClasses\Entities\AclPerfil')
                    ->findOneBy(array("id"=>$this->Params("id")));
        $usuarios = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')->findAll();
        return new ViewModel(array(
                "equipe"=>$equipe,
                "usuarios"=>$usuarios
        ));
    }
    
    /**
     * delete team
     * @return \Zend\View\Model\ViewModel
     */
    public function excluiAction(){
        $equipe = $this->getEm()->getRepository('MyClasses\Entities\AclPerfil')
                    ->findOneBy(array("id"=>$this->Params("id")));
        $this->getEm()->remove($equipe);
        $this->getEm()->flush();
        return new ViewModel(array("id"=>$equipe->getId()));
    }
    
    /**
     * save the team
     * @return \Zend\View\Model\ViewModel
     */
    public function gravaAction(){
        if ($this->getRequest()->isPost()){
            if ($this->getRequest()->getPost("equipe")) //update team
                $equipe = $this->getEm()->getRepository('MyClasses\Entities\AclPerfil')
                            ->findOneBy(array("id"=>$this->getRequest()->getPost("equipe")));
            else //new team
                $equipe = new AclPerfil();
            $equipe->setPerfil($this->getRequest()->getPost("nome"));
            //set profile for team's users
            $usuariosEquipe = $this->getRequest()->getPost("usuariosequipe");
            foreach ($usuariosEquipe as $usuarioEquipe){
                $usuario = $this->getEm()->getRepository('MyClasses\Entities\AclUsuario')
                            ->findOneBy(array("id"=>$usuarioEquipe));
                $equipe->addUsuario($usuario);
            }
            //add resources to profile
            $equipe->addRecurso($this->getEm()->getRepository('MyClasses\Entities\AclRecurso')
                            ->findOneBy(array("id"=>1)));
            $equipe->addRecurso($this->getEm()->getRepository('MyClasses\Entities\AclRecurso')
                    ->findOneBy(array("id"=>2)));
            $this->getEm()->persist($equipe);
            $this->getEm()->flush();
            return new ViewModel(array("id"=>$equipe->getId()));
        }
    }
    
}
?>