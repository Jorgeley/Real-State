<?php
/**
 * Controlador de Imoveis
 */
namespace Application\Controller;

use Zend\View\Model\ViewModel,
    Zend\Mvc\Controller\AbstractActionController,
    MyClasses\Controllers\PadraoController,
    MyClasses\Conn\Conn;

class ImovelController extends AbstractActionController{
	
    public function indexAction(){
    }
    
    public function pesquisaAction(){
        if ($this->getRequest()->isPost()){
            $pesquisa = $this->getRequest()->getPost("pesquisa");
            $query = Conn::getConn()->createQueryBuilder();
            $query->select("i, b")
                ->from("MyClasses\Entities\Imovel", "i")
                ->leftJoin("i.bairro", "b")
                ->where(
                    $query->expr()->orX(
                        $query->expr()->like("i.descricao", "'%$pesquisa%'"),
                        $query->expr()->like("i.endereco", "'%$pesquisa%'"),
                        $query->expr()->like("b.nome", "'%$pesquisa%'"),
                        $query->expr()->like("b.descricao", "'%$pesquisa%'")
                    )
                );
            $imoveis = $query->getQuery()->getResult();
            return new ViewModel(array('imoveis' => $imoveis));
        }
    }

    public function visualizaAction(){
        return new ViewModel(array('id'=>$this->Params('id'), 'mais'=>$this->Params('mais')));
    }
    
    public function visitaAction(){
        return new ViewModel(array('id'=>$this->Params('id'), 'agenda'=>$this->Params('agenda')));
    }

}
?>