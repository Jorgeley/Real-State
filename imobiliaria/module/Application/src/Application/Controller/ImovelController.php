<?php

/**
 * Property Controller
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
            $this->sessao->setExpirationSeconds(86400); //24h to expire
        }
    }

    public function indexAction() {
        
    }

    /**
     * search properties by parameter
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
     * view the property selected, if tenant ask for more informations, as him to register
     * @return ViewModel
     */
    public function visualizaAction() {
        //save new tenant or get the tenant prior registered
        if ($this->getRequest()->isPost()) {
            //getting posible tenant prior registered
            $criterio = new Criteria();
            $criterio->where($criterio->expr()->eq('nome', $this->getRequest()->getPost('nome')))
                    ->andWhere($criterio->expr()->eq('email', $this->getRequest()->getPost('email')))
                    ->andWhere($criterio->expr()->eq('foneCelular', $this->getRequest()->getPost('telefone')));
            $locatario = $this->getEm()->getRepository('MyClasses\Entities\Locatario')->matching($criterio);
            //if quantity of tenants by criteria above is zero...
            if (!$locatario->count()){ //...register new tenant...
                $locatario = new Locatario();
                $locatario->setNome($this->getRequest()->getPost('nome'));
                $locatario->setEmail($this->getRequest()->getPost('email'));
                $locatario->setFoneCelular($this->getRequest()->getPost('telefone'));
                $this->getEm()->persist($locatario);
                $this->getEm()->flush();
            //...else, get the tenant prior registered
            }else $locatario = $locatario->first();
            $this->sessao->locatario = $locatario;
        }
        //get the property and send to view
        if ($this->Params('id')){
            $imovel = $this->getEm()->getRepository("MyClasses\Entities\Imovel")->find($this->Params('id'));
            return new ViewModel(array(
                                    'imovel' => $imovel, 
                                    'mais' => $this->Params('mais'),
                                    'locatario' => $this->sessao->locatario));
        }
    }
    
    /**
     * Schedule/Reschedule/Confirm the visit by tenant and notify locator
     * @return ViewModel
     */
    public function agendavisitaAction(){
        if ($this->Params('id')) //search the property
            $imovel = $this->getEm()->getRepository("MyClasses\Entities\Imovel")->find($this->Params('id'));
        if ($this->Params('visita')) //search the property's visit
            $visita = $this->getEm()->getRepository("MyClasses\Entities\Visita")->find($this->Params('visita'));
        //Schedule/Reschedule/Confirm the visit
        if ($this->Params('confirma')) {
            $header = 'MIME-Version: 1.0' . "\r\n"
                . 'Content-type: text/html; charset=iso-8859-1' . "\r\n"
                . 'From: Suporte Imobiliaria <suporte.imobiliaria@grupo-gpa.com>' . "\r\n";
            if ($this->Params('visita')){
                $visita = $this->getEm()->getRepository("MyClasses\Entities\Visita")->find($this->Params('visita'));
                $horarioVisita = $visita->getData();
                ////confirmation of the visit by tenant
                if ($visita->getData() == $this->getRequest()->getPost('horarioVisita')){
                    $visita->setStatus("confirmada");
                    //notify tenant and locator by e-mail
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
                //reesceduling the visit by tenant
                }else{
                    $visita->setData($this->getRequest()->getPost('horarioVisita'));
                    $visita->setStatus("agendada");
                    //notify Locator by e-mail
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
            //schedule of a new visit by tenant
            }else{
                $horarioVisita = 0;
                $visita = new Visita();
                $locatario = $this->getEm()->getRepository("MyClasses\Entities\Locatario")->find($this->sessao->locatario->getId());
                $visita->setLocatario($locatario);
                $visita->setLocador($imovel->getLocador());
                $visita->setImovel($imovel);
                $visita->setData($this->getRequest()->getPost('horarioVisita'));
                $visita->setStatus("agendada");
                //notify Locator by e-mail
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
        //build actual week calendár to schedule/reschedule the visit
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
     * generate property visit form
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