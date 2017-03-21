<?php declare (strict_types = 1);

namespace OdbavTo\PresenterRoute;


use Nette\Http\IRequest;

class RestRoute
{
	/**
	 * @param string $url
	 * @param string $presenterClassName
	 *
	 * @return Route
	 */
	public static function get(string $url, string $presenterClassName)
	{
		return new Route($url, $presenterClassName, [IRequest::GET]);
	}


	/**
	 * @param string $url
	 * @param string $presenterClassName
	 *
	 * @return Route
	 */
	public static function post(string $url, string $presenterClassName)
	{
		return new Route($url, $presenterClassName, [IRequest::POST]);
	}


	/**
	 * @param string $url
	 * @param string $presenterClassName
	 *
	 * @return Route
	 */
	public static function put(string $url, string $presenterClassName)
	{
		return new Route($url, $presenterClassName, [IRequest::PUT]);
	}


	/**
	 * @param string $url
	 * @param string $presenterClassName
	 *
	 * @return Route
	 */
	public static function delete(string $url, string $presenterClassName)
	{
		return new Route($url, $presenterClassName, [IRequest::DELETE]);
	}


	/**
	 * @param string $url
	 * @param string $presenterClassName
	 *
	 * @return Route
	 */
	public static function patch(string $url, string $presenterClassName)
	{
		return new Route($url, $presenterClassName, [IRequest::PATCH]);
	}


	/**
	 * @param string $url
	 * @param string $presenterClassName
	 *
	 * @return Route
	 */
	public static function head(string $url, string $presenterClassName)
	{
		return new Route($url, $presenterClassName, [IRequest::HEAD]);
	}


	/**
	 * @param string $url
	 * @param string $presenterClassName
	 *
	 * @return Route
	 */
	public static function options(string $url, string $presenterClassName)
	{
		return new Route($url, $presenterClassName, [IRequest::OPTIONS]);
	}
}