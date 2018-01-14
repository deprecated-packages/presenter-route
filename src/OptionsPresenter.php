<?php

namespace OdbavTo\PresenterRoute;

use Nette\Application\BadRequestException;
use Nette\Application\IPresenter;
use Nette\Application\IResponse;
use Nette\Application\Request;
use Nette\Application\Responses\JsonResponse;
use Nette\Http\Response as HttpResponse;

class OptionsPresenter implements IPresenter
{
	/**
	 * @var HttpResponse
	 */
	private $httpResponse;


	public function __construct(HttpResponse $httpResponse)
	{
		$this->httpResponse = $httpResponse;
	}


	public function run(Request $request): IResponse
	{
		$methodsAllowed = $request->getParameter(RestRouteList::ALLOWED_METHODS_KEY);

		if (empty($methodsAllowed)) {
			throw new BadRequestException('There are no HTTP methods allowed.');
		}

		$this->httpResponse->setHeader('Access-Control-Allow-Methods', implode(',', $methodsAllowed));

		return new JsonResponse('');
	}


	public function __invoke(Request $request): IResponse
	{
		return $this->run($request);
	}
}
