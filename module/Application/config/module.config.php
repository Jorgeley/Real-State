<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'inicio' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/inicio',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
            ),
            'login' => array(
                'type'=>'literal',
                'options'=>array(
                    'route'=>'/login',
                    'defaults' => array(
                        'action'     => 'login',
                        'controller' => 'Application\Controller\Index',
                    ),
                ),
            ),
            'webservice' => array(
                'type'=>'segment',
                'options'=>array(
                    'route'=>'/webservice',
                    'defaults' => array(
                        'action'     => 'index',
                        'controller' => 'Application\Controller\Webservice',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'autentica' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/autentica',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Webservice',
                                'action'     => 'autentica',
                            ),
                        ),
                    ),
                    'sincroniza' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/sincroniza',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Webservice',
                                'action'     => 'sincroniza',
                            ),
                        ),
                    ),
                    'concluitarefa' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/concluitarefa',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Webservice',
                                'action'     => 'concluitarefa',
                            ),
                        ),
                    ),
                    'projetos' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/projetos',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Webservice',
                                'action'     => 'projetos',
                            ),
                        ),
                    ),
                    'projetosequipes' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/projetosequipes',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Webservice',
                                'action'     => 'projetosequipes',
                            ),
                        ),
                    ),
                    'projetoshoje' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/projetoshoje',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Webservice',
                                'action'     => 'projetoshoje',
                            ),
                        ),
                    ),
                    'projetossemana' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/projetossemana',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Webservice',
                                'action'     => 'projetossemana',
                            ),
                        ),
                    ),
                    'tarefasarquivadas' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/tarefasarquivadas',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Webservice',
                                'action'     => 'tarefasarquivadas',
                            ),
                        ),
                    ),
                    'gravacomentario' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/gravacomentario',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Webservice',
                                'action'     => 'gravacomentario',
                            ),
                        ),
                    ),
                    'gravaprojeto' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/gravaprojeto',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Webservice',
                                'action'     => 'gravaprojeto',
                            ),
                        ),
                    ),
                    'gravatarefa' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/gravatarefa',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Webservice',
                                'action'     => 'gravatarefa',
                            ),
                        ),
                    ),
                    'excluitarefa' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/excluitarefa',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Webservice',
                                'action'     => 'excluitarefa',
                            ),
                        ),
                    ),
                    'getequipes' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/getequipes',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Webservice',
                                'action'     => 'getequipes',
                            ),
                        ),
                    ),
                    'getprojetos' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/getprojetos',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Webservice',
                                'action'     => 'getprojetos',
                            ),
                        ),
                    ),
                    'getusuarios' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/getusuarios',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Webservice',
                                'action'     => 'getusuarios',
                            ),
                        ),
                    ),
                    'soap' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/soap',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Webservice',
                                'action'     => 'soap',
                            ),
                        ),
                    ),
                    'wsdl' => array(
                            'type'    => 'literal',
                            'options' => array(
                                    'route'    => '/wsdl',
                                    'defaults' => array(
                                            'controller' => 'Application\Controller\Webservice',
                                            'action'     => 'wsdl',
                                    ),
                            ),
                    ),
                ),
            ),
            'autentica' => array(
                'type'=>'literal',
                'options'=>array(
                    'route'=>'/autentica',
                    'defaults' => array(
                        'action'     => 'autentica',
                        'controller' => 'Application\Controller\Index',
                    ),
                ),
            ),
            'logoff' => array(
                'type'=>'literal',
                'options'=>array(
                    'route'=>'/logoff',
                    'defaults' => array(
                        'action'     => 'logoff',
                        'controller' => 'Application\Controller\Index',
                    ),
                ),
            ),
            'tarefa' => array(
                'type'=>'literal',
                'options'=>array(
                    'route'=>'/tarefa',
                    'defaults' => array(
                        'action'     => 'index',
                        'controller' => 'Application\Controller\Tarefa',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'novo' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/novo',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Tarefa',
                                'action'     => 'novo',
                            ),
                        ),
                    ),
                    'grava' => array(
                            'type'    => 'literal',
                            'options' => array(
                                    'route'    => '/grava',
                                    'defaults' => array(
                                            'controller' => 'Application\Controller\Tarefa',
                                            'action'     => 'grava',
                                    ),
                            ),
                    ),
                    'edita' => array(
                            'type'    => 'segment',
                            'options' => array(
                                    'route'    => '/edita/id[/:id]',
                                    'defaults' => array(
                                            'controller' => 'Application\Controller\Tarefa',
                                            'action'     => 'edita',
                                    ),
                            ),
                    ),
                    'exclui' => array(
                            'type'    => 'segment',
                            'options' => array(
                                    'route'    => '/exclui/id[/:id]',
                                    'defaults' => array(
                                            'controller' => 'Application\Controller\Tarefa',
                                            'action'     => 'exclui',
                                    ),
                            ),
                    ),
                ),
            ),
            'projeto' => array(
                'type'=>'literal',
                'options'=>array(
                    'route'=>'/projeto',
                    'defaults' => array(
                        'action'     => 'index',
                        'controller' => 'Application\Controller\Projeto',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'novo' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/novo',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Projeto',
                                'action'     => 'novo',
                            ),
                        ),
                    ),
                    'grava' => array(
                            'type'    => 'literal',
                            'options' => array(
                                    'route'    => '/grava',
                                    'defaults' => array(
                                            'controller' => 'Application\Controller\Projeto',
                                            'action'     => 'grava',
                                    ),
                            ),
                    ),
                    'edita' => array(
                            'type'    => 'segment',
                            'options' => array(
                                    'route'    => '/edita/id[/:id]',
                                    'defaults' => array(
                                            'controller' => 'Application\Controller\Projeto',
                                            'action'     => 'edita',
                                    ),
                            ),
                    ),
                    'exclui' => array(
                            'type'    => 'segment',
                            'options' => array(
                                    'route'    => '/exclui/id[/:id]',
                                    'defaults' => array(
                                            'controller' => 'Application\Controller\Projeto',
                                            'action'     => 'exclui',
                                    ),
                            ),
                    ),
                ),
            ),
            'equipe' => array(
                'type'=>'literal',
                'options'=>array(
                    'route'=>'/equipe',
                    'defaults' => array(
                        'action'     => 'index',
                        'controller' => 'Application\Controller\Equipe',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'novo' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/novo',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Equipe',
                                'action'     => 'novo',
                            ),
                        ),
                    ),
                    'grava' => array(
                            'type'    => 'literal',
                            'options' => array(
                                    'route'    => '/grava',
                                    'defaults' => array(
                                            'controller' => 'Application\Controller\Equipe',
                                            'action'     => 'grava',
                                    ),
                            ),
                    ),
                    'edita' => array(
                            'type'    => 'segment',
                            'options' => array(
                                    'route'    => '/edita/id[/:id]',
                                    'defaults' => array(
                                            'controller' => 'Application\Controller\Equipe',
                                            'action'     => 'edita',
                                    ),
                            ),
                    ),
                    'exclui' => array(
                            'type'    => 'segment',
                            'options' => array(
                                    'route'    => '/exclui/id[/:id]',
                                    'defaults' => array(
                                            'controller' => 'Application\Controller\Equipe',
                                            'action'     => 'exclui',
                                    ),
                            ),
                    ),
                ),
            ),
            'usuario' => array(
                'type'=>'literal',
                'options'=>array(
                    'route'=>'/usuario',
                    'defaults' => array(
                        'action'     => 'index',
                        'controller' => 'Application\Controller\Usuario',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'novo' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/novo',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Usuario',
                                'action'     => 'novo',
                            ),
                        ),
                    ),
                    'grava' => array(
                            'type'    => 'literal',
                            'options' => array(
                                    'route'    => '/grava',
                                    'defaults' => array(
                                            'controller' => 'Application\Controller\Usuario',
                                            'action'     => 'grava',
                                    ),
                            ),
                    ),
                    'edita' => array(
                            'type'    => 'segment',
                            'options' => array(
                                    'route'    => '/edita/id[/:id]',
                                    'defaults' => array(
                                            'controller' => 'Application\Controller\Usuario',
                                            'action'     => 'edita',
                                    ),
                            ),
                    ),
                    'exclui' => array(
                            'type'    => 'segment',
                            'options' => array(
                                    'route'    => '/exclui/id[/:id]',
                                    'defaults' => array(
                                            'controller' => 'Application\Controller\Usuario',
                                            'action'     => 'exclui',
                                    ),
                            ),
                    ),
                ),
            ),
            'conf' => array(
                'type'=>'literal',
                'options'=>array(
                    'route'=>'/conf',
                    'defaults' => array(
                        'action'     => 'index',
                        'controller' => 'Application\Controller\Conf',
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
            'Zend\Authentication\AuthenticationService' => 'auth',
        ),
        'invokables' => array(
                'auth' => 'Zend\Authentication\AuthenticationService',
        )
    ),
    'translator' => array(
        'locale' => 'pt_BR',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Projeto' => 'Application\Controller\ProjetoController',
            'Application\Controller\Tarefa' => 'Application\Controller\TarefaController',
            'Application\Controller\Usuario' => 'Application\Controller\UsuarioController',
            'Application\Controller\Equipe' => 'Application\Controller\EquipeController',
            'Application\Controller\Conf' => 'Application\Controller\ConfController',
            'Application\Controller\Webservice' => 'Application\Controller\WebserviceController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    // Doctrine config
    /* 'doctrine' => array(
            'driver' => array(
                    __NAMESPACE__ . '_driver' => array(
                            'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                            'cache' => 'array',
                            'paths' => array(__DIR__ . '/../../MyClasses/src/MyClasses/Entities')
                    ),
                    'orm_default' => array(
                            'drivers' => array(
                                    __NAMESPACE__ . '\Entities' => 'Entities_driver',
                                    'proxy_dir' => __DIR__.'/../../../data/DoctrineORMModule/Proxy',
                                    'proxy_namespace' => 'DoctrineORMModule\Proxy',
                            )
                    )
            )
    ), */
);