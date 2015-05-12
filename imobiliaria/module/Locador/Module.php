<?php
namespace Locador;
use Zend\ModuleManager\ModuleManager,
    Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface{
	
	public function init(ModuleManager $moduleManager)
	{
		$sharedEvents = $moduleManager->getEventManager()->getSharedManager();
		$sharedEvents->attach(__NAMESPACE__, 'dispatch', function($e) {
			// This event will only be fired when an ActionController under the MyModule namespace is dispatched.
			$controller = $e->getTarget();
			$controller->layout('Locador/layout');
		}, 100);
	}
    public function getAutoloaderConfig()
    {
        return array(
//            'Zend\Loader\ClassMapAutoloader' => array(
//                __DIR__ . '/autoload_classmap.php',
//            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}