<?php

/**
 * Controlador de Imoveis
 */

namespace Locador\Controller;

use Zend\View\Model\ViewModel,
    MyClasses\Controllers\PadraoController,
    MyClasses\Conn\Conn,
    MyClasses\Entities\Imovel;

class ImovelController extends PadraoController{

    public function indexAction() {
        $pagina = $this->Params('pagina');
        $inicio = ($pagina == 0) ? 0 : ($pagina - 1) * 10;
        $imoveisPaginados = $this->locador->getImoveisPaginados();
        return new ViewModel(array(
                                'imoveisPaginados' => $imoveisPaginados,
                                'pagina' => $pagina
                            ));
    }
    
    public function novoAction(){
        return new ViewModel(array("etapa" => $this->Params("etapa")));
    }
    
    public function gravaAction(){
        return new ViewModel();
    }

    public function pesquisaAction() {
        if ($this->getRequest()->isPost()) {
            $pesquisa = $this->getRequest()->getPost("pesquisa");
            $query = Conn::getConn()->createQueryBuilder();
            $query->select("i")
                    ->from("MyClasses\Entities\Imovel", "i")
                    ->where(
                            $query->expr()->orX(
                                    $query->expr()->like("i.descricao", "'%$pesquisa%'"), 
                                    $query->expr()->like("i.endereco", "'%$pesquisa%'"), 
                                    $query->expr()->like("i.bairro", "'%$pesquisa%'"), 
                                    $query->expr()->like("i.cidade", "'%$pesquisa%'")
                            )
            );
            $imoveis = $query->getQuery()->getResult();
            return new ViewModel(array('imoveis' => $imoveis));
        }
    }

    public function visualizaAction() {
        if ($this->getRequest()->isPost()) {
            $this->sessao->nome = $this->getRequest()->getPost('nome');
            $this->sessao->email = $this->getRequest()->getPost('email');
            $this->sessao->telefone = $this->getRequest()->getPost('telefone');
        }
        return new ViewModel(array('id' => $this->Params('id'), 'mais' => $this->Params('mais')));
    }

    public function agendavisitaAction() {
        if ($this->Params('confirma') !== null) {
            $msg = "<h2>Visita Confirmada</h2>"
                    . "<p>Sr(ª). " . $this->sessao->nome . ", sua visita foi confirmada pelo Locador do imovel,<br>"
                    . "acesse o link abaixo para visualizar sua ficha de visita:</p>"
                    . "<a href='http://imobiliaria.grupo-gpa.com" . $this->url()->fromRoute('imovel/fichavisita', array('controller' => 'imovel',
                        'action' => 'fichavisita',
                        'id' => 1
                    ))
                    . "'>ficha de visita</a><br>"
                    . "<i><b>Suporte Imobiliaria Grupo GPA</b></i></p>";
            mail($this->sessao->email, "Visita Confirmada", $msg, 'MIME-Version: 1.0' . "\r\n"
                    . 'Content-type: text/html; charset=iso-8859-1' . "\r\n"
                    . 'From: Suporte Imobiliaria <suporte.imobiliaria@grupo-gpa.com>' . "\r\n");
            return new ViewModel(array('id' => $this->Params('id'), 'confirma' => true));
        } else
            return new ViewModel(array('id' => $this->Params('id')));
    }

    public function fichavisitaAction() {
        return new ViewModel();
    }

}

?>