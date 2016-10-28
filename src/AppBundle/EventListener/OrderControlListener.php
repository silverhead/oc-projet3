<?php

namespace AppBundle\EventListener;

use AppBundle\Bridge\BridgeOrderORMInterface;
use AppBundle\Manager\OrderManager;
use AppBundle\Service\FindBookingsInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Router;

class OrderControlListener
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
    protected $bridgeOrder;

    public function __construct(Router $router, BridgeOrderORMInterface $bridgeOrder)
    {
        $this->router       = $router;
        $this->bridgeOrder  = $bridgeOrder;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
		if(true === $this->isNoControlledRoute($event)){
			return;
		}

		$order = $this->bridgeOrder->getCurrent();

	    //If there is not order in the session
	    //then we can to display the home page / first booking page
	    if(null === $order){
		    return;
	    }

        $event->setResponse(
            new RedirectResponse(
                $this->router->generate(
                    self::CHECK_AUTHOR_ORDER_ROUTE
                )
            )
        );

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