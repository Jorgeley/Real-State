<?php

/**
 * Controlador de Imoveis
 */

namespace Application\Controller;

use Zend\View\Model\ViewModel,
    MyClasses\Controllers\PadraoControllerSite,
    Zend\Session\Container as Sessao,
    Doctrine\Common\Collections\Criteria,
    MyClasses\Entities\Locatario,
    MyClasses\Entities\Visita;

class ImovelController extends PadraoControllerSite{
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
            $query = $this->getEm()->createQueryBuilder();
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
            $criterio = new Criteria();
            $criterio->where($criterio->expr()->eq('nome', $this->getRequest()->getPost('nome')))
                    ->andWhere($criterio->expr()->eq('email', $this->getRequest()->getPost('email')))
                    ->andWhere($criterio->expr()->eq('foneCelular', $this->getRequest()->getPost('telefone')));
            $locatario = $this->getEm()->getRepository('MyClasses\Entities\Locatario')->matching($criterio);
            if (!$locatario->count()){
                $locatario = new Locatario();
                $locatario->setNome($this->getRequest()->getPost('nome'));
                $locatario->setEmail($this->getRequest()->getPost('email'));
                $locatario->setFoneCelular($this->getRequest()->getPost('telefone'));
                $this->getEm()->persist($locatario);
                $this->getEm()->flush();
                $this->sessao->locatario = $locatario;
            }
        }
        if ($this->Params('id')){
            $imovel = $this->getEm()->getRepository("MyClasses\Entities\Imovel")->find($this->Params('id'));
            return new ViewModel(array(
                                    'imovel' => $imovel, 
                                    'mais' => $this->Params('mais'),
                                    'locatario' => $this->sessao->locatario));
        }
    }

    public function agendavisitaAction() {
        $imovel = $this->getEm()->getRepository("MyClasses\Entities\Imovel")->find($this->Params('id'));
        if ($this->Params('confirma') !== null) {
            $visita = new Visita();
            $locatario = $this->getEm()->getRepository("MyClasses\Entities\Locatario")->find($this->sessao->locatario->getId());
            $visita->setLocatario($locatario);
            $visita->setLocador($imovel->getLocador());
            $visita->setImovel($imovel);
            $visita->setData($this->getRequest()->getPost('horarioVisita'));
            $visita->setStatus("agendada");
            $this->getEm()->persist($visita);
            $this->getEm()->flush();
            $msg = "<h2>Visita Confirmada</h2>"
                    . "<p>Sr(ª). " . $this->sessao->locatario->getNome() . ", sua visita foi confirmada pelo Locador do imovel,<br>"
                    . "acesse o link abaixo para visualizar sua ficha de visita:</p>"
                    . "<a href='http://imobiliaria.grupo-gpa.com" . $this->url()->fromRoute('imovel/fichavisita', array(
                        'controller' => 'imovel',
                        'action' => 'fichavisita',
                        'id' => $this->Params('id')
                    ))
                    . "'>ficha de visita</a><br>"
                    . "<i><b>Suporte Imobiliaria Grupo GPA</b></i></p>";
            mail($this->sessao->locatario->getEmail(), "Visita Confirmada", $msg, 'MIME-Version: 1.0' . "\r\n"
                    . 'Content-type: text/html; charset=iso-8859-1' . "\r\n"
                    . 'From: Suporte Imobiliaria <suporte.imobiliaria@grupo-gpa.com>' . "\r\n");
            return new ViewModel(array('id' => $this->Params('id'), 'confirma' => true));
        } else{
//            $nSemanaHoje = date("w");
//            $inicioSemana = new \DateTime("-".$nSemanaHoje." days");
//            $fimSemana = new \DateTime("+".(6-$nSemanaHoje)." days");
            $hoje = new \DateTime("-1 day");
            for ($s=0; $s<=6; $s++){
                $hoje->add(new \DateInterval("P1D"));
                switch ($hoje->format('w')){
                    case 0: $semanas[] = 'dom'; break;
                    case 1: $semanas[] = 'seg'; break;
                    case 2: $semanas[] = 'ter'; break;
                    case 3: $semanas[] = 'qua'; break;
                    case 4: $semanas[] = 'qui'; break;
                    case 5: $semanas[] = 'sex'; break;
                    case 6: $semanas[] = 'sab'; break;
                }
            }
            return new ViewModel(array(
                                    'imovel' => $imovel,
                                    'hoje' => new \DateTime("-1 day"),
                                    'semanas' => $semanas
//                                    'inicioSemana' => $inicioSemana,
//                                    'fimSemana' => $fimSemana
                                ));
        }
    }

    public function fichavisitaAction() {
        if ($this->Params('id')){
            $imovel = $this->getEm()->getRepository("MyClasses\Entities\Imovel")->find($this->Params('id'));
            return new ViewModel(array("imovel"=>$imovel));
        }
    }

}

?>