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


	    $routeDirection = self::HOME_PAGE_ROUTE;

	    if( self::USER_INFOS_ROUTE === $route && $this->testUserInformationRoute($lastRoute)){
		    $routeDirection = $route;
	    }



	    $event->setResponse(
            new RedirectResponse(
                $this->router->generate(
	                $routeDirection
                )
            )
        );

//        //get curent booking entity
//        $booking = $this->findBooking->getCurrentBooking();
//
//        //test if the current booking has persisted, if not them the workflow has corrupted and force to redirect on
//        //the homepage
//        if (null === $booking->getId()){
//            $event->setResponse(
//                new RedirectResponse(
//                    $this->router->generate(
//                        self::HOME_PAGE_ROUTE
//                    )
//                )
//            );
//        }
    }



    private function testUserInformationRoute($lastRoute)
    {
		return 	 (self::HOME_PAGE_ROUTE === $lastRoute) || (self::CHECK_ORDER_ROUTE === $lastRoute);
    }


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
		if (self::HOME_PAGE_ROUTE !== $route){
			return true;
		}

		return false;
	}
}