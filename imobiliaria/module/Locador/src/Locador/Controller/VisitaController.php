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
        $visitasPaginadas = $locador->getVisitasPaginadas(null, 5);
        return new ViewModel(array(
                                'visitasPaginadas' => $visitasPaginadas,
                                'pagina' => $pagina
                            ));
    }

}

?>