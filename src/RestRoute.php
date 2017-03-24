<?php declare (strict_types = 1);

namespace OdbavTo\PresenterRoute;


use Nette\Http\IRequest;

class RestRoute
{
	/**
	 * @param string $route
	 * @param string $presenterClassName
	 *
	 * @return Route
	 */
	public static function get(string $route, string $presenterClassName)
	{
		return new Route($route, $presenterClassName, [IRequest::GET]);
	}


	/**
	 * @param string $route
	 * @param string $presenterClassName
	 *
	 * @return Route
	 */
	public static function post(string $route, string $presenterClassName)
	{
		return new Route($route, $presenterClassName, [IRequest::POST]);
	}


	/**
	 * @param string $route
	 * @param string $presenterClassName
	 *
	 * @return Route
	 */
	public static function put(string $route, string $presenterClassName)
	{
		return new Route($route, $presenterClassName, [IRequest::PUT]);
	}


	/**
	 * @param string $route
	 * @param string $presenterClassName
	 *
	 * @return Route
	 */
	public static function delete(string $route, string $presenterClassName)
	{
		return new Route($route, $presenterClassName, [IRequest::DELETE]);
	}


	/**
	 * @param string $route
	 * @param string $presenterClassName
	 *
	 * @return Route
	 */
	public static function patch(string $route, string $presenterClassName)
	{
		return new Route($route, $presenterClassName, [IRequest::PATCH]);
	}


	/**
	 * @param string $route
	 * @param string $presenterClassName
	 *
	 * @return Route
	 */
	public static function head(string $route, string $presenterClassName)
	{
		return new Route($route, $presenterClassName, [IRequest::HEAD]);
	}


	/**
	 * @param string $route
	 * @param string $presenterClassName
	 *
	 * @return Route
	 */
	public static function options(string $route, string $presenterClassName)
	{
		return new Route($route, $presenterClassName, [IRequest::OPTIONS]);
	}
}