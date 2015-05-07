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
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Locador\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            'locador' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/locador',
                    'defaults' => array(
                        'action' => 'index',
                        'controller' => 'Locador\Controller\Index',
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
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
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
