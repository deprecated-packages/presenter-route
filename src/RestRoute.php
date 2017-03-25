<?php declare (strict_types = 1);

namespace OdbavTo\PresenterRoute;


use Nette\Http\IRequest;

class RestRoute
{
	public static function get(string $route, string $presenterClassName): Route
	{
		return new Route($route, $presenterClassName, [IRequest::GET]);
	}


	public static function post(string $route, string $presenterClassName): Route
	{
		return new Route($route, $presenterClassName, [IRequest::POST]);
	}


	public static function put(string $route, string $presenterClassName): Route
	{
		return new Route($route, $presenterClassName, [IRequest::PUT]);
	}


	public static function delete(string $route, string $presenterClassName): Route
	{
		return new Route($route, $presenterClassName, [IRequest::DELETE]);
	}


	public static function patch(string $route, string $presenterClassName): Route
	{
		return new Route($route, $presenterClassName, [IRequest::PATCH]);
	}


	public static function head(string $route, string $presenterClassName): Route
	{
		return new Route($route, $presenterClassName, [IRequest::HEAD]);
	}


	public static function options(string $route, string $presenterClassName): Route
	{
		return new Route($route, $presenterClassName, [IRequest::OPTIONS]);
	}
}