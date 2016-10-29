<?php
/**
 * Created by Nicolas PIN <pin.nicolas@free.fr>.
 * Date: 29/10/16
 * Time: 07:41
 */

namespace AppBundle\EventListener;

use AppBundle\Bridge\BridgeBookingORMInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Router;


class BookingExistListener
{
	const WDT_ROUTE = '_wdt';
	const PROFILE_ROUTE = '_profiler';
	const TWIG_ERROR_TEST = '_twig_error_test';

	const HOME_PAGE_ROUTE = 'homepage';
	const CHECK_AUTHOR_ORDER_ROUTE = 'check-author-order';

	/**
	 * @var Router
	 */
	protected $router;

	/**
	 * @var BridgeOrderORMInterface
	 */
	protected $bridgeBookingORM;

	public function __construct(Router $router, BridgeBookingORMInterface $bridgeBookingORM)
	{
		$this->router       = $router;
		$this->bridgeBookingORM  = $bridgeBookingORM;
	}

	public function onKernelRequest(GetResponseEvent $event)
	{
		if(true === $this->isNoControlledRoute($event)){
			return;
		}

		//test if the booking entity exist
		try{
			$this->bridgeBookingORM->getCurrent();
		}
		catch(EntityNotFoundException $e){
			//If the booking has not found
			$event->setResponse(
				new RedirectResponse(
					$this->router->generate(
						self::HOME_PAGE_ROUTE
					)
				)
			);
		}
	}

	private function isNoControlledRoute(GetResponseEvent $event)
	{
		if (!$event->isMasterRequest()) {
			return true;
		}
		//get request format (json, html, etc...)
		$format = $event->getRequest()->getRequestFormat();

		//test if it'is JSON format them ok
		if('json' === $format){
			return true;
		}

		//get current route
		$route = $event->getRequest()->attributes->get('_route');

		//in dev environnement display toolbar
		if(preg_match('/^'.self::PROFILE_ROUTE.'/', $route)){
			return true;
		}
		//in dev environnement display toolbar
		if(preg_match('/^'.self::WDT_ROUTE.'/', $route)){
			return true;
		}

		//test if the current route is the homepage, if it's the case them return (workflow start : ok)
		if (self::HOME_PAGE_ROUTE === $route){
			return true;
		}

		return false;
	}
}