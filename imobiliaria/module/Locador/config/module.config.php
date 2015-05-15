<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Locador;
return array(
    'router' => array(
        'routes' => array(
            'Locador' => array(
                'type' => 'literal',
                'options' => array(
                    'route'    => '/Locador',
                    'defaults' => array(
                        'controller' => 'Locador\Controller\Index',
                        'action' => 'index'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'login' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/login',
                            'defaults' => array(
                                'controller' => 'Locador\Controller\Index',
                                'action' => 'login',
                            ),
                        ),
                    ),
                    'logoff' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/logoff',
                            'defaults' => array(
                                'controller' => 'Locador\Controller\Index',
                                'action' => 'logoff',
                            ),
                        ),
                    ),
                    'imovel' => array(
                        'type' => 'literal',
                        'options' => array(
                            'route' => '/imovel',
                            'defaults' => array(
                                'controller' => 'Locador\Controller\Imovel',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'paginacao' => array(
                                'type' => 'segment',
                                'options' => array(
                                    'route' => '[/:pagina]',
                                    'constraints' => array(
                                        'pagina' => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'controller' => 'Locador\Controller\Imovel',
                                        'action' => 'index',
                                    ),
                                ),
                            ),
                            'novo' => array(
                                'type' => 'segment',
                                'options' => array(
                                    'route' => '/novo[/:etapa]',
                                    'defaults' => array(
                                        'controller' => 'Locador\Controller\Imovel',
                                        'action' => 'novo',
                                    ),
                                ),
                            ),
                            'grava' => array(
                                'type' => 'literal',
                                'options' => array(
                                    'route' => '/grava',
                                    'defaults' => array(
                                        'controller' => 'Locador\Controller\Imovel',
                                        'action' => 'grava',
                                    ),
                                ),
                            ),
                            'upload' => array(
                                'type' => 'literal',
                                'options' => array(
                                    'route' => '/upload',
                                    'defaults' => array(
                                        'controller' => 'Locador\Controller\Imovel',
                                        'action' => 'upload',
                                    ),
                                ),
                            ),
                        ),
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
            'Locador\Controller\Index' => 'Locador\Controller\IndexController',
            'Locador\Controller\Imovel' => 'Locador\Controller\ImovelController',
        ),
    ),
    'view_manager' => array(
		'display_not_found_reason' => true,
		'display_exceptions' => true,
		'doctype' => 'HTML5',
		'template_map' => array(
                    'Locador/layout' => __DIR__ . '/../view/layout/locador.phtml',
                    'Locador/index/index' => __DIR__ . '/../view/locador/index/index.phtml',
                    'formLogin' => __DIR__ . '/../../Application/view/application/locador/index.phtml',
		),
		'template_path_stack' => array('testdrive'=>__DIR__ . '/../view'),
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