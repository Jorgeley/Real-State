<?php

/**
 * Controlador de Locadores
 */

namespace Application\Controller;

use Zend\View\Model\ViewModel,
    Zend\Mvc\Controller\AbstractActionController,
    Zend\Captcha\Image as Captcha,
    MyClasses\Entities\Locador,
    MyClasses\Conn\Conn;

class LocadorController extends AbstractActionController {
    /**
     * @var Container
     */
    private $sessao;
    
    /**
     *
     * @var Captcha
     */
    private $captcha;

    public function indexAction() {
        return new ViewModel();
    }

    public function novoAction() {
        $this->captcha = new Captcha();
        $this->captcha->setFont(getcwd()."/../www/imobiliaria/fonts/Ubuntu-C.ttf");
        $this->captcha->setImgDir(getcwd()."/../www/imobiliaria/img");
        $this->captcha->setWordlen(3);
        $this->captcha->setDotNoiseLevel(0);
        //echo "captcha ".  $this->captcha->getSession()->word;
        return new ViewModel(array( "captcha" => $this->captcha->generate(), 
                                    "idCaptcha" => $this->captcha->getWord())
                            );
    }
    
    public function gravaAction(){
        if ($this->getRequest()->isPost()){
            $locador = new Locador();
            $locador->setNome($this->getRequest()->getPost("nome"));
            $locador->setSexo($this->getRequest()->getPost("sexo"));
            $locador->setNascimento($this->getRequest()->getPost("nascimento"));
            $locador->setEmail($this->getRequest()->getPost("email"));
            $locador->setFoneFixo($this->getRequest()->getPost("foneFixo"));
            $locador->setFoneCelular($this->getRequest()->getPost("foneCelular"));
            $locador->setCep($this->getRequest()->getPost("cep"));
            $locador->setEndereco($this->getRequest()->getPost("endereco"));
            $locador->setBairro($this->getRequest()->getPost("bairro"));
            $locador->setCidade($this->getRequest()->getPost("cidade"));
            $locador->setUf($this->getRequest()->getPost("uf"));
            $locador->setLogin($this->getRequest()->getPost("login"));
            $locador->setSenha(sha1($this->getRequest()->getPost("senha")));
            $this->getEm()->persist($locador);
            $this->getEm()->flush();
            $msg = "<h2>Confirme seu cadastro</h2>"
                    . "<p>Sr(ª). " . $locador->getNome() . ", acesse o link abaixo para confirmar seu cadastro:</p>"
                    . "<a href='http://imobiliaria.grupo-gpa.com" . 
                            $this->url()->fromRoute('locador/confirma', array(
                                                                        'controller' => 'locador',
                                                                        'action' => 'confirma',
                                                                        'url' => base64_encode($locador->getId())
                                                    ))
                    . "'>confirmar cadastro</a><br>"
                    . "<i><b>Suporte Imobiliaria Grupo GPA</b></i></p>";
            mail($locador->getEmail(), "Confirme seu cadastro", $msg, 'MIME-Version: 1.0' . "\r\n"
                    . 'Content-type: text/html; charset=iso-8859-1' . "\r\n"
                    . 'From: Suporte Imobiliaria <suporte.imobiliaria@grupo-gpa.com>' . "\r\n");
            return new ViewModel(array( "id"=>$locador->getId(),
                                        "nome"=>$locador->getNome()
                                ));
        }
    }
    
    public function confirmaAction(){
        if ($this->getRequest()->isGet()){
            $locador = $this->getEm()->getRepository('MyClasses\Entities\Locador')
                                    ->find(base64_decode($this->Params('url')));
            if (isset($locador)){
                $locador->setStatus("ativo");
                $this->getEm()->persist($locador);
                $this->getEm()->flush();
            }
            return new ViewModel(array("locador"=>$locador));
        }
    }

        private $em;
    /** @return Doctrine\ORM\EntityManager */
    public function getEm(){
        if (null === $this->em){
            $this->em = Conn::getConn();
        }
        return $this->em;
    }

}

?>