<?php
/**
 * Controlador de Imoveis
 */
namespace Locador\Controller;

use Zend\View\Model\ViewModel,
    MyClasses\Controllers\PadraoControllerLocador,
    Zend\Session\Container as Sessao,
    MyClasses\Img\Img,
    Zend\File\Transfer\Adapter\Http as FileTransfer,
    MyClasses\Conn\Conn,
    MyClasses\Entities\Imovel;

class VisitaController extends PadraoControllerLocador{

    /**
     * lista as visitas paginadas
     * @return ViewModel
     */
    public function indexAction() {
        $locador = $this->getEm()->getRepository("MyClasses\Entities\Locador")->find($this->locador->getId());
        $pagina = $this->Params('pagina');
        $inicio = ($pagina == 0) ? 0 : ($pagina - 1) * 5;
        $visitasPaginadas = $locador->getVisitasPaginadas($inicio, 5);
        return new ViewModel(array(
                                'visitasPaginadas' => $visitasPaginadas,
                                'pagina' => $pagina
                            ));
    }
    
    /**
     * visualiza a visita e dá opção de alterar/reagendar
     * @return ViewModel
     */
    public function alteraAction(){
        $visita = $this->getEm()->getRepository("MyClasses\Entities\Visita")->find($this->Params('id'));
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
        return new ViewModel(array( 'visita'=>$visita, 
                                    'semanas'=>$semanas, 
                                    'hoje'=>new \DateTime("-1 day")));
    }
    
    public function gravaAction(){
        $visita = $this->getEm()->getRepository("MyClasses\Entities\Visita")->find($this->Params('id'));
        //reagendamento de visita pelo Locador
        if ($this->getRequest()->getPost('horarioVisita')){
            $visita->setData($this->getRequest()->getPost('horarioVisita'));
            $visita->setStatus("reagendada");
            $msg = "<h2>Visita Reagendada</h2>"
                    . "<p>Sr(ª). " . $visita->getLocatario()->getNome() . ", sua visita foi reagendada pelo Locador do imovel,<br>"
                    . "acesse o link abaixo para confirmar ou reagendar novamente a visita:</p>"
                    . "<a href='http://imobiliaria.grupo-gpa.com" . $this->url()->fromRoute('imovel/agendavisita', array(
                        'controller' => 'imovel',
                        'action' => 'agendavisita',
                        'visita' => $visita->getId()
                    ))
                    . "'>confirmar/reagendar visita</a><br>"
                    . "<i><b>Suporte Imobiliaria Grupo GPA</b></i></p>";
            mail($visita->getLocatario()->getEmail(), "Visita Confirmada", $msg, 'MIME-Version: 1.0' . "\r\n"
                    . 'Content-type: text/html; charset=iso-8859-1' . "\r\n"
                    . 'From: Suporte Imobiliaria <suporte.imobiliaria@grupo-gpa.com>' . "\r\n");
        //confirmação de visita pelo Locador
        }else{
            $visita->setStatus("confirmada");
            $msg = ", sua visita foi confirmada,<br>"
                            . "acesse o link abaixo para visualizar sua ficha de visita:</p>"
                            . "<a href='http://imobiliaria.grupo-gpa.com" . $this->url()->fromRoute('imovel/fichavisita', array(
                                'controller' => 'imovel',
                                'action' => 'fichavisita',
                                'id' => $visita->getImovel()->getId()
                            ))
                            . "'>ficha de visita</a><br>"
                            . "<i><b>Suporte Imobiliaria Grupo GPA</b></i></p>";
            $msgLocatario = "<h2>Visita Confirmada</h2><p>Sr(ª). " . $visita->getLocatario()->getNome() . $msg;
            $msgLocador = "<h2>Visita Confirmada</h2><p>Sr(ª). " . $visita->getLocador()->getNome() . $msg;
            mail($visita->getLocatario()->getEmail(), "Visita Confirmada", $msgLocatario, $header);
            mail($visita->getLocador()->getEmail(), "Visita Confirmada", $msgLocador, $header);
        }
        $this->getEm()->persist($visita);
        $this->getEm()->flush();
        return new ViewModel(array('id'=>$visita->getId()));
    }

}

?>