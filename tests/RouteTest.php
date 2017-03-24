<?php declare (strict_types = 1);

namespace OdbavTo\PresenterRoute\Tests;


use PHPUnit\Framework\TestCase;
use OdbavTo\PresenterRoute\Route;
use Nette\Http\IRequest;
use Nette\Http\Request as HttpRequest;
use Nette\Http\UrlScript;
use Nette\Http\Url;
use Nette\Application\Request as AppRequest;
use OdbavTo\PresenterRoute\RouteException;

class RouteTest extends TestCase
{	
	private const PRESENTER = 'presenter';
	/**
	 * @test
	 * @dataProvider matchProvider
	 * @param string $route
	 * @param string $url
	 */
	public function match(string $route, string $url, bool $result, array $params = [])
	{
		$httpRequest = new HttpRequest(new UrlScript($url));
		$return = (new Route($route, self::PRESENTER, [IRequest::GET] ))->match($httpRequest);
		
		$appRequest = new AppRequest(
			self::PRESENTER,
			$httpRequest->getMethod(),
			$params,
			$httpRequest->getPost(),
			$httpRequest->getFiles(),
			[AppRequest::SECURED => $httpRequest->isSecured()]
		);
		
		$expected = $result ? $appRequest : null;
		$this->assertEquals($expected, $return);
	}
	
	public function matchProvider()
	{
		return [
			['hele/mese', 'http://www.hele.cz/hele/mese', true],
			['hele/mese/', 'http://www.hele.cz/hele/mese', true],
			['hele/mese', 'http://www.hele.cz/hele/mese/', true],
			['hele/mese', 'http://www.hele.cz/hele/mese/?haha=1', true, ['haha' => 1]],
			['hele/mese/', 'http://www.hele.cz/hele/mese/', true],
			['hele/Mese/', 'http://www.hele.cz/hele/mese/', false],
			['hele/Mese/', 'http://www.hele.cz/hele/Mese/', true],
			['hele/mese/', 'http://www.hele.cz/hele/Mese/', false],
			['hele/mese', 'http://www.hele.cz/hele/', false],
			['hele/', 'http://www.hele.cz/hele/mese', false],
			['he-le/mese/', 'http://www.hele.cz/he-le/mese/', true],
			['he-le/mese/', 'http://www.hele.cz/hele/mese/', false],
			['hele/mese/', 'http://www.hele.cz/he-le/mese/', false],
			['hele/<id>', 'http://www.hele.cz/hele/21', true, ['id' => 21]],
			['hele/<id>/', 'http://www.hele.cz/hele/21', true, ['id' => 21]],
			['he-le/<id>', 'http://www.hele.cz/he-le/21', true, ['id' => 21]],
			['hele/<id>', 'http://www.hele.cz/hele/mese', true, ['id' => 'mese']],
			['hele/<id>', 'http://www.hele.cz/hele/me3se', true, ['id' => 'me3se']],
			['hele/<id>', 'http://www.hele.cz/hele/me-se', true, ['id' => 'me-se']],
			['hele/<i_d>', 'http://www.hele.cz/hele/me-se', true, ['i_d' => 'me-se']],
			['hele/<id>/mese', 'http://www.hele.cz/hele/21', false],
			['hele/<id>/mese', 'http://www.hele.cz/hele/21/mese', true, ['id' => 21]],
			['hele/<id>/<pid>', 'http://www.hele.cz/hele/123/456', true, ['id' => 123, 'pid' => 456]],
		];
	}
	
	/**
	 * @test
	 * @dataProvider constructUrlProvider
	 * @param string $route
	 * @param string $refUrl
	 */
	public function constructUrl(string $route, array $params, string $refUrl, string $expected, string $exception = '')
	{
		$appRequest = new AppRequest(
			self::PRESENTER,
			IRequest::GET,
			$params
		);
		
		if ($exception) {
			$this->expectException($exception);
		}
		
		$return = (new Route($route, self::PRESENTER, [IRequest::GET] ))->constructUrl($appRequest, new Url($refUrl));
		
		$this->assertEquals($expected, $return);
	}
	
	public function constructUrlProvider()
	{
		return [
			['hele/mese', [], 'http://www.hele.cz/hele/', 'http://www.hele.cz/hele/mese'],
			['hele/mese/', [], 'http://www.hele.cz/hele/', 'http://www.hele.cz/hele/mese/'],
			['hele/mese', [], 'http://www.hele.cz/hele', 'http://www.hele.cz/hele/mese'],
			['hele/me-se', [], 'http://www.hele.cz/hele', 'http://www.hele.cz/hele/me-se'],
			['hele/<id>', [], 'http://www.hele.cz/hele', 'http://www.hele.cz/hele/mese', RouteException::class],
			['hele/<id>', ['id' => 123], 'http://www.hele.cz/hele/', 'http://www.hele.cz/hele/123'],
			['hele/<i-d>', ['i-d' => 123], 'http://www.hele.cz/hele/', 'http://www.hele.cz/hele/123'],
			['hele/<id>', ['id' => 'mese'], 'http://www.hele.cz/hele/', 'http://www.hele.cz/hele/mese'],
			['hele/<id>', ['id' => 'me-se'], 'http://www.hele.cz/hele/', 'http://www.hele.cz/hele/me-se'],
			['hele/<id>/', ['id' => 123], 'http://www.hele.cz/hele/', 'http://www.hele.cz/hele/123/'],
			['hele/<id>/ok', ['id' => 123], 'http://www.hele.cz/hele/', 'http://www.hele.cz/hele/123/ok'],
			['hele/<id>/<pid>', ['id' => 123, 'pid' => 456], 'http://www.hele.cz/hele/', 'http://www.hele.cz/hele/123/456'],
			['hele/<id>/<pid>', ['id' => 123], 'http://www.hele.cz/hele/', 'http://www.hele.cz/hele/123/456', RouteException::class],
		];
	}
	
	/**
	 * @dataProvider restProvider
	 * @param string $method
	 * @param string $route
	 * @param string $url
	 * @param bool $result
	 */
	public function testRest(?array $routeMethods, string $requestMethod, bool $result)
	{
		$restRoute = new Route('', self::PRESENTER, $routeMethods);
		
		$httpRequest = new HttpRequest(new UrlScript(), null, null, null, null, null, $requestMethod);
		
		$returned = $restRoute->match($httpRequest);
		
		$this->assertEquals($result, (bool) $returned);
	}
	
	public function restProvider()
	{
		return [
			[[IRequest::GET, IRequest::POST], IRequest::GET, true],
			[[IRequest::GET], IRequest::POST , false],
			[[IRequest::POST], IRequest::POST , true],
			[null, IRequest::POST , true],
			[[], IRequest::POST , false],
		];
	}
}
