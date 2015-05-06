<?php
/**
 * Controlador de Imoveis
 */
namespace Application\Controller;

use Zend\View\Model\ViewModel,
    Zend\Mvc\Controller\AbstractActionController,
    MyClasses\Controllers\PadraoController,
    Zend\Session\Container as Sessao,
    MyClasses\Conn\Conn;

class ImovelController extends AbstractActionController{	
    /**
     * @var Container
     */
    private $sessao;
    
    public function __construct(){
        if (!isset($this->sessao)){
        	$this->sessao = new Sessao('ip'.str_replace('.','_', $_SERVER['REMOTE_ADDR']));
        	$this->sessao->setExpirationSeconds(86400);//expira em 24h
        }
    }
	
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
        if ($this->getRequest()->isPost()){
            $this->sessao->nome = $this->getRequest()->getPost('nome');
            $this->sessao->email = $this->getRequest()->getPost('email');
            $this->sessao->telefone = $this->getRequest()->getPost('telefone');
        }
        return new ViewModel(array('id'=>$this->Params('id'), 'mais'=>$this->Params('mais')));
    }
    
    public function visitaAction(){
        return new ViewModel(array('id'=>$this->Params('id'), 'agenda'=>$this->Params('agenda')));
        $msg = "<h2>Visita Confirmada</h2>"
                . "<p>Sr(ª). ".$this->sessao->nome.", sua visita foi confirmada pelo Locador do imovel,<br>"
                . "acesse o link abaixo para visualizar sua ficha de visita:</p>"
                . "<a href='".$this->basePath()."/imovel/visita/id/1/ficha'>ficha de visita</a><br>"
                . "<i><b>Suporte Imobiliaria Grupo GPA</b></i></p>";
        mail($this->sessao->email, 
            "Visita Confirmada", 
            $msg,
            'MIME-Version: 1.0' . "\r\n"
            .'Content-type: text/html; charset=iso-8859-1' . "\r\n"
            .'From: Suporte Imobiliaria <suporte.imobiliaria@grupo-gpa.com>' . "\r\n");
    }

}
?>