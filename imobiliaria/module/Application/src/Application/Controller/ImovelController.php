<?php

/**
 * Controlador de Imoveis
 */

namespace Application\Controller;

use Zend\View\Model\ViewModel,
    Zend\Mvc\Controller\AbstractActionController,
    Zend\Session\Container as Sessao,
    MyClasses\Conn\Conn;

class ImovelController extends AbstractActionController {
    /**
     * @var Container
     */
    private $sessao;

    public function __construct() {
        if (!isset($this->sessao)) {
            $this->sessao = new Sessao('ip' . str_replace('.', '_', $_SERVER['REMOTE_ADDR']));
            $this->sessao->setExpirationSeconds(86400); //expira em 24h
        }
    }

    public function indexAction() {
        
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
        if ($this->Params('id')){
            $imovel = Conn::getConn()->getRepository("MyClasses\Entities\Imovel")->find($this->Params('id'));
            return new ViewModel(array('imovel' => $imovel, 'mais' => $this->Params('mais')));
        }
    }

    public function agendavisitaAction() {
        if ($this->Params('confirma') !== null) {
            $msg = "<h2>Visita Confirmada</h2>"
                    . "<p>Sr(ª). " . $this->sessao->nome . ", sua visita foi confirmada pelo Locador do imovel,<br>"
                    . "acesse o link abaixo para visualizar sua ficha de visita:</p>"
                    . "<a href='http://imobiliaria.grupo-gpa.com" . $this->url()->fromRoute('imovel/fichavisita', array(
                        'controller' => 'imovel',
                        'action' => 'fichavisita',
                        'id' => $this->Params('id')
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
        if ($this->Params('id')){
            $imovel = Conn::getConn()->getRepository("MyClasses\Entities\Imovel")->find($this->Params('id'));
            return new ViewModel(array("imovel"=>$imovel));
        }
    }

}

?>