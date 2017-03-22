<?php declare(strict_types = 1);

include __DIR__ . '/../vendor/autoload.php';

register_shutdown_function(function () {
	Nette\Utils\FileSystem::delete(__DIR__ . '/temp');
});