<?php

namespace CarrooiTests\Labels;

use Nette\Configurator;
use Tester\FileMock;
use Tester\TestCase as BaseTestCase;

/**
 *
 * @author David Kudera
 */
class TestCase extends BaseTestCase
{


	/**
	 * @return \Nette\DI\Container
	 */
	protected function createContainer()
	{
		copy(__DIR__. '/../LabelsApp/Model/database', TEMP_DIR. '/database');

		$config = new Configurator;
		$config->setTempDirectory(TEMP_DIR);
		$config->addParameters(['appDir' => __DIR__. '/../LabelsApp']);
		$config->addConfig(__DIR__. '/../LabelsApp/config/config.neon');
		$config->addConfig(FileMock::create('parameters: {databasePath: %tempDir%/database}', 'neon'));

		return $config->createContainer();
	}

}
