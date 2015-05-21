<?php
/** CONTROLOADOR PADRÃO PARA TODOS OS CONTROLADORES DO MÓDULO "site"
 * @author jorgeley 
 * @version **/

namespace MyClasses\Controllers;
use MyClasses\Conn\Conn,
    Zend\Mvc\Controller\AbstractActionController;

abstract class PadraoControllerSite extends AbstractActionController{
    /** @var Doctrine\ORM\EntityManager */
    private $em;
    
    /**
     * 
     * @return Doctrine\ORM\EntityManager
     */
    public function getEm(){
        if (null === $this->em){
            $this->em = Conn::getConn();
        }
        return $this->em;
    }
    
}