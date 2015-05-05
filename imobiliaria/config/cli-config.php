<?php
// Define path to your entities and proxies.
$entityPath = '../../../../module/MyClasses/src/MyClasses/Entities/';
$proxyPath = '../../../../module/MyClasses/src/MyClasses/Entities/Proxies/';

// Register the namespace and include path of your entities to autoloader.
$classLoader = new \Doctrine\Common\ClassLoader('Entities', $entityPath);
$classLoader->register();

// Register the namespace and include path of your proxies to autoloader.
$classLoader = new \Doctrine\Common\ClassLoader('Proxies', $proxyPath);
$classLoader->register();

// Setup the configuration.
$config = new \Doctrine\ORM\Configuration();
$driverImpl = $config->newDefaultAnnotationDriver($entityPath);
$config->setMetadataDriverImpl($driverImpl);
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
$config->setProxyDir($proxyPath);
$config->setProxyNamespace('domain\Proxies');

// Specify the connection options to your database.
$connectionOptions = array(
		'driver'    => 'pdo_mysql',
		'user'        => 'root',
		'password'        => '123',
		'dbname'    => 'imobiliaria',
		'host'        => 'localhost'
);

// Get the entity manager.
$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);
use Doctrine\ORM\Tools\Console\ConsoleRunner;
return ConsoleRunner::createHelperSet($em);
/*$helperSet = new \Symfony\Components\Console\Helper\HelperSet(array(
		'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
		'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));*/
