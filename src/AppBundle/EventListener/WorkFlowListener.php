<?php

namespace AppBundle\EventListener;

use AppBundle\Service\FindBookingsInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Router;

class WorkFlowListener
{
    const HOME_PAGE_ROUTE           = 'homepage';
	const USER_INFOS_ROUTE          = 'user-informations';
	const CHECK_ORDER_ROUTE         = 'check-order';
	const PAYMENT_CHOICE_ROUTE      = 'payment-choice';
	const CHECK_AUTHOR_ORDER_ROUTE  = 'check-author-order';


    const WDT_ROUTE = '_wdt';
    const PROFILE_ROUTE = '_profiler';
    const TWIG_ERROR_TEST = '_twig_error_test';

    protected $router;
    protected $findBooking;

    public function __construct(Router $router, FindBookingsInterface $findBooking)
    {
        $this->router = $router;
        $this->findBooking = $findBooking;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
	    if(true === $this->isNoControlledRoute($event)){
		    return;
	    }

	    //get current route
	    $route = $event->getRequest()->attributes->get('_route');

	    $lastRoute = $this->getLastRoute($event->getRequest());

	    //test if the route provenance is authorized for the current route

	    if( self::USER_INFOS_ROUTE === $route && $this->testUserInformationRoute($lastRoute)){
		   return;
	    }

	    if( self::CHECK_ORDER_ROUTE === $route && $this->testCheckOrderRoute($lastRoute)){
		    return;
	    }

	    if( self::PAYMENT_CHOICE_ROUTE === $route && $this->testPaymentChoice($lastRoute)){
		    return;
	    }

		//if the conditions are not ok, then we redirect the user to the homepage
	    $event->setResponse(
            new RedirectResponse(
                $this->router->generate(
	                self::HOME_PAGE_ROUTE
                )
            )
        );
    }


	/**
	 * authorized route provenance for the user information route
	 *
	 * @param $lastRoute
	 * @return bool
	 */
    private function testUserInformationRoute($lastRoute)
    {
		return 	(self::HOME_PAGE_ROUTE === $lastRoute)
				||
				(self::CHECK_ORDER_ROUTE === $lastRoute)
				||
				(self::USER_INFOS_ROUTE === $lastRoute)		 //for the form validation
				;
    }


	private function testCheckOrderRoute($lastRoute)
	{
		return 	 (self::USER_INFOS_ROUTE === $lastRoute)
				||
				(self::PAYMENT_CHOICE_ROUTE === $lastRoute)
				||
				(self::CHECK_AUTHOR_ORDER_ROUTE === $lastRoute)
				||
				(self::CHECK_ORDER_ROUTE === $lastRoute) //for the form validation
			;
	}


	private function testPaymentChoice($lastRoute)
	{
		return 	(self::CHECK_ORDER_ROUTE === $lastRoute)
				||
				(self::PAYMENT_CHOICE_ROUTE === $lastRoute)
		;
	}

	/**
	 * authorized route provenance for the check order route
	 *
	 * @param $lastRoute
	 * @return bool
	 */
    public function getLastRoute(Request $request)
    {
	    // get last requested path
	    $referer = $request->server->get('HTTP_REFERER');
	    $lastPath = substr($referer, strpos($referer, $request->getBaseUrl()));
	    $lastPath = str_replace($request->getBaseUrl(), '', $lastPath);

	    // get last route
	    $matcher = $this->router->getMatcher();
	    $parameters = $matcher->match($lastPath);

	    return $parameters['_route'];
    }


	/**
	 * test if the event is an event we want to listen or not
	 *
	 * @param GetResponseEvent $event
	 * @return bool
	 */
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