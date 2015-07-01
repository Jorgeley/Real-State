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

    /**
     * pesquisa de imóveis por parâmetro
     * @return ViewModel
     */
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

    /**
     * Visualiza o imóvel selecionado, se Locatario pedir +informações, solicita cadastro e confirma
     * @return ViewModel
     */
    public function visualizaAction() {
        //grava novo Locatario ou recupera Locatario já cadastrado anteriormente
        if ($this->getRequest()->isPost()) {
            //buscando possível Locatário já cadastrado anteriormente
            $criterio = new Criteria();
            $criterio->where($criterio->expr()->eq('nome', $this->getRequest()->getPost('nome')))
                    ->andWhere($criterio->expr()->eq('email', $this->getRequest()->getPost('email')))
                    ->andWhere($criterio->expr()->eq('foneCelular', $this->getRequest()->getPost('telefone')));
            $locatario = $this->getEm()->getRepository('MyClasses\Entities\Locatario')->matching($criterio);
            //se qtd de Locatarios de acordo com os critérios acima for 0...
            if (!$locatario->count()){ //...cadastra novo Locatario...
                $locatario = new Locatario();
                $locatario->setNome($this->getRequest()->getPost('nome'));
                $locatario->setEmail($this->getRequest()->getPost('email'));
                $locatario->setFoneCelular($this->getRequest()->getPost('telefone'));
                $this->getEm()->persist($locatario);
                $this->getEm()->flush();
            //...senão, pega Locatario já cadastrado anteriormente
            }else $locatario = $locatario->first();
            $this->sessao->locatario = $locatario;
        }
        //busca o imóvel e envia pra view
        if ($this->Params('id')){
            $imovel = $this->getEm()->getRepository("MyClasses\Entities\Imovel")->find($this->Params('id'));
            return new ViewModel(array(
                                    'imovel' => $imovel, 
                                    'mais' => $this->Params('mais'),
                                    'locatario' => $this->sessao->locatario));
        }
    }
    
    /**
     * Agenda/Reagenda/Confirma visita pelo Locatario e notifica Locador
     * @return ViewModel
     */
    public function agendavisitaAction(){
        if ($this->Params('id')) //busca imóvel
            $imovel = $this->getEm()->getRepository("MyClasses\Entities\Imovel")->find($this->Params('id'));
        if ($this->Params('visita')) //busca visita do imóvel
            $visita = $this->getEm()->getRepository("MyClasses\Entities\Visita")->find($this->Params('visita'));
        //agenda/reagenda/confirma visita
        if ($this->Params('confirma')) {
            $header = 'MIME-Version: 1.0' . "\r\n"
                . 'Content-type: text/html; charset=iso-8859-1' . "\r\n"
                . 'From: Suporte Imobiliaria <suporte.imobiliaria@grupo-gpa.com>' . "\r\n";
            if ($this->Params('visita')){
                $visita = $this->getEm()->getRepository("MyClasses\Entities\Visita")->find($this->Params('visita'));
                $horarioVisita = $visita->getData();
                //confirmação da visita pelo Locatário
                if ($visita->getData() == $this->getRequest()->getPost('horarioVisita')){
                    $visita->setStatus("confirmada");
                    //notifica Locatario e Locador por e-mail
                    $msg = ", sua visita foi confirmada,<br>"
                            . "acesse o link abaixo para visualizar sua ficha de visita:</p>"
                            . "<a href='http://imobiliaria.grupo-gpa.com" . $this->url()->fromRoute('imovel/fichavisita', array(
                                'controller' => 'imovel',
                                'action' => 'fichavisita',
                                'id' => $this->Params('id')
                            ))
                            . "'>ficha de visita</a><br>"
                            . "<i><b>Suporte Imobiliaria Grupo GPA</b></i></p>";
                    $msgLocatario = "<h2>Visita Confirmada</h2><p>Sr(ª). " . $visita->getLocatario()->getNome() . $msg;
                    $msgLocador = "<h2>Visita Confirmada</h2><p>Sr(ª). " . $visita->getLocador()->getNome() . $msg;
                    mail($visita->getLocatario()->getEmail(), "Visita Confirmada", $msgLocatario, $header);
                    mail($visita->getLocador()->getEmail(), "Visita Confirmada", $msgLocador, $header);
                //reagendamento da visita pelo Locatário
                }else{
                    $visita->setData($this->getRequest()->getPost('horarioVisita'));
                    $visita->setStatus("agendada");
                    //notifica Locador por e-mail
                    $msg = "<p>Sr(ª) " . $visita->getLocador()->getNome() . ", o locatário reagendou a visita,<br>"
                            . "acesse o link abaixo para confirmar ou reagendar a visita:</p>"
                            . "<a href='http://imobiliaria.grupo-gpa.com" . $this->url()->fromRoute('Locador/visita/altera', array(
                                'controller' => 'visita',
                                'action' => 'altera',
                                'id' => $visita->getId()
                            ))
                            . "'>confirmar/reagendar visita</a><br>"
                            . "<i><b>Suporte Imobiliaria Grupo GPA</b></i></p>";
                    mail($visita->getLocador()->getEmail(), "Reagendamento de Visita", $msg, $header);
                }
            //agendamento de nova visita pelo Locatário
            }else{
                $horarioVisita = 0;
                $visita = new Visita();
                $locatario = $this->getEm()->getRepository("MyClasses\Entities\Locatario")->find($this->sessao->locatario->getId());
                $visita->setLocatario($locatario);
                $visita->setLocador($imovel->getLocador());
                $visita->setImovel($imovel);
                $visita->setData($this->getRequest()->getPost('horarioVisita'));
                $visita->setStatus("agendada");
                //notifica Locador por e-mail
                $msg = "<p>Sr(ª) " . $visita->getLocador()->getNome() . ", um locatário solicitou o agendamento de uma visita,<br>"
                        . "acesse o link abaixo para confirmar ou reagendar a visita:</p>"
                        . "<a href='http://imobiliaria.grupo-gpa.com" . $this->url()->fromRoute('Locador/visita/altera', array(
                            'controller' => 'visita',
                            'action' => 'altera',
                            'id' => $visita->getId()
                        ))
                        . "'>confirmar/reagendar visita</a><br>"
                        . "<i><b>Suporte Imobiliaria Grupo GPA</b></i></p>";
                mail($visita->getLocador()->getEmail(), "Agendamento de Visita", $msg, $header);
            }
            $this->getEm()->persist($visita);
            $this->getEm()->flush();
            return new ViewModel(array('id' => $this->Params('id'), 'confirma' => true));
        //monta calendário da semana atual para agendar/reagendar visita
        }else{
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
        }
        return new ViewModel(array(
                                'imovel' => $imovel,
                                'hoje' => new \DateTime("-1 day"),
                                'semanas' => $semanas,
                                'visita' => $this->Params('visita'),
                                'horarioVisita' => $horarioVisita
                            ));
    }

    /**
     * gera ficha de visita do imóvel
     * @return ViewModel
     */
    public function fichavisitaAction() {
        if ($this->Params('id')){
            $imovel = $this->getEm()->getRepository("MyClasses\Entities\Imovel")->find($this->Params('id'));
            return new ViewModel(array("imovel"=>$imovel));
        }
    }

}

?>