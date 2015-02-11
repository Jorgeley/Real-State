<?php
namespace MyClasses\Conn;
use Doctrine\Tests\ORM\Mapping\NamingStrategyTest;

use Doctrine\ORM\EntityManager;

class Conn{

	public static function getConn(){
		$conn = array(
				'driverClass' 	=> 'Doctrine\DBAL\Driver\PDOMySql\Driver',
				//'wrapperClass' => 'Doctrine\Tests\Mocks\ConnectionMock',
				'host'			=> 'localhost',
				'user'			=> 'root',
				'password'		=> '123',
				'dbname'		=> 'GPA',
		);
		/* $conn = array(
		 'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
				//'wrapperClass' => 'Doctrine\Tests\Mocks\ConnectionMock',
				'host' => 'mysql.hippershopping.com.br',
				'user' => 'hippershopping',
				'password' => 'k2x7w8b6',
				'dbname' => 'hippershopping',
		); */
		$config = new \Doctrine\ORM\Configuration();
		$config->setProxyDir(__DIR__ . '/../Entities/Proxies');
		$config->setProxyNamespace('MyClasses\Entities\Proxies');
		$config->setMetadataDriverImpl($config->newDefaultAnnotationDriver(array(), true));
		$eventManager = new \Doctrine\Common\EventManager();
		return EntityManager::create($conn, $config, $eventManager);
	}
}