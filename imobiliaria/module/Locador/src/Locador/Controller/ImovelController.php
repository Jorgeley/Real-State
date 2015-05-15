<?php

/**
 * Controlador de Imoveis
 */

namespace Locador\Controller;

use Zend\View\Model\ViewModel,
    MyClasses\Controllers\PadraoController,
    Zend\Session\Container as Sessao,
    MyClasses\Img\Img,
    Zend\File\Transfer\Adapter\Http as FileTransfer,
    MyClasses\Conn\Conn,
    MyClasses\Entities\Imovel;

class ImovelController extends PadraoController{
    /**
     * @var Container
     */
    private $sessao;
    /**
     * @var String
     */
    private $path;

    public function __construct() {
        parent::__construct();
        $this->path = __DIR__ . '/../../../../../../www/imobiliaria/arquivos/imoveis/img/';
        if (!isset($this->sessao)) {
            $this->sessao = new Sessao('ip' . str_replace('.', '_', $_SERVER['REMOTE_ADDR']));
            $this->sessao->setExpirationSeconds(86400); //expira em 24h
        }
    }

    public function indexAction() {
        $locador = $this->getEm()->getRepository("MyClasses\Entities\Locador")->find($this->locador->getId());
        $pagina = $this->Params('pagina');
        $inicio = ($pagina == 0) ? 0 : ($pagina - 1) * 5;
        $imoveisPaginados = $locador->getImoveisPaginados(null, 5);
        return new ViewModel(array(
                                'imoveisPaginados' => $imoveisPaginados,
                                'pagina' => $pagina
                            ));
    }
    
    public function novoAction(){
        switch ($this->Params("etapa")){
            case 2:
                if ($this->getRequest()->isPost()) {
                    $this->sessao->tipo =                           $this->getRequest()->getPost("tipo");
                    $this->sessao->areaLote =                       $this->getRequest()->getPost("areaLote");
                    $this->sessao->areaConstruida =                 $this->getRequest()->getPost("areaConstruida");
                    $this->sessao->qtdComodos =                     $this->getRequest()->getPost("qtdComodos");
                    $this->sessao->qtdQuartos =                     $this->getRequest()->getPost("qtdQuartos");
                    $this->sessao->qtdSuites =                      $this->getRequest()->getPost("qtdSuites");
                    $this->sessao->qtdGaragens =                    $this->getRequest()->getPost("qtdGaragens");
                    $this->sessao->condominio =                     $this->getRequest()->getPost("condominio");
                    if ($this->sessao->condominio){
                        $this->sessao->condominioAreasComuns =      $this->getRequest()->getPost("condominioAreasComuns");
                        $this->sessao->condominioAreasPrivativas =  $this->getRequest()->getPost("condominioAreasPrivativas");
                    }
                    $this->sessao->idade =                          $this->getRequest()->getPost("idade");
                }
                break;
            case 3:
                if ($this->getRequest()->isPost()) {
                    $this->sessao->cep =        $this->getRequest()->getPost("cep");
                    $this->sessao->uf =         $this->getRequest()->getPost("uf");
                    $this->sessao->cidade =     $this->getRequest()->getPost("cidade");
                    $this->sessao->bairro =     $this->getRequest()->getPost("bairro");
                    $this->sessao->endereco =   $this->getRequest()->getPost("endereco");
                    $this->sessao->referencia = $this->getRequest()->getPost("referencia");
                }
                break;
            case 4:
                if ($this->getRequest()->isPost()) {
                    $img = new Img(1, //qtd imgs
                        $this->getRequest()->getPost(), //post dos hiddens com as coordenadas do recorte
                        array('/arquivos/imoveis/img/'.$this->locador->getId().'.jpg'), //endereço img origem
                        '/arquivos/imoveis/img/', //caminho relativo da imagem
                        $this->path, //caminho absoluto da imagem
                        200, //largura do recorte
                        150, //altura do recorte
                        $this->locador->getId()); //identificador da img, Ex: $id.jpg (img única) OU $id/1.jpg, $id/2.jpg (várias imgs)
                    $img->recorta();
                }
                break;
            case 5:
                if ($this->getRequest()->isPost()) {
                    $this->sessao->valor = $this->getRequest()->getPost("valor");
                    $this->sessao->valorm2 = $this->getRequest()->getPost("valorm2");
                    $this->sessao->iptu = $this->getRequest()->getPost("iptu");
                    $this->sessao->hipoteca = $this->getRequest()->getPost("hipoteca");
                    if ($this->sessao->hipoteca){
                        $this->sessao->hipotecaBanco = $this->getRequest()->getPost("hipotecaBanco");
                        $this->sessao->hipotecaValorFinanciado = $this->getRequest()->getPost("hipotecaValorFinanciado");
                        $this->sessao->hipotecaQtdParcelas = $this->getRequest()->getPost("hipotecaQtdParcelas");
                        $this->sessao->hipotecaValorParcela = $this->getRequest()->getPost("hipotecaValorParcela");
                    }
                    if ($this->sessao->condominio)
                        $this->sessao->condominioValor = $this->getRequest()->getPost("condominioValor");
                }
                break;
        }
        return new ViewModel(array(
                                "etapa" => $this->Params("etapa"), 
                                "locador" => $this->locador
                            ));
    }

    /**
     * faz upload da imagem de fachada
     */
    public function uploadAction() {
        if ($this->getRequest()->isPost()) {
            $fileTransfer = new FileTransfer();
            $arquivo = $fileTransfer->getFileInfo('fachada');
            $fileTransfer->setDestination($this->path);
            echo $this->sessao->foto = $this->path.$this->locador->getId().'.' . strtolower(substr($arquivo['fachada']['name'], -3, 3));
            $fileTransfer->addFilter('Rename', array(
                'target' => $this->locador->getId().'.' . strtolower(substr($arquivo['fachada']['name'], -3, 3)),
                'overwrite' => true)
            );
            $fileTransfer->receive();
            $this->redirect()->toRoute('Locador/imovel/novo', array('etapa'=>3));
        }
    }
    
    public function gravaAction(){
        if ($this->getRequest()->isPost()){
            //print_r($this->sessao->getArrayCopy());
            $imovel = new Imovel();
            $locador = $this->getEm()->getRepository("MyClasses\Entities\Locador")->find($this->locador->getId());
            $imovel->setLocador($locador);
            $imovel->setTipo($this->sessao->tipo);
            $imovel->setAreaLote($this->sessao->areaLote);
            $imovel->setAreaConstruida($this->sessao->areaConstruida);
            $imovel->setQtdComodos($this->sessao->qtdComodos);
            $imovel->setQtdQuartos($this->sessao->qtdQuartos);
            $imovel->setQtdSuites($this->sessao->qtdSuites);
            $imovel->setQtdGaragens($this->sessao->qtdGaragens);
            $imovel->setCondominio($this->sessao->condominio);
            $imovel->setCondominioValor($this->sessao->condominioValor);
            $imovel->setCondominioAreasComuns($this->sessao->condominioAreasComuns);
            $imovel->setCondominioAreasPrivativas($this->sessao->condominioAreasPrivativas);
            $imovel->setIdade($this->sessao->idade);
            $imovel->setCep($this->sessao->cep);
            $imovel->setUf($this->sessao->uf);
            $imovel->setCidade($this->sessao->cidade);
            $imovel->setBairro($this->sessao->bairro);
            $imovel->setEndereco($this->sessao->endereco);
            $imovel->setReferencia($this->sessao->referencia);
            $imovel->setValor($this->sessao->valor);
            $imovel->setValorm2($this->sessao->valorm2);
            $imovel->setIptu($this->sessao->iptu);
            $imovel->setHipoteca($this->sessao->hipoteca);
            $imovel->setHipotecaBanco($this->sessao->hipotecaBanco);
            $imovel->setHipotecaValorFinanciado($this->sessao->hipotecaValorFinanciado);
            $imovel->setHipotecaQtdParcelas($this->sessao->hipotecaQtdParcelas);
            $imovel->setHipotecaValorParcela($this->sessao->hipotecaValorParcela);
            $imovel->setDescricao($this->getRequest()->getPost("descricao"));
            $imovel->setHorariosVisita($this->getRequest()->getPost("horariosVisita"));
            $imovel->setPublicacao(date("d/m/Y"));
            $imovel->setStatus("ativo");
            $this->getEm()->persist($imovel);
            $this->getEm()->flush();
            rename( $this->sessao->foto, 
                    $this->path.$imovel->getId().".".strtolower(substr($this->sessao->foto,-3,3)));
            return new ViewModel(array(
                "id" => $imovel->getId()
            ));
        }
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
                    . 'Content-type: text/html; char=iso-8859-1' . "\r\n"
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