<?php declare (strict_types = 1);

namespace OdbavTo\PresenterRoute\Tests;

use PHPUnit\Framework\TestCase;
use OdbavTo\PresenterRoute\RestRoute;
use Nette\Http\Request as HttpRequest;
use Nette\Http\UrlScript;
use Nette\Http\IRequest;

class RestRouteTest extends TestCase
{
	private const PRESENTER = 'presenter';

	/**
	 * @dataProvider restProvider
	 * @param string $method
	 * @param string $route
	 * @param string $url
	 * @param bool $result
	 */
	public function testRest(string $routeMethod, string $requestMethod, bool $result)
	{
		$restRoute = RestRoute::$routeMethod('', self::PRESENTER);
		
		$httpRequest = new HttpRequest(new UrlScript(), null, null, null, null, null, $requestMethod);
		
		$returned = $restRoute->match($httpRequest);
		
		$this->assertEquals($result, (bool) $returned);
	}
	
	public function restProvider()
	{
		return [
			['get', IRequest::GET, true],
			['get', IRequest::POST, false],
			['post', IRequest::POST, true],
			['post', IRequest::GET, false],
			['put', IRequest::PUT, true],
			['delete', IRequest::DELETE, true],
			['head', IRequest::HEAD, true],
			['patch', IRequest::PATCH, true],
			['options', IRequest::OPTIONS, true],
		];
	}
}
