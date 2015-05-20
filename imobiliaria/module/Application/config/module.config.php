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
            '/' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'imovel' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/imovel',
                    'defaults' => array(
                        'action' => 'index',
                        'controller' => 'Application\Controller\Imovel',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'pesquisa' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/pesquisa',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Imovel',
                                'action' => 'pesquisa',
                            ),
                        ),
                    ),
                    'visualiza' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/visualiza/id[/:id][/:mais]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Imovel',
                                'action' => 'visualiza',
                            ),
                        ),
                    ),
                    'agendavisita' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/agendavisita/id[/:id][/:confirma]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Imovel',
                                'action' => 'agendavisita',
                            ),
                        ),
                    ),
                    'fichavisita' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/fichavisita/id[/:id]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Imovel',
                                'action' => 'fichavisita',
                            ),
                        ),
                    )
                ),
            ),
            'locador' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/locador',
                    'defaults' => array(
                        'action' => 'index',
                        'controller' => 'Application\Controller\Locador',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'novo' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/novo',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Locador',
                                'action' => 'novo',
                            ),
                        ),
                    ),
                    'grava' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/grava',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Locador',
                                'action' => 'grava',
                            ),
                        ),
                    ),
                    'confirma' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/confirma[/:url]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Locador',
                                'action' => 'confirma',
                            ),
                        ),
                    ),
                )
            ),
            'conf' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/conf',
                    'defaults' => array(
                        'action' => 'index',
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
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Imovel' => 'Application\Controller\ImovelController',
            'Application\Controller\Locador' => 'Application\Controller\LocadorController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'application/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        )
    ),
        'view_helpers' => array(
            'invokables'=> array(
                'Paginador' => 'Application\Helper\Paginador'
            )
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