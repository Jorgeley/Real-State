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
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
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
        ),
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