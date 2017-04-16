<?php
/**
 * Created by PhpStorm.
 * User: koyuch
 * Date: 11.4.2017
 * Time: 20:34
 */

namespace OdbavTo\PresenterRoute;


interface PathMatcherInterface
{
	public function match(string $route, string $path): ?array;


	public function createUrl(string $route, array $parameters): string;
}