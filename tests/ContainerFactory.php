<?php

namespace OdbavTo\PresenterRoute\Tests;


use Nette\Configurator;
use Nette\DI\Container;
use Nette\Utils\FileSystem;

class ContainerFactory
{
	public function create(): Container
	{
		$configurator = new Configurator;
		$configurator->setTempDirectory($this->createTempDir());
		$configurator->addConfig(__DIR__ . '/config/default.neon');

		return $configurator->createContainer();
	}


	private function createTempDir(): string
	{
		$tempDir = __DIR__ . '/temp/' . getmypid();
		FileSystem::delete($tempDir);
		FileSystem::createDir($tempDir);

		return $tempDir;
	}
}
