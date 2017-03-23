<?php

namespace OdbavTo\PresenterRoute\Tests;


use PHPUnit\Framework\TestCase;
use OdbavTo\PresenterRoute\Route;
use Nette\Http\IRequest;
use Nette\Http\Request as HttpRequest;
use Nette\Http\UrlScript;
use Nette\Http\Url;
use Nette\Application\Request as AppRequest;

class RouteTest extends TestCase
{	
	/**
	 * 
	 * @dataProvider matchProvider
	 * @param string $route
	 * @param string $url
	 */
	public function match(string $route, string $url, bool $result)
	{
		$httpRequest = new HttpRequest(new UrlScript($url));
		$presenter = 'presenter';
		$return = (new Route($route, $presenter, [IRequest::GET] ))->match($httpRequest);
		
		$appRequest = new AppRequest(
			$presenter,
			$httpRequest->getMethod(),
			$httpRequest->getQuery(),
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
			['hele/mese/', 'http://www.hele.cz/hele/mese/', true],
			['hele/mese', 'http://www.hele.cz/hele/', false],
		];
	}
	
	/**
	 * @test
	 * @dataProvider constructUrlProvider
	 * @param string $route
	 * @param string $refUrl
	 */
	public function constructUrl(string $route, array $params, string $refUrl, string $expected)
	{
		$presenter = 'presenter';
		
		$appRequest = new AppRequest(
			$presenter,
			IRequest::GET,
			$params
		);
		
		$return = (new Route($route, $presenter, [IRequest::GET] ))->constructUrl($appRequest, new Url($refUrl));
		
		$this->assertEquals($expected, $return);
	}
	
	public function constructUrlProvider()
	{
		return [
			['hele/mese', [], 'http://www.hele.cz/hele/', 'http://www.hele.cz/hele/mese'],
			['hele/mese/', [], 'http://www.hele.cz/hele/', 'http://www.hele.cz/hele/mese/'],
			['hele/mese', [], 'http://www.hele.cz/hele', 'http://www.hele.cz/hele/mese'],
			['hele/<id>', ['id' => 123], 'http://www.hele.cz/hele/', 'http://www.hele.cz/hele/123'],
			['hele/<id>/', ['id' => 123], 'http://www.hele.cz/hele/', 'http://www.hele.cz/hele/123/'],
			['hele/<id>/ok', ['id' => 123], 'http://www.hele.cz/hele/', 'http://www.hele.cz/hele/123/ok'],
			['hele/<id>/<pid>', ['id' => 123, 'pid' => 456], 'http://www.hele.cz/hele/', 'http://www.hele.cz/hele/123/456'],
		];
	}
}
